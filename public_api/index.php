<?php
session_start();
require '../autoload.php';
use controller\UsersController;
$user = new UsersController();

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
	default:
	echo json_encode(array("error" => "Not a valid action !!", "data" => null));
	break;
}