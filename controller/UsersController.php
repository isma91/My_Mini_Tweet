<?php
/**
* UsersController.php
*
* A controller to CRUD a user and check some info
*
* PHP 7.0.6-1+donate.sury.org~xenial+1 (cli) ( NTS )
*
* @category Controller
* @package  Controller
* @author   isma91 <ismaydogmus@gmail.com>
* @license  http://opensource.org/licenses/gpl-license.php GNU Public License
*/
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

    public function create($lastname, $firstname, $email, $login, $pass, $confirm_pass)
    {
        $error_create = "";
        $bdd = new Bdd();

        if (strlen($pass) < 5) {
            $error_create = $error_create . "<p>Password must be at lest 5 characters !!</p>";
        }
        if ($pass !== $confirm_pass) {
            $error_create = $error_create . "<p>Password are not the same !!</p>";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_create = $error_create . "<p>Not a valide email !!</p>";
        }
        if (empty($lastname) || empty($email) || empty($login) || empty($pass) || empty($confirm_pass)) {
            $error_create = $error_create . "<p>One of the filed is empty !!</p>";
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

    public function connexion($login, $password)
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
        $check = $bdd->getBdd()->prepare('SELECT login FROM users WHERE login = :login');
        $check->bindParam(':login', $login);
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
        $check = $bdd->getBdd()->prepare('SELECT email FROM users WHERE email = :email');
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

    public function user_exist($id)
    {
        $bdd = new Bdd();
        $check = $bdd->getBdd()->prepare('SELECT id FROM users WHERE  id = :id');
        $check->bindParam(':id', $id);
        $check->execute();
        if ($check->fetch(\PDO::FETCH_ASSOC)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_user_info($id)
    {
        $bdd = new Bdd();
        if (self::user_exist($id)) {
            $get_user = $bdd->getBdd()->prepare('SELECT id, lastname, firstname, email, login, avatar, created_at FROM users WHERE  id = :id AND active = 1');
            $get_user->bindParam(':id', $id);
            if ($get_user->execute()) {
                $user_info = $get_user->fetch(\PDO::FETCH_ASSOC);
                $get_user_count_tweet = $bdd->getBdd()->prepare("SELECT COUNT(id) AS 'tweet_user' FROM tweets WHERE user_id = :id");
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

    public function logout($id, $token)
    {
        $bdd = new Bdd();
        if (self::user_exist($id)) {
            $get_token = $bdd->getBdd()->prepare('SELECT token FROM users WHERE  id = :id AND active = 1');
            $get_token->bindParam(':id', $id);
            $get_token->execute();
            $user_token = $get_token->fetch(\PDO::FETCH_ASSOC);
            if ($user_token["token"] === $token) {
                session_destroy();
                self::send_json(null, null);
            } else {
                self::send_json("Bad token !! Please delete your cache and your cookie of this site !!", null);
            }
        } else {
            self::send_json("User not found !!", null);
        }
    }

    public function check_login_exist($login)
    {
        if (empty($login)) {
            self::send_json("Empty login !!", null);
        } else {
            $bdd = new Bdd();
            $check = $bdd->getBdd()->prepare('SELECT login FROM users WHERE login = :login');
            $check->bindParam(':login', $login);
            $check->execute();
            $user = $check->fetch(\PDO::FETCH_ASSOC);
            if ($user) {
                self::send_json(null, array("user" => "taken"));
            } else {
                self::send_json(null, array("user" => "free"));
            }
        }
    }

    public function check_email_exist($email)
    {
        if (empty($email)) {
            self::send_json("Empty email !!", null);
        } else {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                self::send_json("Not a valide email !!", null);
            } else {
                $bdd = new Bdd();
                $check = $bdd->getBdd()->prepare('SELECT email FROM users WHERE email = :email');
                $check->bindParam(':email', $email);
                $check->execute();
                $user = $check->fetch(\PDO::FETCH_ASSOC);
                if ($user) {
                    self::send_json(null, array("user" => "taken"));
                } else {
                    self::send_json(null, array("user" => "free"));
                }
            }
        }
    }

    public function update_lastname_firstname($lastname, $firstname)
    {
        $bdd = new Bdd();
        if (empty($lastname) || empty($firstname)) {
            self::send_json("Empty lastname or firstname !!", null);
        } else {
            if (self::user_exist($_SESSION["id"])) {
                $update = $bdd->getBdd()->prepare('UPDATE users SET lastname = :lastname, firstname = :firstname WHERE id = :id AND token = :token');
                $update->bindParam(':lastname', $lastname);
                $update->bindParam(':firstname', $firstname);
                $update->bindParam(':id', $_SESSION["id"]);
                $update->bindParam(':token', $_SESSION["token"]);
                if ($update->execute()) {
                    self::send_json(null, null);
                } else {
                    self::send_json("A problem occurred when we try to update your lastname and firstname !! Please contact the admin of the site !!", null);
                }
            } else {
                self::send_json("User not found !!", null);
            }
        }
    }

    public function update_login($login)
    {
        $bdd = new Bdd();
        if (empty($login)) {
            self::send_json("Empty login !!", null);
        } else {
            if (!$this->_check_login($login)) {
                self::send_json("Login already taken !!", null);
            } else {
                if (self::user_exist($_SESSION["id"])) {
                    $update = $bdd->getBdd()->prepare('UPDATE users SET login = :login WHERE id = :id AND token = :token');
                    $update->bindParam(':login', $login);
                    $update->bindParam(':id', $_SESSION["id"]);
                    $update->bindParam(':token', $_SESSION["token"]);
                    if ($update->execute()) {
                        self::send_json(null, null);
                    } else {
                        self::send_json("A problem occurred when we try to update your login !! Please contact the admin of the site !!", null);
                    }
                } else {
                    self::send_json("User not found !!", null);
                }
            }
        }
    }

    public function update_email($email)
    {
        $bdd = new Bdd();
        if (empty($email)) {
            self::send_json("Empty email !!", null);
        } else {
            if (!$this->_check_email($email)) {
                self::send_json("Email already taken !!", null);
            } else {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if (self::user_exist($_SESSION["id"])) {
                        $update = $bdd->getBdd()->prepare('UPDATE users SET email = :email WHERE id = :id AND token = :token');
                        $update->bindParam(':email', $email);
                        $update->bindParam(':id', $_SESSION["id"]);
                        $update->bindParam(':token', $_SESSION["token"]);
                        if ($update->execute()) {
                            self::send_json(null, null);
                        } else {
                            self::send_json("A problem occurred when we try to update your email !! Please contact the admin of the site !!", null);
                        }
                    } else {
                        self::send_json("User not found !!", null);
                    }
                } else {
                    self::send_json("Not a validate email !!", null);
                }
            }
        }
    }

    public function update_pass($actual_pass, $new_pass, $confirm_new_pass)
    {
        if (empty($actual_pass) === true || empty($new_pass) === true || empty($confirm_new_pass) === true) {
            self::send_json("Actual Pass, New Pass or Confirm Pass empty !!", null);
        } elseif (strlen($actual_pass) < 5 || strlen($new_pass) < 5 || strlen($confirm_new_pass) < 5) {
            self::send_json("Actual Pass, New Pass and Confirm Pass must be at least 5 characters !!", null);
        } else {
            if ($new_pass !== $confirm_new_pass) {
                self::send_json("New Pass and Confirm Pass must be at least 5 characters !!", null);
            } else {
                $bdd = new Bdd();
                $check = $bdd->getBdd()->prepare("SELECT pass FROM users WHERE id = :id AND token = :token");
                $check->bindParam(":id", $_SESSION["id"]);
                $check->bindParam(":token", $_SESSION["token"]);
                if ($check->execute()) {
                    $check_pass = $check->fetch(\PDO::FETCH_ASSOC);
                    if (!$this->_check_password($actual_pass, $check_pass["pass"])) {
                        self::send_json("Wrong actual password !!", null);
                    } else {
                        $new_pass_hashed = $this->_hash_password($new_pass);
                        $update = $bdd->getBdd()->prepare('UPDATE users SET pass = :pass WHERE id = :id AND token = :token AND active = 1');
                        $update->bindParam(':pass', $new_pass_hashed);
                        $update->bindParam(':id', $_SESSION['id']);
                        $update->bindParam(':token', $_SESSION['token']);
                        if ($update->execute()) {
                            self::send_json(null, null);
                        } else {
                            self::send_json("A problem occurred when we try to update your new password !! Please contact the admin of the site !!", null);
                        }
                    }
                } else {
                    self::send_json("A problem occurred when we try to check your password !! Please contact the admin of the site !!", null);
                }
            }
        }
    }

    public function remove_account ($password)
    {
        if (empty($password)) {
            self::send_json("Password empty !!", null);
        } elseif (strlen($password) < 5) {
            self::send_json("Password must be at least 5 characters !!", null);
        }
        else {
            $bdd = new Bdd();
            $check = $bdd->getBdd()->prepare("SELECT pass FROM users WHERE id = :id AND token = :token");
            $check->bindParam(":id", $_SESSION["id"]);
            $check->bindParam(":token", $_SESSION["token"]);
            if ($check->execute()) {
                $check_pass = $check->fetch(\PDO::FETCH_ASSOC);
                if (!$this->_check_password($password, $check_pass["pass"])) {
                    self::send_json("Wrong password !!", null);
                } else {
                    $bdd = new Bdd();
                    $delete = $bdd->getBdd()->prepare('UPDATE users SET active = 0 WHERE id = :id AND token = :token AND active = 1');
                    $delete->bindParam(':id', $_SESSION['id']);
                    $delete->bindParam(':token', $_SESSION['token']);
                    if ($delete->execute()) {
                        session_destroy();
                        self::send_json(null, null);
                    } else {
                        self::send_json("A problem occurred when we try to remove your account !! Please contact the admin of the site !!", null);
                    }
                }
            }
        }
    }

    public function send_tweet($tweet)
    {
        if (empty($tweet)) {
            self::send_json("Empty tweet !!", null);
        } elseif (strlen($tweet) > 120) {
            self::send_json("Tweet don't be more than 120 characters !!", null);
        } else {
            $bdd = new Bdd();
            if (self::user_exist($_SESSION["id"])) {
                $get_token = $bdd->getBdd()->prepare('SELECT token FROM users WHERE  id = :id AND active = 1');
                $get_token->bindParam(':id', $_SESSION["id"]);
                $get_token->execute();
                $user_token = $get_token->fetch(\PDO::FETCH_ASSOC);
                if ($user_token["token"] === $_SESSION["token"]) {
                    $insert_tweet = $bdd->getBdd()->prepare('INSERT INTO tweets (user_id, content, created_at) VALUES (:user_id, :content, NOW())');
                    $insert_tweet->bindParam(":user_id", $_SESSION["id"]);
                    $insert_tweet->bindParam(":content", $tweet);
                    if ($insert_tweet->execute()) {
                        self::send_json(null, null);
                    } else {
                        self::send_json("A problem occurred when we try to add the tweet in the database !! Please contact the admin of the site !!", null);
                    }
                } else {
                    self::send_json("Bad token !! Please delete your cache and your cookie of this site !!", null);
                }
            } else {
                self::send_json("User not found !!", null);
            }
        }
    }

    public function send_avatar($file_name, $file_type, $file_tmp_name, $file_error, $file_size)
    {
        if ($file_error === 0) {
            if (!empty($file_name)) {
                if (substr($file_type, 0, 5) === "image") {
                    if ($file_size < 5242880) {
                        $bdd = new Bdd();
                        if (self::user_exist($_SESSION["id"])) {
                            $get_token = $bdd->getBdd()->prepare('SELECT token FROM users WHERE  id = :id AND active = 1');
                            $get_token->bindParam(':id', $_SESSION["id"]);
                            $get_token->execute();
                            $user_token = $get_token->fetch(\PDO::FETCH_ASSOC);
                            if ($user_token["token"] === $_SESSION["token"]) {
                                $user_avatar_folder = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "media" . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $_SESSION["id"] . DIRECTORY_SEPARATOR;
                                if (!is_dir($user_avatar_folder)) {
                                    mkdir($user_avatar_folder, 0777, true);
                                }
                                if (file_exists($user_avatar_folder . DIRECTORY_SEPARATOR . $file_name)) {
                                    $exploded_file_name = explode(".", $file_name);
                                    $exploded_file_name[0] = $exploded_file_name[0] . sha1(rand());
                                    for ($i = 0; $i < count($exploded_file_name); $i = $i + 1) {
                                        if ($i !== (count($exploded_file_name) -1)) {
                                            $exploded_file_name[$i] = $exploded_file_name[$i] . ".";
                                        }
                                    }
                                    $file_name = implode('', $exploded_file_name);
                                }
                                if (rename($file_tmp_name, $user_avatar_folder . DIRECTORY_SEPARATOR . $file_name)) {
                                    chmod($user_avatar_folder . DIRECTORY_SEPARATOR . $file_name, 0777);
                                    $change_avatar = $bdd->getBdd()->prepare('UPDATE users SET avatar = :avatar WHERE id = :id AND token = :token AND active = 1');
                                    $change_avatar->bindParam(":avatar", $file_name);
                                    $change_avatar->bindParam(":id", $_SESSION["id"]);
                                    $change_avatar->bindParam(":token", $_SESSION["token"]);
                                    if ($change_avatar->execute()) {
                                        self::send_json(null, null);
                                    } else {
                                        self::send_json("A problem occurred when we try to upload your avatar file in the database !! Please contact the admin of the site !!", null);
                                    }
                                } else {
                                    self::send_json("A problem occurred when we try to upload your avatar file !! Please contact the admin of the site !!", null);
                                }
                            } else {
                                self::send_json("Bad token !! Please delete your cache and your cookie of this site !!", null);
                            }
                        }
                    } else {
                        self::send_json("Your avatar file is more than 5 Mo !!", null);
                    }
                } else {
                    self::send_json("Your avatar file is not an image !!", null);
                }
            } else {
                self::send_json("Empty avatar file name !!", null);
            }
        } else {
            self::send_json("A problem occurred when we try to get your avatar file !! Please contact the admin of the site !!", null);
        }
    }

    public function get_user_tweet($id)
    {
        $bdd = new Bdd();
        if (self::user_exist($id)) {
            $get_token = $bdd->getBdd()->prepare('SELECT token FROM users WHERE  id = :id AND active = 1');
            $get_token->bindParam(':id', $id);
            $get_token->execute();
            $user_token = $get_token->fetch(\PDO::FETCH_ASSOC);
            if ($user_token["token"] === $_SESSION["token"]) {
                $get_tweet = $bdd->getBdd()->prepare("SELECT * FROM tweets WHERE user_id = :user_id");
                $get_tweet->bindParam(":user_id", $id);
                if ($get_tweet->execute()) {
                    self::send_json(null, $get_tweet->fetchAll(\PDO::FETCH_ASSOC));
                } else {
                    self::send_json("A problem occurred when we try to get all of your tweets in the database !! Please contact the admin of the site !!", null);
                }
            } else {
                self::send_json("Bad token !! Please delete your cache and your cookie of this site !!", null);
            }
        } else {
            self::send_json("User not found !!", null);
        }
    }
}