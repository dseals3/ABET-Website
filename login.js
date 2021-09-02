/*
   Damian Seals
   COSC 465/565
   Web Project #4
   login.js
   */
$(document).ready(function() {
	$("#submitBtn").click(function() {
		let email = $("#email").val().trim();
		let pass = $("#password").val().trim();

		console.log(email + " " + pass);

		$.ajax({
			type: 'post',
			url: 'login.php',
			data: {
				'email': email,
				'password': pass
			},
			success: function(response) {
				console.log(response);
				loginResults = JSON.parse(response);
				//console.log(loginResults[0].sectionId + " " + loginResults[0].major);
				if (response != 0) {
					$('#invalid').addClass("hidden");
					//window.location.assign("abet.php");
					console.log("would have jumped");
					$.ajax({
						type: 'post',
						url: 'nav.php',
						data: {
							'sectionId': loginResults[0].sectionId,
							'major': loginResults[0].major
						},
						success: function(data) {
							console.log(data);
							window.location.href = "abet.php";
						}
					});
				} else {
					$('#invalid').removeClass("hidden");
				}
			}
		});
	});
});
