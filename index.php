<?php
/**
* Index.php
*
* Index who check if the project is installed or not
* Switch view compared to the request
*
* PHP 7.0.6-1+donate.sury.org~xenial+1 (cli) ( NTS )
*
* @category Controller
* @package  Controller
* @author   isma91 <ismaydogmus@gmail.com>
* @license  http://opensource.org/licenses/gpl-license.php GNU Public License
*/
session_start();
require 'autoload.php';
use controller\UsersController;

function go_to_view ($page) {
	include "./view/" . $page;
}

$connected = UsersController::is_connected();

if (isset($_GET)) {
	switch ($_GET["page"]) {
		case 'home':
		if ($connected === false) {
			go_to_view("home_page.php");
		} else {
			go_to_view("wall.php");
		}
		break;
		case 'wall':
		if ($connected === false) {
			go_to_view("home_page.php");
		} else {
			go_to_view("wall.php");
		}
		break;
		default:
		if ($connected === false) {
			go_to_view("home_page.php");
		} else {
			go_to_view("wall.php");
		}
		break;
	}
} else {
	if ($connected === false) {
		go_to_view("home_page.php");
	} else {
		go_to_view("wall.php");
	}
}