<?php
namespace controller;

use model\Bdd;
use model\User;
class UsersController extends User
{
    public function send_json($error, $data)
    {
        echo json_encode(array("error" => $error, "data" => $data));
    }

    static public function is_connected ()
    {
        $bdd = new Bdd();
        if (!isset($_SESSION['id']) && !isset($_SESSION['token'])) {
            return false;
        }
        $get = $bdd->getBdd()->prepare('SELECT id, token, login FROM users WHERE id = :id AND token = :token');
        $get->bindParam(':id', $_SESSION['id']);
        $get->bindParam(':token', $_SESSION['token']);
        $get->execute();
        $user = $get->fetch(\PDO::FETCH_ASSOC);
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    public function create ($lastname, $firstname, $email, $login, $pass, $confirm_pass)
    {
        $error_create = "";
        $bdd = new Bdd();

        if (strlen($pass) < 5) {
            $error_create = $error_create . "<p>Password must be at lest 5 characters !!</p>";
        }

        if ($pass !== $confirm_pass) {
            $error_create = $error_create . "<p>Password are not the same !!</p>";
        }
        if ($error_create === "") {
            $hashed_pass = $this->_hash_password($pass);
            if (!$this->_check_login($login)) {
                self::send_json('Login already in use', null);
                return false;
            }
            if (!$this->_check_email($email)) {
                self::send_json('Email already in use', null);
                return false;
            }
            $create = $bdd->getBdd()->prepare('INSERT INTO users (lastname, firstname, email, login, pass, created_at) VALUES (:lastname, :firstname, :email, :login, :pass, NOW())');
            $create->bindParam(':lastname', $lastname);
            $create->bindParam(':firstname', $firstname);
            $create->bindParam(':email', $email);
            $create->bindParam(':login', $login);
            $create->bindParam(':pass', $hashed_pass);
            if (!$create->execute()) {
                self::send_json('A problem occurred while adding your data in the database !! Please contact the admin of the site !!', null);
            } else {
                self::send_json(null, null);
            }
        } else {
            self::send_json($error_create, null);
        }
    }

    public function connexion ($login, $password)
    {

        $bdd = new Bdd();

        $get_user = $bdd->getBdd()->prepare('SELECT id, pass FROM users WHERE (login = :login OR email = :login) AND active = 1');
        $get_user->bindParam(':login', $login);
        $get_user->execute();
        $user = $get_user->fetch(\PDO::FETCH_ASSOC);
        $hashed_pass = $user['pass'];
        if (!$hashed_pass) {
            self::send_json("Bad login or password", null);
            return false;
        }
        if (!$this->_check_password($password, $hashed_pass)) {
            self::send_json("Bad login or password", null);
            return false;
        }
        if (!$this->_update_token($user['id'])) {
            self::send_json("A problem occurred when we create a token for you !! Please contact the admin of the site !!", null);
        } else {
            self::send_json(null, null);
        }
    }

    private function _update_token($id)
    {
        $bdd = new Bdd();

        $token = sha1(time() * rand(1, 555));
        $update_token = $bdd->getBdd()->prepare('UPDATE users SET token = :token WHERE id = :id');
        $update_token->bindParam(':token', $token, \PDO::PARAM_STR, 60);
        $update_token->bindParam(':id', $id);
        if ($update_token->execute()) {
            $_SESSION['token'] = $token;
            $_SESSION['id'] = $id;
            return true;
        } else {
            return false;
        }
    }

    private function _hash_password($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private function _check_password($password, $hash)
    {
        return password_verify($password, $hash);
    }

    private function _check_login($login)
    {
        $bdd = new Bdd();

        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
        } else {
            $id = 0;
        }
        $check = $bdd->getBdd()->prepare('SELECT * FROM users WHERE login = :login AND id != :id AND active = 1');
        $check->bindParam(':login', $login);
        $check->bindParam(':id', $id);
        $check->execute();

        $user = $check->fetch(\PDO::FETCH_ASSOC);
        if ($user) {
            return false;
        } else {
            return true;
        }
    }

    private function _check_email($email)
    {
        $bdd = new Bdd();

        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
        } else {
            $id = 0;
        }
        $check = $bdd->getBdd()->prepare('SELECT email FROM users WHERE email = :email AND id != :id AND active = 1');
        $check->bindParam(':email', $email);
        $check->bindParam(':id', $id);
        $check->execute();
        $user = $check->fetch(\PDO::FETCH_ASSOC);
        if ($user) {
            return false;
        } else {
            return true;
        }
    }

    public function get_user_info ($id) {
        $bdd = new Bdd();
        $check = $bdd->getBdd()->prepare('SELECT id FROM users WHERE  id = :id AND active = 1');
        $check->bindParam(':id', $id);
        $check->execute();
        $user_check = $check->fetch(\PDO::FETCH_ASSOC);
        if ($user_check) {
            $get_user = $bdd->getBdd()->prepare('SELECT lastname, firstname, email, login, avatar, created_at FROM users WHERE  id = :id AND active = 1');
            $get_user->bindParam(':id', $id);
            if ($get_user->execute()) {
                $user_info = $get_user->fetch(\PDO::FETCH_ASSOC);
                $get_user_count_tweet = $bdd->getBdd()->prepare("SELECT COUNT(id) AS 'tweet_user' FROM tweet WHERE id = :id");
                $get_user_count_tweet->bindParam(':id', $id);
                if ($get_user_count_tweet->execute()) {
                    $user_info["tweet_user"] = $get_user_count_tweet->fetch(\PDO::FETCH_ASSOC);
                    self::send_json(null, $user_info);
                } else {
                    self::send_json("A problem occurred when we try to get your user info !! Please contact the admin of the site !!", null);
                }
            } else {
                self::send_json("A problem occurred when we try to get your user info !! Please contact the admin of the site !!", null);
            }
        } else {
            self::send_json("User not found !!", null);
        }
    }

    public function update ($login, $email, $lastname, $firstname)
    {
        $bdd = new Bdd();
        
        if (!$this->_check_login($login)) {
            $this->setError('Login already in use');
            return false;
        }
        if (!$this->_check_email($email)) {
            $this->setError('Email already in use');
            return false;
        }

        $update = $bdd->getBdd()->prepare('UPDATE users SET login = :login, email = :email, lastname = :lastname, firstname = :firstname, updated_at = NOW() WHERE id = :id AND token = :token AND active = 1');
        $update->bindParam(':login', $login);
        $update->bindParam(':email', $email);
        $update->bindParam(':lastname', $lastname);
        $update->bindParam(':firstname', $firstname);
        $update->bindParam(':id', $_SESSION['id']);
        $update->bindParam(':token', $_SESSION['token']);
        if ($update->execute()) {
            //header('Location:./');
            return true;
        } else {
            return false;
        }
    }

    public function update_password($new)
    {
        $bdd = new Bdd();

        $password = $this->_hash_password($new);

        $update = $bdd->getBdd()->prepare('UPDATE users SET password = :password WHERE id = :id AND token = :token AND active = 1');
        $update->bindParam(':password', $password);
        $update->bindParam(':id', $_SESSION['id']);
        $update->bindParam(':token', $_SESSION['token']);
        if ($update->execute()) {
            return true;
        }
        return false;
    }

    public function delete ()
    {
        $bdd = new Bdd();

        $delete = $bdd->getBdd()->prepare('UPDATE users SET active = 0 WHERE id = :id AND token = :token AND active = 1');
        $delete->bindParam(':id', $_SESSION['id']);
        $delete->bindParam(':token', $_SESSION['token']);
        if ($delete->execute()) {
            session_destroy();
            //header('Location:./');
            return true;
        } else {
            return false;
        }
    }
}