/**
 *
 */
document.addEventListener('DOMContentLoaded', function () {
	// Event delegation for .edit-payer click
	document.addEventListener('click', function (event) {

			if (event.target.classList.contains('delete-tourist')) {
				let question = confirm('Are you sure you want to remove this person from this list? This cannot be undone');
				if (question) {
					const tourId = event.target.getAttribute('data-tour_id');
					const payerId = event.target.getAttribute('data-payer_id');
					const personId = event.target.getAttribute('data-person_id');
					const targetId = event.target.getAttribute('data-target');
					const targetElement = document.querySelector(targetId);

					const form_data = {
						person_id: personId,
						tour_id: tourId,
						payer_id: payerId,
						ajax: 1,
					};
					fetch(base_url + 'tourist/delete', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json',  // Set Content-Type header
							},
							body: JSON.stringify(form_data),
						},
					)
						.then(response => response.json())
						.then(data => {
							targetElement.innerHTML = data['data'];
							const updatedTourists = targetElement.querySelectorAll('li');
							document.getElementById('tourist_count').value = updatedTourists.length;
						});
				}
			}
			if (event.target.classList.contains('update-cost-display')) {
				event.preventDefault();
				// reload the window
				window.location.reload();
			}

			if (event.target.classList.contains('save-payer-edits')) {
				document.forms[0].submit();
			}

			if (event.target.classList.contains('insert-tourist')) {
				event.preventDefault();

				let form_data = {
					tour_id: event.target.dataset.tour_id,
					payer_id: event.target.dataset.payer_id,
					tourist_id: event.target.dataset.tourist_id,
				};

				fetch(event.target.href + '?ajax=1', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',  // Set Content-Type header
						},
						body: JSON.stringify(form_data),
					},
				)
					.then(response => response.json())
					.then(data => {
						window.location.href = data.target;
					});
			}
		},
	)
	;
	document.addEventListener('change', function (event) {
		if (event.target.classList.contains('change_payment_type') || event.target.classList.contains('change_room_size')) {
			const targetElement = event.target.getAttribute('data-target');
			console.log('targetElement', targetElement);
			const tour_id = event.target.getAttribute('data-tour-id');
			const field = event.target.value;
			fetchTourValue(tour_id, field, targetElement);
		}
		if (event.target.name === 'is_cancelled') {
			if (typeof event.target === 'object') {
				if (event.target.checked === true) {
					const question = confirm('This will delete all the roommate information for this ticket. This CANNOT be undone! Continue?');
					if (!question) {
						return;
					}
				}
				const url = event.target.dataset.url + '?ajax=1';

				const form_data = new FormData();
				form_data.append('field', event.target.name);
				form_data.append('value', event.target.checked ? 1 : 0);
				// Send the form data to the server
				fetch(url, {
					method: 'POST',
					body: form_data,
				})
					.then(response => response.text())
					.then(data => {
						console.log(data);
					});

			}
		}
	});

});

function fetchTourValue(tour_id, field, targetElement) {
	const form_data = {
		tour_id: tour_id,
		field: field,
		ajax: 1,
	};
	let query = new URLSearchParams(form_data).toString();
	fetch(base_url + 'tours/get_value?' + query)
		.then(response => response.json())
		.then(data => {
			document.getElementById(targetElement).innerHTML = data;
		});
}

$(document).ready(function () {

	/*
	 * target in this scriptlet identifies the format of the output from tourist/insert
	 * in this case "tour" will return a confirmation with an option to
	 */

	$(document).on('click', '.select-payer', function (e) {
		e.preventDefault();
		let tour_id = $(this).data('tour_id');
		let tourist_id = $(this).data('tourist_id');
		let payer_id = $(this).data('payer_id');
		let url = $(this).attr('href');
		let form_data = {
			person_id: tourist_id,
			payer_id: payer_id,
			tour_id: tour_id,
			target: 'tour',
			ajax: 1,
		};
		$.ajax({
			type: 'post',
			url: url,
			data: form_data,
			success: function () {
				window.location.href = base_url + 'tourist/view_all/' + tour_id;
			},
		});
	});

	$('.select-tourist-type').on('click', function () {
		my_id = this.id.split('_')[1];
		form_data = {
			id: my_id,
			ajax: 1,
		};
		$.ajax({
			type: 'get',
			url: base_url + 'tourist/select_tourist_type',
			data: form_data,
			success: function (data) {
				show_popup('Select Tourist Type', data, 'auto');
			},
		});
	});

	$('.select-tour').on('click', function (e) {
		e.preventDefault();
		let my_url = $(this).attr('href');
		let person_id = $(this).data('person_id');
		let form_data = {
			ajax: '1',
			id: person_id,
		};
		$.ajax({
			type: 'get',
			url: my_url,
			data: form_data,
			success: function (data) {
				show_popup('Select a Tour', data, 'auto');
			},
		});
	});

	$('body').on('click', '.select-as-tourist', function (e) {
		e.preventDefault();
		let my_tour = $(this).data('tour_id');
		let my_person = $(this).data('person_id');
		let my_url = $(this).attr('href');
		let form_data = {
			tour_id: my_tour,
			person_id: my_person,
			ajax: '1',
		};
		$.ajax({
			type: 'get',
			url: my_url,
			data: form_data,
			success: function (data) {
				show_popup('Who is Paying?', data, 'auto');
			},
		});
	});

	$(document).on('click', '.select-as-payer', function (e) {
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
			type: 'post',
			url: my_url,
			data: form_data,
			success: function () {
				document.location.href = base_url + 'payer/edit/?payer_id=' + my_person + '&tour_id=' + my_tour;
			},
		});
	});

	$(document).on('click', '.create-new-tourist', function () {
		let tourist_name = $(this).data('name');
		let first_name = tourist_name.split(' ')[0];
		let last_name = tourist_name.split(' ')[1];
		let tour_id = $(this).data('tour_id');
		let payer_id = $(this).data('payer_id');

		let form_data = {
			ajax: '1',
			last_name: last_name,
			first_name: first_name,
			tour_id: tour_id,
			payer_id: payer_id,
		};
		$.ajax({
			type: 'get',
			url: base_url + 'tourist/create',
			data: form_data,
			success: function (data) {
				$('#add-new-tourist').html(data);
				$('.create-new-tourist').fadeOut();
				$('#tourist-mini-selector').fadeOut();
				$('#first_name').val(first_name);
				$('#last_name').val(last_name);
			},
		});
	});

	$(document).on('click', '.insert-new-tourist', function () {
		let my_tour = $(this).data('tour_id');
		let my_payer = $(this).data('payer_id');
		let form_data = {
			tour_id: my_tour,
			payer_id: my_payer,
			first_name: $('#first_name').val(),
			last_name: $('#last_name').val(),
			shirt_size: $('#shirt_size').val(),
			ajax: '1',
		};
		$.ajax({
			type: 'post',
			data: form_data,
			url: base_url + 'tourist/insert_new',
			success: function (data) {
				const newTourist = $('.create-new-tourist');
				// Fade out the new t
				newTourist.fadeOut();
				$('#add-new-tourist').children().remove();
				$('#payer-tourist-list').html(data);
				const tourist_count = $('#payer-tourist-list tr').length;
				$('#tourist_count').val(tourist_count);
				calculate_cost(1);
				newTourist.fadeIn();
				$('#tourist-mini-selector').fadeIn();
				$('#tourist-dropdown').val('');
			},

		});

	});

	$(document).on('click', '.select-letter', function (e) {
		e.preventDefault();
		let form_data = {
			ajax: 1,
		};
		$.ajax({
			type: 'get',
			url: $(this).attr('href'),
			data: form_data,
			success: function (data) {
				show_popup('Select Letter', data, 'auto');
			},
		});

	});

});//end document load

function calculate_cost(include_amt) {
	let tourist_count = document.getElementById('tourist_count').value;
	let amt_paid = 0;
	if (include_amt) {
		amt_paid = document.getElementById('amount_paid').dataset.value;
	}
	if (amt_paid.length === 0) {
		amt_paid = 0;
	}
	let discount = document.getElementById('discount').value;
	if (discount.length === 0) {
		discount = 0;
	}
	let surcharge = document.getElementById('surcharge').value;
	if (surcharge.length === 0) {
		surcharge = 0;
	}
	let room_rate = document.getElementById('room_size').dataset.value;
	if (room_rate.length === 0) {
		room_rate = 0;
	}

	let tour_price = document.getElementById('tour_price_display').innerHTML;
	const total_cost = document.getElementById('total_cost');
	total_cost.innerHTML = ((parseInt(tourist_count) * (parseFloat(tour_price)) + parseFloat(room_rate) - parseFloat(discount))).toString();
	if (include_amt) {
		const amt_due = document.getElementById('amt_due');
		amt_due.innerHTML = (
			(parseInt(tourist_count) * (parseInt(tour_price))) - amt_paid - parseInt(discount) + parseInt(room_rate) + parseInt(surcharge)
		).toString();
	}
}
