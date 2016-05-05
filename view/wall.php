<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="My_Mini_Tweet is a good way to test your own ego !!" />
	<title>Welcome </title>
	<?php include "media.html" ?>
	<script src="media/js/wall.js"></script>
</head>
<body>
	<div class="container" id="the_body">
		<ul id="slide-out" class="side-nav fixed">
			<input name="avatar" id="input_avatar" type="file" />
			<div class="row mui-panel" id="div_user_avatar"></div>
			<div class="row mui-panel">
				<h1 class="title" id="user_lastname_firstname"></h1>
			</div>
			<div class="row mui-panel">
				<h2 class="title" id="user_login"></h2>
			</div>
			<div class="row mui-panel">
				<h3 class="title" id="user_count_tweet"></h3>
			</div>
			<div class="row end_button mui-panel">
				<button class="waves-effect btn-flat" id="change_info_user">Change Info User</button>
			</div>
			<div class="row end_button mui-panel">
				<button class="waves-effect btn-flat" id="logout" token='<?php echo $_SESSION["token"] ?>'>Logout</button>
			</div>
		</ul>
	</div>
	<span id="user_id"><?php echo $_SESSION["id"]; ?></span>
</body>
</html>