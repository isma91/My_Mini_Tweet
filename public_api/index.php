<?php
/**
* Index.php
*
* All ajax request go here and be sended to different Controller
*
* PHP 7.0.6-1+donate.sury.org~xenial+1 (cli) ( NTS )
*
* @category Controller
* @package  Controller
* @author   isma91 <ismaydogmus@gmail.com>
* @license  http://opensource.org/licenses/gpl-license.php GNU Public License
*/
session_start();
require '../autoload.php';
use controller\UsersController;
$user = new UsersController();
if (count($_FILES) !== 0) {
	$user->send_avatar($_FILES["file_avatar"]["name"], $_FILES["file_avatar"]["type"], $_FILES["file_avatar"]["tmp_name"], $_FILES["file_avatar"]["error"], $_FILES["file_avatar"]["size"]);
} else {
	switch ($_POST["action"]) {
		case 'inscription':
		$user->create($_POST["user_lastname"], $_POST["user_firstname"], $_POST["user_email"], $_POST["user_username"], $_POST["user_pass"], $_POST["user_confirm_pass"]);
		break;
		case 'connexion':
		$user->connexion($_POST["user_username"], $_POST["user_pass"]);
		break;
		case 'get_user_info':
		$user->get_user_info($_POST["user_id"]);
		break;
		case 'logout':
		$user->logout($_POST["user_id"], $_POST["token"]);
		break;
		case 'update_lastname_firstname':
		$user->update_lastname_firstname($_POST["user_lastname"], $_POST["user_firstname"]);
		break;
		case 'check_login_exist':
		$user->check_login_exist($_POST["login"]);
		break;
		case 'update_login':
		$user->update_login($_POST["user_login"]);
		break;
		case 'check_email_exist':
		$user->check_email_exist($_POST["email"]);
		break;
		case 'update_email':
		$user->update_email($_POST["user_email"]);
		break;
		case 'update_pass':
		$user->update_pass($_POST["user_actual_pass"], $_POST["user_new_pass"], $_POST["user_confirm_new_pass"]);
		break;
		case 'remove_account':
		$user->remove_account($_POST["user_pass_remove_account"]);
		break;
		case 'send_tweet':
		$user->send_tweet($_POST["tweet"]);
		break;
		case 'get_user_tweet':
		$user->get_user_tweet($_POST["id"], $_POST["page"]);
		break;
		case 'remove_tweet':
		$user->remove_tweet($_POST["id_tweet"], $_POST["user_pass_remove_tweet"]);
		break;
		default:
		echo json_encode(array("error" => "Not a valid action !!", "data" => null));
		break;
	}
}