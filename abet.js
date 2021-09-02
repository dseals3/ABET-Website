/*
   Damian Seals
   COSC 465/565
   Web Project #4
   abet.js
   */
let generateOutcomeDesc = function() {
	let id = $("#outcomes-list").find("li.selected").data("id");
	let desc = $("#outcomes-list").find("li.selected").data("desc");
	let maj = $("#outcomes-list").find("li.selected").data("major");

	console.log(id);
	let sel = JSON.parse($("#sectionMenu").find(":selected").val());
	console.log(sel);

	$.ajax({
		url: 'main.php',
		type: 'post',
		data: {
			'major': sel.maj,
			'sectionId': sel.sec,
			'outcomeId': id
		},
		success: function(data) {
			$("#mainGoHere").html(data);
			$("#outcome").html("<strong>Outcome " + id + " - " + maj + ":</strong> " + desc);
			$("#sectionMenu").change(blah);
		}
	});
}

let blah = function() {
	let sel = JSON.parse($("#sectionMenu").find(":selected").val());
	console.log(sel);
	$.ajax({
		url: 'nav.php',
		type: 'post',
		data: {
			'major': sel.maj,
			'sectionId': sel.sec
		},
		success: function(data) {
			$("#navGoHere").html(data);
			$("#sectionMenu").change(blah);
			$("#outcomes-list li")[0].click();
		}
	});
}
window.onload = function() {
	generateOutcomeDesc();
};

$(document).ready(function() {
	$("#sectionMenu").change(blah);

	let element = $("#sectionMenu")[0];
	console.log(element);
	if ("createEvent" in document) {
		var evt = document.createEvent("HTMLEvents");
		evt.initEvent("change", false, true);
		element.dispatchEvent(evt);
	}
	else {
		element.fireEvent("onchange");
		el.fireEvent("onclick");
	}

	/*************    RESULTS   *************************/
	$("main").on("click", "#resultsBtn", function() {
		let id = $("#outcomes-list").find("li.selected").data("id");
		let sel = JSON.parse($("#sectionMenu").find(":selected").val());

		let num = [];
		num[1] = parseInt($("#resOne").val());
		num[2] = parseInt($("#resTwo").val());
		num[3] = parseInt($("#resThree").val());

		for (i = 1; i < 4; i++) {
			$.ajax({
				url: 'updateResults.php',
				type: 'post',
				data: {
					'major': sel.maj,
					'sectionId': sel.sec,
					'outcomeId': id,
					'performanceLevel': i,
					'numberOfStudents': num[i]
				},
				success: function(data) {
				}
			});
		}
		let pop = $("#results-popup");
		pop.slideDown(800);
		pop.delay(1800);
		pop.fadeOut(800);

		$("#total-lbl").text(num[1] + num[2] + num[3]);

	});
	/*************    ADD NEW ASSESSMENT ROWS   *************************/
	$("main").on("click", "#newBtn", function() {
		$("#assess-table").each(function() {
			let row = "<tr>";
			row += "<td><input type='number' min=1 max=100></td>";
			row += "<td><textarea maxlength=400></textarea></td>";
			row += "<td><img class='trash' src='trash.svg'></td>";
			row += "</tr>";

			if ($("tbody", this).length > 0) {
				$("tbody", this).append(row);
			} else {
				$(this).append(row);
			}
		});
	});

	/*************    DELETE ASSESSMENT ROWS   *************************/
	$("main").on("click", ".trash", function() {
		$(this).closest("tr").remove();
		let assessId = $(this).closest("tr").data("assess-id");
		console.log(assessId);
		$.ajax({
			url: 'deleteAssessment.php',
			type: 'post',
			data: {
				'assessmentId': assessId
			},
			success: function(data) {
				console.log("deleted");
			}
		});

	});

	/*************    ASSESSMENTS   *************************/
	$("main").on("click", "#assessBtn", function() {
		let id = $("#outcomes-list").find("li.selected").data("id");
		let sel = JSON.parse($("#sectionMenu").find(":selected").val());

		let desc = $("#assess-table tr").find("textarea").val();

		if (desc) {
		$("#assess-table tr").each(function() {
			let me = $(this);
			let assessId = $(this).data("assessid");
			let weight = $(this).find("input").val();
			let desc = $(this).find("textarea").val();
			console.log(desc);
			console.log(weight);
			console.log(assessId);
				$.ajax({
					url: 'updateAssessment.php',
					type: 'post',
					data: {
						'major': sel.maj,
						'sectionId': sel.sec,
						'outcomeId': id,
						'assessmentDescription': desc,
						'assessmentId': assessId,
						'weight': weight
					},
					success: function(data) {
						console.log("saved");
					}
				});
				});
			let pop = $("#assess-popup");
				pop.slideDown(800);
				pop.delay(1800);
				pop.fadeOut(800);
			} else {
				let pop = $("#error-empty");
				pop.slideDown(800);
				pop.delay(1800);
				pop.fadeOut(800);
				return false;

			}
});
	/*************    NARRATIVES   *************************/
	$("main").on("click", "#narBtn", function() {
		let id = $("#outcomes-list").find("li.selected").data("id");
		let sel = JSON.parse($("#sectionMenu").find(":selected").val());
		let strengths = $("#strength").val();
		let weaknesses = $("#weak").val();
		let actions = $("#action").val();

		$.ajax({
			url: 'updateNarrative.php',
			type: 'post',
			data: {
				'major': sel.maj,
				'sectionId': sel.sec,
				'outcomeId': id,
				'strengths': strengths,
				'weaknesses': weaknesses,
				'actions': actions
			},
			success: function(data) {
				let pop = $("#nar-popup");
				pop.slideDown(800);
				pop.delay(1800);
				pop.fadeOut(800);
			}
		});
	});

	/*************    LOGOUT   *************************/
	$("#logout").click(function() {
		$.ajax({
			url: 'logout.php',
			type: 'post',
			success: function(data) {
				console.log(data);
				window.location.assign("login.html");
			}
		});
	});

	/*************    PASSWORD   *************************/
	$("#passwordPage").click(function() {
		$.ajax({
			url: 'password.php',
			type: 'post',
			success: function(data) {
				$("#mainGoHere").html(data);
			}
		});
	});

	$(document).on("click", "#outcomes-list li", function(e) {
		let selection = $(this).val();
		$("#outcomes-list").find("li.selected").removeClass("selected");
		$(this).addClass("selected");

		generateOutcomeDesc();
	});
});


