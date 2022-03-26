/**
 *
 */
$(document).ready(function () {
	$(document).on("click", ".edit-payer",function () {
		let form_data = {
			payer_id: $(this).data("payer_id"),
			tour_id: $(this).data("tour_id"),
			ajax: 1
		};
		$.ajax({
			type: "get",
			data: form_data,
			url: base_url + "payer/edit",
			success: function (data) {
				show_popup("Edit Tour for Payer", data, "auto");
			}
		});


	});

	$(document).on("click", ".save-payer-edits",function () {
		document.forms[0].submit();
	});

	$(document).on("click", ".cancel-payer-edit",function () {
		$("#payer-editor-block").css("background-color", "#FFCCCC");
		let tour_id = $("#tour_id").val();
		let ok = "<a class='button delete' href='" + base_url + "tourist/view_all/" + tour_id + "' >Yes</a>";
		let text = "<p>Do you want to cancel? This will discard ONLY changes<br/> you made to the \"Ticket Details\" column on this page.</p>";
		let button_box = "<div class='button-box mini'><ul class='button-list'><li>" + ok + "</li></ul></div>";
		let message = text + button_box;

		show_popup("Discard Changes to Ticket Details?", message, "auto");
		$("#payer-editor-block").css("background-color", "#fff");

	});


	/*
	 * change the hidden value of the payment type based on changes to a dropdown
	 * in the payer editor.
	 */
	$(document).on("mouseup",".change_payment_type", function () {
		my_id = $("#tour_id").val();
		my_type = $(this).val();

		form_data = {
			tour_id: my_id,
			field: my_type,
			ajax: 1
		};
		$.ajax({
			type: "get",
			url: base_url + "tour/get_value",
			data: form_data,
			success: function (data) {
				$("#tour_price").val(data);
				$("#tour_price_display").html(data);
				total_cost = calculate_cost(1);
			}
		});

	});

	$(document).on("change", ".change_room_size", function () {
		my_id = $("#tour_id").val();
		amt_paid = $("#amt_paid").val().valueOf();
		my_type = $(this).val();
		my_format = "int";

		form_data = {
			tour_id: my_id,
			field: my_type,
			ajax: 1
		};
		$.ajax({
			type: "get",
			url: base_url + "tour/get_value",
			data: form_data,
			success: function (data) {
				$("#room_rate").val(data);
				$("#room_rate_display").html(data);
				total_cost = calculate_cost(1);
			}
		});

	});


	$(document).on("blur","input.edit-payer-amounts", function () {
		calculate_cost(1);
	});


	$(document).on('keyup', '#tourist-dropdown', function (event) {
		let person_search = this.value;
		$(this).delay(300);
		if (person_search.length > 4) {
			let search_words = person_search.split(' ');
			let my_url = $(this).data('url');
			let my_name = search_words.join('%') + "%";
			let my_tour = $(this).data("tour_id");
			let my_payer = $(this).data("payer_id");
			let form_data = {
				ajax: 1,
				name: my_name,
				tour_id: my_tour,
				payer_id: my_payer
			};
			$.ajax({
				url: my_url,
				type: 'GET',
				data: form_data,
				success: function (data) {
					//remove the search_list because we don't want to have a ton of them.
					if (data.length > 0) {
						$("#search_list").css({"z-index": 10000}).html(data).position({
							my: "left top",
							at: "left bottom",
							of: $("#tourist-dropdown"),
							collision: "fit"
						}).show();
					}
				}
			});
		} else {
			$("#search_list").hide();
			$("#search_list").css({"left": 0, "top": 0});


		}
	});// end person_search.keyup


	$(document).on('blur', '#tourist-dropdown',function (event) {
		//$("#search_list").fadeOut();
	});


	/*
	 * "target" in this scriptlet identifies the format of the output from tourist/insert
	 * in this case payer returns a list of tourists for a given payer.
	 */

	$(document).on("click", ".select_for_tour", function () {
		let my_person = $(this).data("person_id");
		let my_payer =  $(this).data("payer_id");
		let my_tour =  $(this).data("tour_id");
		$("#search_list").hide();

		let form_data = {
			person_id: my_person,
			payer_id: my_payer,
			tour_id: my_tour,
			target: "payer",
			ajax: 1
		};
		$.ajax({
			type: "post",
			url: base_url + "tourist/insert",
			data: form_data,
			success: function (data) {
				$("#payer-tourist-list").html(data);
				tourist_count = $("#payer-tourist-list tr").length;
				$("#tourist_count").val(tourist_count);
				calculate_cost(1);
			}

		});
	});

	/*
	 * target in this scriptlet identifies the format of the output from tourist/insert
	 * in this case "tour" will return a confirmation with an option to
	 */

	$(document).on("click", ".select-payer", function (e) {
		e.preventDefault();
		let tour_id = $(this).data('tour_id');
		let tourist_id = $(this).data('tourist_id');
		let payer_id = $(this).data('payer_id');
		let url = $(this).attr('href');
		let form_data = {
			person_id: tourist_id,
			payer_id: payer_id,
			tour_id: tour_id,
			target: "tour",
			ajax: 1
		};
		$.ajax({
			type: "post",
			url: url,
			data: form_data,
			success: function (data) {
				window.location.href = base_url + "tourist/view_all/" + tour_id;
			}
		});
	});


	$(document).on("click", ".delete-tourist", function () {
		let question = confirm("Are you sure you want to remove this person from this list? This cannot be undone");
		if (question) {
			let my_person = $(this).data('person_id');
			let my_tour = $(this).data('tour_id');
			let my_payer = $(this).data('payer_id');
			let form_data = {
				person_id: my_person,
				tour_id: my_tour,
				payer_id: my_payer,
				ajax: 1
			};
			$.ajax({
				type: "post",
				url: base_url + "tourist/delete",
				data: form_data,
				success: function (data) {
					$("#payer-tourist-list").html(data);
					tourist_count = $("#payer-tourist-list tr").length;
					$("#tourist_count").val(tourist_count);
					calculate_cost(1);
				}
			});
		}
	});

	$(".select-tourist-type").on("click", function () {
		my_id = this.id.split("_")[1];
		form_data = {
			id: my_id,
			ajax: 1
		};
		$.ajax({
			type: "get",
			url: base_url + "tourist/select_tourist_type",
			data: form_data,
			success: function (data) {
				show_popup("Select Tourist Type", data, "auto");
			}
		});
	});

	$(".select-tour").on("click", function (e) {
		e.preventDefault();
		let my_url = $(this).attr('href');
		let person_id = $(this).data('person_id')
		let form_data = {
			ajax: "1",
			id: person_id
		};
		$.ajax({
			type: "get",
			url: my_url,
			data: form_data,
			success: function (data) {
				show_popup("Select a Tour", data, "auto");
			}
		});
	});


	$("body").on("click", ".select-as-tourist", function (e) {
		e.preventDefault();
		let my_tour = $(this).data('tour_id');
		let my_person = $(this).data('person_id');
		let my_url = $(this).attr('href');
		let form_data = {
			tour_id: my_tour,
			person_id: my_person,
			ajax: "1"
		};
		$.ajax({
			type: "get",
			url: my_url,
			data: form_data,
			success: function (data) {
				$("#popup").html(data);
			}
		});
	});

	$(document).on("click", ".select-as-payer", function (e) {
		e.preventDefault();
		let my_tour = $(this).data('tour_id');
		let my_person = $(this).data('person_id');
		let my_url = $(this).attr('href');
		let form_data = {
			ajax: 1,
			tour_id: my_tour,
			payer_id: my_person,
		};
		$.ajax({
			type: "post",
			url: my_url,
			data: form_data,
			success: function (data) {
				document.location.href = base_url + "payer/edit/?payer_id=" + my_person + "&tour_id=" + my_tour;
			}
		});
	});

	$(document).on("click",".delete-payer", function () {
		request = confirm("Only delete a payer if they have been added to the tour by mistake. Check 'Cancelled' at the top of the record if they have dropped out.");
		if (request) {
			plea = confirm("Are you really sure? This will remove this payer, all their accompanying tourists, and any roommmate records for the tour they may have. Continue?");
			if (plea) {
				let my_payer = $(this).data("payer_id");
				let my_tour = $(this).data("tour_id");
				form_data = {
					tour_id: my_tour,
					payer_id: my_payer,
					ajax: 1
				};
				$.ajax({
					type: "post",
					url: base_url + "payer/delete",
					data: form_data,
					success: function () {
					}
				});
				window.location.href = base_url + "tourist/view_all/" + my_tour;

			}

		}
	});

	$(document).on("click", ".create-new-tourist", function () {
		tourist_name = $("#tourist-dropdown").val();
		first_name = tourist_name.split(" ")[0];
		last_name = tourist_name.split(" ")[1];
		form_data = {
			ajax: '1'
		};
		$.ajax({
			type: "get",
			url: base_url + "tourist/create",
			data: form_data,
			success: function (data) {
				$("#add-new-tourist").html(data);
				$(".create-new-tourist").fadeOut();
				$("#tourist-mini-selector").fadeOut();
				$("#first_name").val(first_name);
				$("#last_name").val(last_name);
			}
		});
	});

	$(document).on("click", ".insert-new-tourist", function () {
		let my_tour = $(this).data("tour_id");
		let my_payer = $(this).data("payer_id");
		let form_data = {
			tour_id: my_tour,
			payer_id: my_payer,
			first_name: $("#first_name").val(),
			last_name: $("#last_name").val(),
			shirt_size: $("#shirt_size").val(),
			ajax: '1'
		};
		$.ajax({
			type: "post",
			data: form_data,
			url: base_url + "tourist/insert_new",
			success: function (data) {
				$(".create-new-tourist").fadeOut();
				$("#add-new-tourist").children().remove();
				$("#payer-tourist-list").html(data);
				tourist_count = $("#payer-tourist-list tr").length;
				$("#tourist_count").val(tourist_count);
				calculate_cost(1);
				$(".create-new-tourist").fadeIn();
				$("#tourist-mini-selector").fadeIn();
				$("#tourist-dropdown").val("");
			}

		});


	});

	$(document).on("click", ".select-letter", function (e) {
		e.preventDefault();
		let form_data = {
			ajax: 1
		};
		$.ajax({
			type: "get",
			url: $(this).attr("href"),
			data: form_data,
			success: function (data) {
				show_popup("Select Letter", data, "auto");
			}
		});

	});


	$(document).on("click", ".insert-merge-note",function () {
		$(this).fadeOut();
		$.ajax({
			type: "get",
			url: base_url + "merge/create_note",
			success: function (data) {
				$("#merge-note").html(data);
			}
		});
	});

	$(document).on("click", ".edit-merge-note", function () {
		$(this).fadeOut();
		let my_id = $("#id").val();
		let form_data = {
			id: my_id,
			field: "note",
			ajax: 1
		};
		$.ajax({
			type: "get",
			url: base_url + "merge/get_note",
			data: form_data,
			success: function (data) {
				$("#merge-note").html(data);
			}
		});
	});


});//end document load


function calculate_cost(include_amt) {
	let tourist_count = $("#tourist_count").val().valueOf();
	let amt_paid = 0;
	if (include_amt) {
		amt_paid = $("#amt_paid").val().valueOf();

	}
	if (amt_paid.length == 0) {
		amt_paid = 0;
	}
	let discount = $("#discount").val().valueOf();
	if (discount.length == 0) {
		discount = 0;
	}
	let room_rate = $("#room_rate").val().valueOf();
	if (room_rate.length == 0) {
		room_rate = 0;
	}

	let tour_price = $("#tour_price").val().valueOf();
	$("#total_cost").html((parseInt(tourist_count) * (parseInt(tour_price)) + parseInt(room_rate) - parseInt(discount)));
	if (include_amt) {
		$("#amt_due").html(
			(parseInt(tourist_count) * (parseInt(tour_price))) - amt_paid - parseInt(discount) + parseInt(room_rate)
		);
	}
}
