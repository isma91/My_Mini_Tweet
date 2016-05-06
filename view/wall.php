<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="My_Mini_Tweet is a good way to test your own ego !!" />
	<title>Welcome to My_Mini_Tweet</title>
	<?php include "media.html" ?>
	<script src="media/js/wall.js"></script>
</head>
<body>
	<div class="container" id="the_body">
		<ul id="slide-out" class="side-nav fixed">
			<form id="form_avatar" enctype="multipart/form-data" method="POST">
				<div class="file-field input-field">
					<div class="btn">
						<span>File</span>
						<input type="file" name="input_avatar" id="input_avatar" accept="image/*">
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>
				<div class="row end_button mui-panel">
					<button class="waves-effect btn-flat" id="send_avatar">Upload avatar</button>
				</div>
			</form>
			<div class="row mui-panel" id="div_user_avatar"></div>
			<div class="row end_button mui-panel danger-zone">				
				<button class="waves-effect btn-flat" id="delete_avatar">Delete avatar</button>
			</div>
			<div class="row mui-panel">
				<h1 class="title" id="user_lastname_firstname"></h1>
				<h2 id="user_login_wall"></h2>
				<h3 id="user_email_wall"></h3>
			</div>
			<div class="row mui-panel">
				<h4 id="user_count_tweet"></h4>
				<h5 id="user_created_at"></h5>
			</div>
			<div class="row end_button mui-panel">
				<button class="waves-effect btn-flat" id="change_pass">Change your password</button>
			</div>
			<div class="row end_button mui-panel">
				<button class="waves-effect btn-flat" id="display_hide_form_tweet">Form tweet<i class="material-icons right" id="display_hide_form_tweet_icon">visibility</i></button>
			</div>
			<div class="row end_button mui-panel">
				<button class="waves-effect btn-flat" id="logout" token='<?php echo $_SESSION["token"] ?>'>Logout</button>
			</div>
			<div class="row end_button mui-panel danger-zone">
				<button class="waves-effect btn-flat" id="remove_user">Remove This Account</button>
			</div>
		</ul>
		<div id="user_tweet" class="container">
		</div>
		<div id="tweet_pagination" class="container">
		</div>
		<div id="form_tweet" class="container">
			<div class="container">
				<div class="container input-field col s12">
					<i class="material-icons prefix">message</i>
					<textarea id="tweet_form" class="materialize-textarea" length="120"></textarea>
					<label for="tweet_form">Tweet</label>
				</div>
				<div class="row end_button">
					<button class="waves-effect btn-flat" id="send_tweet">Send the tweet<i class="material-icons right">send</i></button>
				</div>
			</div>
		</div>
		<div id="modal_info_user"></div>
		<div id="modal_user_lastname_firstname"></div>
		<div id="modal_user_login"></div>
		<div id="modal_user_email"></div>
		<div id="modal_user_pass"></div>
		<div id="modal_user_remove"></div>
		<div id="modal_tweet_remove"></div>
		<div id="modal_remove_avatar" class="modal bottom-fixed-footer">
			<div class="modal-content">
				<h1 class="title">To remove your avatar, write your password !!</h1>
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">vpn_key</i>
						<input id="avatar_confirm_remove" type="password">
						<label for="avatar_confirm_remove">Password</label>
					</div>
				</div>
				<div class="row" id="div_error_remove_avatar"></div>
			</div>
			<div class="modal-footer end_button">
				<button class="modal-action modal-close waves-effect btn-flat left" id="cancel_info_user">Cancel</button>
				<button class="waves-effect btn-flat right" id="apply_remove_avatar">Apply Changing</button>
			</div>
		</div>
		<div id="modal_change_tweet" class="modal bottom-fixed-footer">
			<div class="modal-content">
				<h1 class="title">Change this tweet !!</h1>
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">message</i>
						<textarea id="tweet_change" class="materialize-textarea" length="120" placeholder="Tweet"></textarea>
					</div>
				</div>
				<div class="row" id="div_error_change_tweet"></div>
			</div>
			<div class="modal-footer end_button">
				<button class="modal-action modal-close waves-effect btn-flat left" id="cancel_info_user">Cancel</button>
				<button class="waves-effect btn-flat right" id="apply_change_tweet">Apply Changing</button>
			</div>
		</div>
		<span id="user_id"><?php echo $_SESSION["id"]; ?></span>
		<span id="tweet_id_to_remove"></span>
		<span id="tweet_id_to_change"></span>
		<span id="user_count_tweet_pagination_number"></span>
	</body>
	</html>