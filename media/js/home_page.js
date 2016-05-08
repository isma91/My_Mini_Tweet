/*jslint browser: true, node : true*/
/*jslint devel : true*/
/*global $, document, this*/
$(document).ready(function(){
	var path_to_ajax, user_lastname, user_firstname, user_email, user_username, user_pass, user_confirm_pass, error_inscription, error_connexion;
	path_to_ajax = "public_api/index.php";
	function press_enter (selector, go_function) {
		$(document).on('keyup', selector, function(event) {
			if (event.keyCode === 13) {
				go_function();
			}
		});
	}
	function change_to_invalide (selector) {
		$(selector).css('border-bottom', '1px solid #FF0000');
	}
	function change_to_valide (selector) {
		$(selector).css('border-bottom', '1px solid #9e9e9e');
	}
	function inscription () {
		error_inscription = "";
		$("#div_error").html("");
		change_to_valide("#user_lastname");
		change_to_valide("#user_firstname");
		change_to_valide("#user_email");
		change_to_valide("#user_username");
		change_to_valide("#user_pass");
		change_to_valide("#user_confirm_pass");
		user_lastname = $.trim($('#user_lastname').val());
		user_firstname = $.trim($('#user_firstname').val());
		user_email = $.trim($('#user_email').val());
		user_username = $.trim($('#user_username').val());
		user_pass = $('#user_pass').val();
		user_confirm_pass = $('#user_confirm_pass').val();
		if (user_lastname === "") {
			change_to_invalide("#user_lastname");
			error_inscription = error_inscription + "<p>Lastname empty !!</p>";
		}
		if (user_firstname === "") {
			change_to_invalide("#user_firstname");
			error_inscription = error_inscription + "<p>Firstname empty !!</p>";
		}
		if (user_username === "") {
			change_to_invalide("#user_username");
			error_inscription = error_inscription + "<p>Username empty !!</p>";
		}
		if (user_email === "") {
			change_to_invalide("#user_email");
			error_inscription = error_inscription + "<p>Email empty !!</p>";
		}
		if (user_pass === "") {
			change_to_invalide("#user_pass");
			error_inscription = error_inscription + "<p>Pass empty !!</p>";
		}
		if (user_pass !== "" && user_pass.length < 5) {
			change_to_invalide("#user_pass");
			error_inscription = error_inscription + "<p>Your pass must be at least 5 characters !!</p>";
		}
		if (user_confirm_pass === "") {
			change_to_invalide("#user_confirm_pass");
			error_inscription = error_inscription + "<p>Confirm your pass again !!</p>";
		}
		if (user_email !== "") {
			if (user_email.split('@').length === 2) {
				if (user_email.split('@')[0] !== "" && user_email.split('@')[1] !== "") {
					if (user_email.split('@')[1].split(".").length > 1) {
					} else {
						error_inscription = error_inscription + "<p>Invalid email !!</p>";
						change_to_invalide("#user_email");
					}
				} else {
					error_inscription = error_inscription + "<p>Invalid email !!</p>";
					change_to_invalide("#user_email");
				}
			} else {
				error_inscription = error_inscription + "<p>Invalid email !!</p>";
				change_to_invalide("#user_email");
			}
		}
		if (user_confirm_pass !== user_pass) {
			error_inscription = error_inscription + "<p>You don't write the same pass twice !!</p>";
		}
		$("#div_error").html(error_inscription);
		if (error_inscription === "") {
			$.post(path_to_ajax, {action: 'inscription', user_lastname: user_lastname, user_firstname: user_firstname, user_email: user_email, user_username: user_username, user_pass: user_pass, user_confirm_pass: user_confirm_pass}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						Materialize.toast('<p class="alert-success">You successfully sign up !!<p>', 3000, 'rounded alert-success');
						if ($("#sign_in").attr('class') === undefined || $.trim($("#sign_in").attr('class')) === "") {
							$("#sign_in").children('div').trigger('click');
						}
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while sending your data in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	}
	$(document).on('click', '#inscription', function() {
		inscription();
	});
	press_enter("#user_lastname", inscription);
	press_enter("#user_firstname", inscription);
	press_enter("#user_username", inscription);
	press_enter("#user_email", inscription);
	press_enter("#user_pass", inscription);
	press_enter("#user_confirm_pass", inscription);
	function connexion () {
		error_connexion = "";
		$("#div_error").html('');
		change_to_valide('#user_login');
		change_to_valide('#user_pass_sign_in');
		user_username = $.trim($('#user_login').val());
		user_pass = $('#user_pass_sign_in').val();
		if (user_username === "") {
			change_to_invalide("#user_login");
			error_connexion = error_connexion + "<p>Login empty !!</p>";
		}
		if (user_pass === "") {
			change_to_invalide("#user_pass_sign_in");
			error_connexion = error_connexion + "<p>Pass empty !!</p>";
		}
		if (user_pass !== "" && user_pass.length < 5) {
			change_to_invalide("#user_pass_sign_in");
			error_connexion = error_connexion + "<p>Your pass must be at least 5 characters !!</p>";
		}
		$("#div_error").html(error_connexion);
		if (error_connexion === "") {
			$.post(path_to_ajax, {action: 'connexion', user_username: user_username, user_pass: user_pass}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					if (data.error === null) {
						Materialize.toast('<p class="alert-success">You sign in successfully !!<p>', 1500, 'rounded alert-success');
						setTimeout(function () {
							window.location = "?page=wall";
						}, 1000);
					} else {
						Materialize.toast('<p class="alert-failed">' + data.error + '<p>', 3000, 'rounded alert-failed');
					}
				} else {
					Materialize.toast('<p class="alert-failed">a problem occurred while sending your data in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	}
	$(document).on('click', '#connexion', function() {
		connexion();
	});
	press_enter("#user_login", connexion);
	press_enter("#user_pass_sign_in", connexion);
	function display_hide_mousedown_mouseup (selector_trigger, selector_input) {
		$(document).on('mousedown', selector_trigger, function() {
			$(selector_input).prop("type", "text");
		});
		$(document).on('mouseup', selector_trigger, function() {
			$(selector_input).prop("type", "password");
		});	
	}
	display_hide_mousedown_mouseup("#display_user_pass", "#user_pass");
	display_hide_mousedown_mouseup("#display_user_confirm_pass", "#user_confirm_pass");
	display_hide_mousedown_mouseup("#display_user_pass_sign_in", "#user_pass_sign_in");
});