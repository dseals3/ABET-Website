/*
   Damian Seals
   COSC 465/565
   Web Project #4
   password.js
   */

$(document).ready(function() {
	$("#submitBtn").click(function() {
		let passOne = $("#passOne").val().trim();
		let passTwo = $("#passTwo").val().trim();
		let email = $("#getEmail").text();
		console.log(email);

		console.log(passOne + " " + passTwo);

		if (passOne === "" && passTwo === "") {
			$("#emptyError").removeClass("hidden");
			$("#success").addClass("hidden");
			$("#error").addClass("hidden");
		} else if (passOne === passTwo) {
			$("#emptyError").addClass("hidden");
			$("#error").addClass("hidden");
			$.ajax({
				type: 'post',
				url: 'updatePassword.php',
				data: {
					'email': email,
					'password': passOne
				},
				success: function(response) {
					console.log(response);
					$("#success").removeClass("hidden");
				}
			});
		} else {
			console.log("nope");
			$("#success").addClass("hidden");
			$("#emptyError").addClass("hidden");
			$("#error").removeClass("hidden");
		}
	});
});
