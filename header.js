/*
   Damian Seals
   COSC 465/565
   Web Project #4
   header.js
   */

function dropdown() {
	$("#list-container").toggleClass("show");
}

window.onclick = function(event) {
	if (!event.target.matches('#profile-span') && !event.target.matches('#profile-span img')) {
		var $dropdowns = $("#list-container");
		var i;
		for (i = 0; i < $dropdowns.length; i++) {
			var $openDropdown = $dropdowns[i];
			if ($openDropdown.classList.contains('show')) {
				$openDropdown.classList.remove('show');
			}
		}
	}
}


