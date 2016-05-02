<?php
namespace controller;

use model\Bdd;
use model\User;
class UsersController extends User
{
	static public function isConnected ()
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
			$_SESSION['login'] = $user['login'];
			return true;
		}
	}

	public function create ($lastname, $firstname, $login, $email, $pass, $confirm_pass)
	{
		$bdd = new Bdd();

		if (strlen($pass) < 5) {
			$this->setError("Password must be at lest 5 characters !!");
		}
		if ($pass !== $confirm_pass) {
			$this->setError("Password are not the same !!");
			return false;
		}

		$pass = $this->_hashPassword($pass);

		if (!$this->_updateCheckLogin($login)) {
			$this->setError('Login already in use');
			return false;
		}
		if (!$this->_updateCheckEmail($email)) {
			$this->setError('Email already in use');
			return false;
		}

		$create = $bdd->getBdd()->prepare('INSERT INTO users (login, firstname, lastname, email, pass, created_at) VALUES (:login, :firstname, :lastname, :email, :pass, NOW())');
		$create->bindParam(':login', $login);
		$create->bindParam(':lastname', $lastname);
		$create->bindParam(':firstname', $firstname);
		$create->bindParam(':email', $email);
		$create->bindParam(':pass', $pass);
		if ($create->execute()) {
			$this->setError('Congrats ! You can connect');
			return true;
		}


	}

	public function update ($login, $email, $lastname, $firstname)
	{
		$bdd = new Bdd();
		
		if (!$this->_updateCheckLogin($login)) {
			$this->setError('Login already in use');
			return false;
		}
		if (!$this->_updateCheckEmail($email)) {
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
			header('Location:./');
			return true;
		}
	}

	public function updatePassword($new)
	{
		$bdd = new Bdd();

		$password = $this->_hashPassword($new);

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
			header('Location:./');
			return true;
		}
	}

	public function connection ($login, $password)
	{
		$bdd = new Bdd();

		$getUser = $bdd->getBdd()->prepare('SELECT id, password FROM users WHERE (login = :login OR email = :login) AND active = 1');
		$getUser->bindParam(':login', $login);
		$getUser->execute();

		$user = $getUser->fetch(\PDO::FETCH_ASSOC);

		$hash = $user['password'];
		if (!$hash) {
			$this->setError("Bad login or password");
			return false;
		}

		if (!$this->_checkPassword($password, $hash)) {
			$this->setError("Bad login or password");
			return false;
		}

		$this->_updateToken($user['id']);

		return true;
	}

	private function _updateToken($id)
	{
		$bdd = new Bdd();

		$token = sha1(time() * rand(1, 555));
		$updateToken = $bdd->getBdd()->prepare('UPDATE users SET token = :token WHERE id = :id');
		$updateToken->bindParam(':token', $token, \PDO::PARAM_STR, 60);
		$updateToken->bindParam(':id', $id);
		if ($updateToken->execute()) {
			$_SESSION['token'] = $token;
			$_SESSION['id'] = $id;
			return true;
		}
	}

	private function _hashPassword($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}

	private function _checkPassword($password, $hash)
	{
		return password_verify($password, $hash);
	}

	private function _updateCheckLogin($login)
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
		}
		return true;
	}

	private function _updateCheckEmail($email)
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
		if ($user)
			return false;

		return true;
	}
}