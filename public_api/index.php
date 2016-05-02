<?php
session_start();
require '../autoload.php';
use controller\UsersController;
$user = new UsersController();

switch ($_POST["action"]) {
	case 'inscription':
	echo json_encode(array("error" => "Not a valid action !!", "data" => null));
	break;
	default:
	echo json_encode(array("error" => "Not a valid action !!", "data" => null));
	break;
}