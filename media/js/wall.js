/*jslint browser: true, node : true*/
/*jslint devel : true*/
/*global $, document, this*/
$(document).ready(function(){
	var path_to_ajax, form_tweet_count_button, error_change_lastname_firstname, error_change_login, error_change_email, error_actual_pass, error_new_pass, error_confirm_new_pass, form_data_avatar, div_user_tweet, i, div_tweet_pagination, error_remove_tweet;
	form_tweet_count_button = 0;
	path_to_ajax = "public_api/index.php";
	function press_enter (selector, go_function) {
		$(document).on('keyup', selector, function(event) {
			if (event.keyCode === 13) {
				go_function();
			}
		});
	}
	function get_user_info () {
		$.post(path_to_ajax, {action: 'get_user_info', user_id: $("#user_id").text()}, function(data, textStatus) {
			if (textStatus === "success") {
				data = JSON.parse(data);
				if (data.error === null) {
					if (data.data.avatar === null) {
						$('#div_user_avatar').html("<i class='material-icons large'>add_a_photo</i>");
					} else {
						$("#div_user_avatar").html('<img class="responsive-img" src="media/avatar/' + data.data.id + "/" + data.data.avatar + '">');
					}
					$('#user_lastname_firstname').html('<span id="only_user_lastname_firstname">' + data.data.lastname + ' ' + data.data.firstname + '</span> <i class="material-icons" id="change_lastname_firstname">mode_edit</i>');
					$('#modal_user_lastname_firstname').html('<div id="modal_change_lastname_firstname" class="modal modal-fixed-footer"><div class="modal-content"><h1 class="title">Change your lastname and your firstname !!</h1><div class="row"><div class="input-field col s12"><i class="material-icons prefix">account_box</i><input value="' + data.data.lastname + '" id="user_lastname_change" type="text" placeholder="Lastname"></div></div><div class="row"><div class="input-field col s12"><i class="material-icons prefix">account_circle</i><input value="' + data.data.firstname + '" id="user_firstname_change" type="text" placeholder="Firstname"></div></div><div class="row" id="div_error_lastname_firstname"></div></div><div class="modal-footer end_button"><button class="modal-action modal-close waves-effect btn-flat left" id="cancel_info_user">Cancel</button><button class="waves-effect btn-flat right" id="apply_changing_lastname_firstname">Apply Changing</button></div></div>');
					$("#user_login_wall").html('@<span id="only_user_login">' + data.data.login + '</span> <i class="material-icons" id="change_login">mode_edit</i>');
					$("#modal_user_login").html('<div id="modal_change_login" class="modal bottom-fixed-footer"><div class="modal-content"><div class="row"><div class="input-field col s12"><i class="material-icons prefix">face</i><input value="' + data.data.login + '" id="user_username_change" type="text" placeholder="Login"></div></div><div class="row" id="div_error_login"></div></div><div class="modal-footer end_button"><button class="modal-action modal-close waves-effect btn-flat left" id="cancel_info_user">Cancel</button><button class="waves-effect btn-flat right" id="apply_changing_login">Apply Changing</button></div></div>');
					if (data.data.tweet_user.tweet_user == 0) {
						$('#user_count_tweet').html("No tweet send !!");
					} else if (data.data.tweet_user.tweet_user == 1) {
						$('#user_count_tweet').html("You send <span id='count_tweet'>a single</span> tweet !!");
					} else {
						$('#user_count_tweet').html("You send <span id='count_tweet'>" + data.data.tweet_user.tweet_user + "</span> tweets !!");
					}
					$('#user_email_wall').html('<span id="only_user_email">' + data.data.email + '</span>' + ' <i class="material-icons" id="change_email">mode_edit</i>');
					$("#modal_user_email").html('<div id="modal_change_email" class="modal bottom-fixed-footer"><div class="modal-content"><h1 class="title">Change your email here !!</h1><div class="row"><div class="input-field col s12"><i class="material-icons prefix">email</i><input value="' + data.data.email + '" id="user_email_change" type="email" placeholder="Email"></div></div><div class="row" id="div_error_email"></div></div><div class="modal-footer end_button"><button class="modal-action modal-close waves-effect btn-flat left" id="cancel_info_user">Cancel</button><button class="waves-effect btn-flat right" id="apply_changing_email">Apply Changing</button></div></div>');
					//$('#user_created_at').html(""data.data.created_at);
					$('#user_created_at').html();
					$("#modal_user_pass").html('<div id="modal_change_pass" class="modal bottom-fixed-footer"><div class="modal-content"><h1 class="title">Change your password here !!</h1><div class="row"><div class="input-field col s12"><i class="material-icons prefix">vpn_key</i><input id="user_actual_pass" type="password"><label for="user_actual_pass">Actual Password</label></div></div><div class="row"><div class="input-field col s12"><i class="material-icons prefix">vpn_key</i><input id="user_new_pass" type="password"><label for="user_new_pass">New Password</label></div></div><div class="row"><div class="input-field col s12"><i class="material-icons prefix">vpn_key</i><input id="user_confirm_new_pass" type="password"><label for="user_confirm_new_pass">Confirm New Password</label></div></div><div class="row" id="div_error_actual_pass"></div><div class="row" id="div_error_new_pass"></div><div class="row" id="div_error_confirm_new_pass"></div></div><div class="modal-footer end_button"><button class="modal-action modal-close waves-effect btn-flat left" id="cancel_info_user">Cancel</button><button class="waves-effect btn-flat right" id="apply_changing_pass">Apply Changing</button></div></div>');
					$("#modal_user_remove").html('<div id="modal_remove_user" class="modal bottom-fixed-footer"><div class="modal-content"><h1 class="title">To remove your account, write your password !!</h1><div class="row"><div class="input-field col s12"><i class="material-icons prefix">vpn_key</i><input id="user_confirm_remove" type="password"><label for="user_confirm_remove">Password</label></div></div><div class="row" id="div_error_remove_user"></div></div><div class="modal-footer end_button"><button class="modal-action modal-close waves-effect btn-flat left" id="cancel_info_user">Cancel</button><button class="waves-effect btn-flat right" id="apply_remove_user">Apply Changing</button></div></div>');
					$("#modal_tweet_remove").html('<div id="modal_remove_tweet" class="modal bottom-fixed-footer"><div class="modal-content"><h1 class="title">To remove this tweet, write your password !!</h1><div class="row"><div class="input-field col s12"><i class="material-icons prefix">vpn_key</i><input id="tweet_confirm_remove" type="password"><label for="tweet_confirm_remove">Password</label></div></div><div class="row" id="div_error_remove_tweet"></div></div><div class="modal-footer end_button"><button class="modal-action modal-close waves-effect btn-flat left" id="cancel_info_user">Cancel</button><button class="waves-effect btn-flat right" id="apply_remove_tweet">Apply Changing</button></div></div>');
					if (data.data.tweet_user.tweet_user / 3 > 1) {
						div_tweet_pagination = '<ul class="pagination center-align"><li><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
						for (i = 1; i <= Math.ceil(data.data.tweet_user.tweet_user / 3); i = i + 1) {
							div_tweet_pagination = div_tweet_pagination + '<li class="waves-effect"><button class="waves-effect btn-flat pagination" id="pagination_' + i + '">' + i + '</button></li>';
						}
						div_tweet_pagination = div_tweet_pagination + '<li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li></ul>';
						$('#tweet_pagination').html(div_tweet_pagination);
					} else {
						$('#tweet_pagination').html('');
					}
				} else {
					Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
				}
			} else {
				Materialize.toast('<p class="alert-failed">a problem occurred while getting your data in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
			}
		});
	}
	function get_user_tweet () {
		$.post(path_to_ajax, {action: 'get_user_tweet', id: $("#user_id").text(), page: 0}, function(data, textStatus) {
			if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						div_user_tweet = "";
						$.each(data.data, function(index, tweet_object) {
							div_user_tweet = div_user_tweet + '<div class="row center-align"><div class="col s12"><div class="card"><i class="material-icons right remove_tweet" id="remove_tweet_' + tweet_object.id + '">close</i><div class="card-content"><span class="card-title">' + tweet_object.lastname + ' ' + tweet_object.firstname + '</span><br/><span class="card-title">@' + tweet_object.login + '</span><p><span id="only_tweet_content_' + tweet_object.id + '">' + tweet_object.content + '</span><i class="material-icons change_tweet_content" id="change_tweet_content_' + tweet_object.id + '">mode_edit</i></p></div><div class="card-action">';
							if (tweet_object.favorite == 0) {
								div_user_tweet = div_user_tweet + '<button class="waves-effect btn-flat"><i class="material-icons not_favorite" id="favorite_' + tweet_object.id + '">star_border</i></button>';
							} else {
								div_user_tweet = div_user_tweet + '<button class="waves-effect btn-flat"><i class="material-icons favorite" id="favorite_' + tweet_object.id + '">star</i></button>';
							}
							if (tweet_object.love == 0) {
								div_user_tweet = div_user_tweet + '<button class="waves-effect btn-flat"><i class="material-icons not_love" id="love_' + tweet_object.id + '">favorite_border</i></button>';
							} else {
								div_user_tweet = div_user_tweet + '<button class="waves-effect btn-flat"><i class="material-icons love" id="love_' + tweet_object.id + '">favorite</i></button>';
							}
							div_user_tweet = div_user_tweet + '</div></div></div></div>';
						});
						$('#user_tweet').html(div_user_tweet);
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while we try to get all of your tweets in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
		});
	}
	function update_lastname_firstname () {
		error_change_lastname_firstname = "";
		$('#div_error_lastname_firstname').html('');
		$("#div_error_lastname_firstname").css('color', '#FF0000');
		$('#user_lastname_change').css('border-bottom', '1px solid #000000');
		$('#user_firstname_change').css('border-bottom', '1px solid #000000');
		if ($.trim($('#user_lastname_change').val()) === "") {
			error_change_lastname_firstname = error_change_lastname_firstname + '<p>Lastname can\'t be empty !!</p>';
			$('#user_lastname_change').css('border-bottom', '1px solid #FF0000');
		}
		if ($.trim($('#user_firstname_change').val()) === "") {
			error_change_lastname_firstname = error_change_lastname_firstname + '<p>Firstname can\'t be empty !!</p>';
			$('#user_firstname_change').css('border-bottom', '1px solid #FF0000');
		}
		$('#div_error_lastname_firstname').html(error_change_lastname_firstname);
		if (error_change_lastname_firstname === "") {
			$.post(path_to_ajax, {action: 'update_lastname_firstname', user_lastname: $.trim($('#user_lastname_change').val()), user_firstname: $.trim($('#user_firstname_change').val())}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						Materialize.toast('<p class="alert-success">Lastname and firstname updating successfully !!<p>', 3000, 'rounded alert-success');
						$('#modal_change_lastname_firstname').closeModal();
						get_user_info();
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while we sending the new lastname and firstname in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	}
	function update_login () {
		if ($.trim($('#div_error_login').html()) === "") {
			$.post(path_to_ajax, {action: 'update_login', user_login: $.trim($('#user_username_change').val())}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						Materialize.toast('<p class="alert-success">Login update successfully !!<p>', 3000, 'rounded alert-success');
						$('#modal_change_login').closeModal();
						get_user_info();
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while we send the new login in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	}
	function update_email () {
		if ($.trim($('#div_error_email').html()) === "") {
			$.post(path_to_ajax, {action: 'update_email', user_email: $.trim($('#user_email_change').val())}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						Materialize.toast('<p class="alert-success">Email update successfully !!<p>', 3000, 'rounded alert-success');
						$('#modal_change_email').closeModal();
						get_user_info();
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while we send the new email in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	}
	function update_pass () {
		if ($.trim($("#div_error_actual_pass").html()) === "" && $.trim($("#div_error_new_pass").html()) === "" && $.trim($("#div_error_confirm_new_pass").html()) === "") {
			$.post(path_to_ajax, {action: 'update_pass', user_actual_pass: $('#user_actual_pass').val(), user_new_pass: $("#user_new_pass").val(), user_confirm_new_pass: $("#user_confirm_new_pass").val()}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						Materialize.toast('<p class="alert-success">password update successfully !!<p>', 3000, 'rounded alert-success');
						$('#modal_change_pass').closeModal();
						get_user_info();
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while we send the new password in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	}
	function remove_user () {
		if ($.trim($("#div_error_remove_user").html()) === "") {
			$.post(path_to_ajax, {action: 'remove_account', user_pass_remove_account: $('#user_confirm_remove').val()}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						Materialize.toast('<p class="alert-success">Account removed !!<p>', 1500, 'rounded alert-success');
						$('#modal_change_pass').closeModal();
						get_user_info();
						setTimeout(function () {
							window.location = "?page=home";
						}, 1000);
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while we send the password in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	}
	function send_tweet () {
		if ($("#tweet_form").val().length <= 120) {
			$.post(path_to_ajax, {action: 'send_tweet', tweet: $("#tweet_form").val()}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						$("#tweet_form").val('');
						Materialize.toast('<p class="alert-success">Tweet added successfully !!<p>', 3000, 'rounded alert-success');
						get_user_info();
						get_user_tweet();
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while we send the tweet in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	}
	function remove_tweet () {
		if ($.trim($("#div_error_remove_tweet").html()) === "") {
			$.post(path_to_ajax, {action: 'remove_tweet', id_tweet: $('#tweet_id_to_remove').text(), user_pass_remove_tweet: $('#tweet_confirm_remove').val()}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						Materialize.toast('<p class="alert-success">Tweet removed successfully !!<p>', 3000, 'rounded alert-success');
						$("#modal_remove_tweet").closeModal();
						get_user_info();
						get_user_tweet();
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while we send the id tweet in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	}
	get_user_info();
	get_user_tweet();
	$(document).on('click', '.change_tweet_content', function() {
		if (Number.isInteger(parseInt($(this).attr('id').substring(21)))) {
			$('#tweet_id_to_change').html(parseInt($(this).attr('id').substring(21)));
			$.post(path_to_ajax, {action: 'get_tweet_by_id', id_tweet: parseInt($(this).attr('id').substring(21))}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						$('#tweet_change').val(data.data.content);
						$('#modal_change_tweet').openModal();
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while we send the id tweet in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	});
	$(document).on('keyup', '#tweet_change', function() {
		if ($(this).val().length > 120) {
			$(this).css('border-bottom', '2px solid #FF0000');
		} else {
			$(this).css('border-bottom', '2px solid #000000');
		}
	});
	$(document).on('click', '#apply_change_tweet', function() {
		if ($('#tweet_change').val().length < 120) {
			$.post(path_to_ajax, {action: 'change_tweet', tweet_to_change: $('#tweet_change').val(), tweet_id_to_change: $('#tweet_id_to_change').text()}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						Materialize.toast('<p class="alert-success">Tweet changed successfully !!<p>', 3000, 'rounded alert-success');
						$("#modal_change_tweet").closeModal();
						get_user_info();
						get_user_tweet();
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while we send the id tweet in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	});
	$(document).on('click', '.remove_tweet', function() {
		if (Number.isInteger(parseInt($(this).attr('id').substring(13)))) {
			$('#tweet_id_to_remove').html(parseInt($(this).attr('id').substring(13)));
			$('#modal_remove_tweet').openModal();
		}
	});
	$(document).on('keyup', '#tweet_confirm_remove', function() {
		error_remove_tweet = "";
		$("#div_error_remove_tweet").html('');
		$("#div_error_remove_tweet").css('color', 'red');;
		$(this).css('border-bottom', '1px solid #000000');
		if ($(this).val() === "") {
			error_remove_tweet = error_remove_tweet + "<p>Password empty !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else if ($(this).val().length < 5) {
			error_remove_tweet = error_remove_tweet + "<p>Password must be at least 5 characters !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else {
			$(this).css('border-bottom', '1px solid #008000');
		}
		$("#div_error_remove_tweet").html(error_remove_tweet);
	});
	press_enter("#tweet_confirm_remove", remove_tweet);
	$(document).on('click', '#apply_remove_tweet', function() {
		remove_tweet();
	});
	$(document).on('click', '#display_hide_form_tweet', function() {
		form_tweet_count_button = form_tweet_count_button + 1;
		if (form_tweet_count_button % 2 === 0) {
			$("#display_hide_form_tweet_icon").text('visibility');
			$('#form_tweet').css('display', 'inherit');
		} else {
			$('#form_tweet').css('display', 'none');
			$("#display_hide_form_tweet_icon").text('visibility_off');
		}
	});
	$(document).on('click', '#div_user_avatar', function() {
		$('#input_avatar').trigger('click');
	});
	$(document).on('change', '#input_avatar', function(event) {
		event.preventDefault();
		$("#form_avatar").submit();
	});
	$(document).on('submit', '#form_avatar', function(event) {
		event.preventDefault();
		form_data_avatar = new FormData();
    	form_data_avatar.append('file_avatar', $('#input_avatar').prop('files')[0]);
    	$.ajax({
    		url: path_to_ajax,
    		type: 'POST',
    		data: form_data_avatar,
    		async: false,
    		cache: false,
		    contentType: false,
		    processData: false,
		    success: function (data) {
		    	data = JSON.parse(data);
		    	if (data.error === null) {
		    		Materialize.toast('<p class="alert-success">Avatar changed successfully !!<p>', 3000, 'rounded alert-success');
		    		get_user_info();
		    	} else {
		    		Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
		    	}
		    },
		    error: function() {
		    	Materialize.toast('<p class="alert-failed">a problem occurred while we send the avatar file in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
		    }
		});
	});
	$(document).on('click', '#change_lastname_firstname', function() {
		$('#modal_change_lastname_firstname').openModal();
	});
	$(document).on('click', '#apply_changing_lastname_firstname', function() {
		update_lastname_firstname();
	});
	press_enter("#user_lastname_change", update_lastname_firstname);
	press_enter("#user_firstname_change", update_lastname_firstname);
	$(document).on('click', '#change_email', function() {
		$('#modal_change_email').openModal();
	});
	$(document).on('keyup', '#user_email_change', function() {
		error_change_email = "";
		$("#div_error_email").html('');
		$('#user_email_change').css('border-bottom', '1px solid #000000');
		$("#div_error_email").css('color', '#FF0000');
		$("#apply_changing_email").removeAttr('disabled');
		if ($.trim($(this).val()) === $("#only_user_email").text()) {
			$('#user_email_change').css('border-bottom', '1px solid #FF0000');
			error_change_email = error_change_email + '<p>You can\'t change your email by the same !!</p>';
			$("#div_error_email").css('color', '#FF0000');
			$("#apply_changing_email").attr('disabled', 'true');
		} else if ($.trim($(this).val()) === "") {
			$('#user_email_change').css('border-bottom', '1px solid #FF0000');
			error_change_email = error_change_email + '<p>Empty email !!</p>';
			$("#div_error_email").css('color', '#FF0000');
			$("#apply_changing_email").attr('disabled', 'true');
		}
		if ($.trim($(this).val()) !== "") {
			if ($.trim($(this).val()).split('@').length === 2) {
				if ($.trim($(this).val()).split('@')[0] !== "" && $.trim($(this).val()).split('@')[1] !== "") {
					if ($.trim($(this).val()).split('@')[1].split(".").length > 1) {
					} else {
						error_change_email = error_change_email + "<p>Invalid email !!</p>";
					}
				} else {
					error_change_email = error_change_email + "<p>Invalid email !!</p>";
				}
			} else {
				error_change_email = error_change_email + "<p>Invalid email !!</p>";
			}
		}
		$('#div_error_email').html(error_change_email);
		if (error_change_email === "") {
			$.post(path_to_ajax, {action: 'check_email_exist', email: $.trim($(this).val())}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						if (data.data.user === "free") {
							$('#user_email_change').css('border-bottom', '1px solid #008000');
							$("#div_error_email").html('<p>This email is free to use !!</p>');
							$("#div_error_email").css('color', '#008000');
							$("#apply_changing_email").removeAttr('disabled');
						} else {
							$('#user_email_change').css('border-bottom', '1px solid #FF0000');
							$("#div_error_email").html('<p>Email already taken !!</p>');
							$("#div_error_email").css('color', '#FF0000');
							$("#apply_changing_email").attr('disabled', 'true');
						}
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while checking if the email exist in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	});
	press_enter("#user_email_change", update_email);
	$(document).on('click', '#apply_changing_email', function() {
		update_email();
	});
	$(document).on('click', '#change_login', function() {
		$('#modal_change_login').openModal();
	});
	$(document).on('keyup', '#user_username_change', function() {
		error_change_login = "";
		$("#div_error_login").html('');
		$('#user_username_change').css('border-bottom', '1px solid #000000');
		$("#div_error_login").css('color', '#FF0000');
		$("#apply_changing_info_user").removeAttr('disabled');
		if ($.trim($(this).val()) === $("#only_user_login").text()) {
			$('#user_username_change').css('border-bottom', '1px solid #FF0000');
			error_change_login = error_change_login + '<p>You can\'t change your login by the same !!</p>';
			$("#div_error_login").css('color', '#FF0000');
			$("#apply_changing_info_user").attr('disabled', 'true');
		} else if ($.trim($(this).val()) === "") {
			$('#user_username_change').css('border-bottom', '1px solid #FF0000');
			error_change_login = error_change_login + '<p>Empty login !!</p>';
			$("#div_error_login").css('color', '#FF0000');
			$("#apply_changing_info_user").attr('disabled', 'true');
		}
		$('#div_error_login').html(error_change_login);
		if (error_change_login === "") {
			$.post(path_to_ajax, {action: 'check_login_exist', login: $.trim($(this).val())}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						if (data.data.user === "free") {
							$('#user_username_change').css('border-bottom', '1px solid #008000');
							$("#div_error_login").html('<p>This login is free to use !!</p>');
							$("#div_error_login").css('color', '#008000');
							$("#apply_changing_info_user").removeAttr('disabled');
						} else {
							$('#user_username_change').css('border-bottom', '1px solid #FF0000');
							$("#div_error_login").html('<p>Login already taken !!</p>');
							$("#div_error_login").css('color', '#FF0000');
							$("#apply_changing_info_user").attr('disabled', 'true');
						}
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while checking if the login exist in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	});
	press_enter("#user_username_change", update_login);
	$(document).on('click', '#apply_changing_login', function() {
		update_login();
	});
	$(document).on('click', '#change_pass', function() {
		$('#modal_change_pass').openModal();
	});
	$(document).on('keyup', '#user_actual_pass', function() {
		error_actual_pass = "";
		$("#div_error_actual_pass").html('');
		$("#div_error_actual_pass").css('color', 'red');;
		$(this).css('border-bottom', '1px solid #000000');
		if ($(this).val() === "") {
			error_actual_pass = error_actual_pass + "<p>Actual Password empty !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else if ($(this).val().length < 5) {
			error_actual_pass = error_actual_pass + "<p>Actual Password must be at least 5 characters !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else {
			$(this).css('border-bottom', '1px solid #008000');
		}
		$("#div_error_actual_pass").html(error_actual_pass);
	});
	$(document).on('keyup', '#user_new_pass', function() {
		error_new_pass = "";
		$("#div_error_new_pass").html('');
		$("#div_error_new_pass").css('color', 'red');;
		$(this).css('border-bottom', '1px solid #000000');
		if ($(this).val() === "") {
			error_new_pass = error_new_pass + "<p>New Password empty !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else if ($(this).val().length < 5) {
			error_new_pass = error_new_pass + "<p>New Password must be at least 5 characters !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else {
			$(this).css('border-bottom', '1px solid #008000');
		}
		$("#div_error_new_pass").html(error_new_pass);
	});
	$(document).on('keyup', '#user_confirm_new_pass', function() {
		error_confirm_new_pass = "";
		$("#div_error_confirm_new_pass").html('');
		$("#div_error_confirm_new_pass").css('color', 'red');;
		$(this).css('border-bottom', '1px solid #000000');
		if ($(this).val() === "") {
			error_confirm_new_pass = error_confirm_new_pass + "<p>Confirm pass empty !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else if ($(this).val().length < 5) {
			error_confirm_new_pass = error_confirm_new_pass + "<p>Confirm pass must be at least 5 characters !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else if ($(this).val() !== $("#user_new_pass").val()) {
			error_confirm_new_pass = error_confirm_new_pass + "<p>New pass and confirm pass are not the same !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else {
			$(this).css('border-bottom', '1px solid #008000');
		}
		$("#div_error_confirm_new_pass").html(error_confirm_new_pass);
	});
	press_enter("#user_actual_pass", update_pass);
	press_enter("#user_new_pass", update_pass);
	press_enter("#user_confirm_new_pass", update_pass);
	$(document).on('click', '#apply_changing_pass', function() {
		update_pass();
	});
	$(document).on('click', '#logout', function() {
		$.post(path_to_ajax, {action: 'logout', user_id: $('#user_id').text(), token: $(this).attr('token')}, function(data, textStatus) {
			if (textStatus === "success") {
				data = JSON.parse(data);
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
	$(document).on('click', '#remove_user', function() {
		$("#modal_remove_user").openModal();
	});
	$(document).on('keyup', '#user_confirm_remove', function() {
		error_remove_user = "";
		$("#div_error_remove_user").html('');
		$("#div_error_remove_user").css('color', 'red');;
		$(this).css('border-bottom', '1px solid #000000');
		if ($(this).val() === "") {
			error_remove_user = error_remove_user + "<p>Password empty !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else if ($(this).val().length < 5) {
			error_remove_user = error_remove_user + "<p>Password must be at least 5 characters !!</p>";
			$(this).css('border-bottom', '1px solid #FF0000');
		} else {
			$(this).css('border-bottom', '1px solid #008000');
		}
		$("#div_error_remove_user").html(error_remove_user);
	});
	press_enter("#user_confirm_remove", remove_user);
	$(document).on('click', '#apply_remove_user', function() {
		remove_user();
	});
	$(document).on('keyup', '#tweet_form', function() {
		if ($(this).val().length > 120) {
			$(this).css('border-bottom', '2px solid #FF0000');
		} else {
			$(this).css('border-bottom', '2px solid #000000');
		}
	});
	$(document).on('click', '#send_tweet', function() {
		send_tweet();
	});
});