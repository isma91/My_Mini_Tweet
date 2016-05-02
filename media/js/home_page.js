/*jslint browser: true, node : true*/
/*jslint devel : true*/
/*global $, document, this*/
$(document).ready(function(){
	var path_to_ajax, user_lastname, user_firstname, user_email, user_username, user_pass, user_confirm_pass, error_inscription, error_connexion;
	path_to_ajax = "public_api/index.php";
	function change_to_invalide (selector) {
		$(selector).css('border-bottom', '1px solid #FF0000');
	}
	function change_to_valide (selector) {
		$(selector).css('border-bottom', '1px solid #9e9e9e');
	}
	$(document).on('click', '#inscription', function() {
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
					if (user_email.split('@')[1].split(".").length > 0) {
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
			$.post(path_to_ajax, {action: 'inscription', user_lastname: user_lastname, user_firstname: user_firstname, user_email: user_email, user_username: user_username, user_pass: user_pass}, function(data, textStatus) {
				if (textStatus === "success") {
					data = JSON.parse(data);
					console.log(data);
				} else {
					Materialize.toast('<p class="alert-failed">Something failed in the server !! Please contact the admin of the site !!<p>', 3000, 'rounded alert-failed');
				}
			});
		}
	});
});