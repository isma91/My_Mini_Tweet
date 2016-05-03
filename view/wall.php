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
			<div class="row" id="div_user_avatar"></div>
			<div class="row">
				<h1 class="title" id="user_lastname_firstname"></h1>
			</div>
			<div class="row">
				<h2 class="title" id="user_login"></h2>
			</div>
			<div class="row mui-panel">
				<h3 class="title" id="user_count_tweet"></h3>
			</div>
		</ul>
	</div>
	<span id="user_id"><?php echo $_SESSION["id"]; ?></span>
</body>
</html>