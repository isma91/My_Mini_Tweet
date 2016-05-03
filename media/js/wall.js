/*jslint browser: true, node : true*/
/*jslint devel : true*/
/*global $, document, this*/
$(document).ready(function(){
	var path_to_ajax;
	path_to_ajax = "public_api/index.php";
	$.post(path_to_ajax, {action: 'get_user_info', user_id: $("#user_id").text()}, function(data, textStatus) {
		if (textStatus === "success") {
			data = JSON.parse(data);
			if (data.error === null) {
				console.log(data.data);
				if (data.data.avatar === null) {
					$('#div_user_avatar').html("<i class='material-icons large'>add_a_photo</i>");
				}
				$('#user_lastname_firstname').html(data.data.lastname + " " + data.data.firstname);
				$("#user_login").html('@' + data.data.login);
				if (data.data.tweet_user.tweet_user == 0) {
					$('#user_count_tweet').html("No tweet send !!");
				} else if (data.data.tweet_user.tweet_user == 1) {
					$('#user_count_tweet').html("You send <span id='count_tweet'>a single</span> tweet !!");
				} else {
					$('#user_count_tweet').html("You send <span id='count_tweet'>" + data.data.tweet_user.tweet_user + "</span> tweets !!");
				}
			} else {
				Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
			}
		} else {
			Materialize.toast('<p class="alert-failed">a problem occurred while getting your data in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
		}
	});
	$(document).on('click', '#div_user_avatar', function() {
		$('#input_avatar').trigger('click');
		console.log("display form to add another avatar and list other avatar of the user like an history");
	});
	$(document).on('click', '#logout', function() {
		$.post(path_to_ajax, {action: 'logout', user_id: $('#user_id').text(), token: $(this).attr('token')}, function(data, textStatus) {
			if (textStatus === "success") {
				data = JSON.parse(data);
				console.log(data);
				if (data.error === null) {
					Materialize.toast('<p class="alert-success">Logout success !!<p>', 500, 'rounded alert-success');
					setTimeout(function () {
						window.location = "?page=home";
					}, 750);
				} else {
					Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
				}
			} else {
				Materialize.toast('<p class="alert-failed">a problem occurred while getting your data in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
			}
		});
	});
});