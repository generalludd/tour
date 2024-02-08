document.addEventListener('DOMContentLoaded', function () {

	// Add a click event listener to the #content of the page.
	document.getElementById('content').addEventListener('click', function (event) {
		event.preventDefault();
		if (event.target.classList.contains('add-roommate')) {
			const button = event.target;
			const myRoom = button.dataset.room_id;
			const myUrl = button.getAttribute('href');
			fetch(myUrl, {
					method: 'GET',
				},
			)
				.then(response => {

					return response.json();
				})
				.then(data => {
					const room = document.getElementById('room_' + myRoom);
					// append a row to the room's table element
					const newRow = document.createElement('tr');
					const newCell = document.createElement('td');
					newCell.classList.add('new-row');
					const newSelect = document.createElement('select');
					newSelect.setAttribute('name', 'person_id');
					const options = data.roomless;
					for (const key in options) {
						const option = document.createElement('option');
						option.setAttribute('value', key);
						option.textContent = options[key];
						newSelect.appendChild(option);
					}
					newCell.appendChild(newSelect);
					// create a link to the data.url
					const newLink = document.createElement('a');
					newLink.setAttribute('href', data.url);
					newLink.textContent = 'Add Placeholder';
					newLink.setAttribute('data-room_id', myRoom);
					newLink.classList.add('add-placeholder', 'link','add');
					newCell.appendChild(newLink);
					newRow.appendChild(newCell);
					room.querySelector('tbody').appendChild(newRow);
				})
				.catch(error => {
					console.error('Fetch error:', error);
				});
		}

		if (event.target.classList.contains('add-placeholder')) {
			const button = event.target;
			const myUrl = button.getAttribute('href');
			const roomId = button.dataset.room_id;
			fetch(myUrl, {
					method: 'GET',
				},
			)
				.then(response => {
					return response.json();
				})
				.then(data => {
					/// Get the parent class of button
					const parent = button.parentElement;
					// remove the button
					parent.removeChild(button);
					// create a new input element from data.input
					const newInput = document.createElement('input');
					newInput.setAttribute('type', 'text');
					newInput.setAttribute('name', 'placeholder');
					newInput.setAttribute('placeholder', data.placeholder);
					newInput.classList.add('insert-placeholder');
					newInput.setAttribute('data-room_id', roomId);
					newInput.setAttribute('data-person_id', data.person_id);
					newInput.setAttribute('data-tour_id', data.tour_id);
					newInput.setAttribute('data-stay', data.stay);
					newInput.setAttribute('data-href', data.url);
					parent.appendChild(newInput);
					// Process your data here
				})
				.catch(error => {
					console.error('Fetch error:', error);
				});
		}
	});

	// add a blur listener to the #content of the page
	document.getElementById('content').addEventListener('focusout', function (event) {

		if (event.target.classList.contains('insert-placeholder')) {
			const input = event.target;
			const formData = {
				room_id: input.dataset.room_id,
				tour_id: input.dataset.tour_id,
				stay: input.dataset.stay,
				person_id: input.dataset.person_id,
				placeholder: input.value,
			};
			const myUrl = input.dataset.href + '?ajax=1';
			fetch(myUrl, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',  // Set Content-Type header
					},
					body: JSON.stringify(formData),
				},
			)
				.then(response => response.json())
				.then(data => {
					// remove the input
					// Find the parent by the data-room_id attribute;
					const row = document.querySelector('table[data-room_id="' + data.room_id + '"] tr:last-child');
					console.log(row, 'row');
					// find the last cell in the row
					const cell = row.querySelector('td:last-child');
					// remove the cell element from the cell
					row.removeChild(cell);
					// create a new cell
					const newCell = document.createElement('td');
					newCell.textContent = data.placeholder;
					// // Add a delete button with the attributes of room_id and person_id
					const deleteButton = document.createElement('button');
					deleteButton.textContent = 'Delete';
					deleteButton.classList.add('delete-roommate','button','delete');
					deleteButton.setAttribute('data-room_id', data.room_id);
					deleteButton.setAttribute('data-person_id', data.person_id);
					// add a cell for the delete button
					const buttonCell = document.createElement('td');

					buttonCell.appendChild(deleteButton);
					// // append the new cell to the row
					row.appendChild(newCell);
					row.appendChild(buttonCell);
				})
				.catch(error => {
					console.error('Fetch error:', error);
				});
		}
	});

});
$(document).ready(function () {

	// $(document).on('blur', '.insert-placeholder', function (e) {
	// 	e.preventDefault();
	// 	let me = this;
	// 	let my_room = $(me).parents('.room-row').attr('id').split('_')[1];
	// 	let form_data = {
	// 		room_id: my_room,
	// 		tour_id: $(this).data('tour_id'),
	// 		stay: $(this).data('stay'),
	// 		person_id: $(this).data('person_id'),
	// 		placeholder: $(me).val(),
	// 		ajax: 1,
	// 	};
	// 	$.ajax({
	// 		type: 'post',
	// 		url: base_url + 'roommate/insert_placeholder',
	// 		data: form_data,
	// 		success: function (data) {
	// 			$(me).parent('td').html(data);
	// 		},
	// 	});
	// });

	$(document).on('change', '.roommates #person_id', function () {
		my_tour = $('#tour_id').val();
		my_stay = $('#stay').val();
		my_room = $(this).parents('div.room-row').attr('id').split('_')[1];
		my_person = $(this).val();
		form_data = {
			tour_id: my_tour,
			stay: my_stay,
			room_id: my_room,
			person_id: my_person,
			ajax: '1',
		};
		$.ajax({
			type: 'post',
			url: base_url + 'roommate/insert_row',
			data: form_data,
			success: function (data) {
				$('.room-row#room_' + my_room).html(data);
			},
		});
	});

	$(document).on('click', '.delete-roommate', function () {
		question = confirm('Are you sure you want to delete this roommate? This cannot be undone!');
		if (question) {
			my_id = this.id.split('_');
			my_room = my_id[1];
			my_person = my_id[2];
			my_tour = $('#tour_id').val();
			my_stay = $('#stay').val();
			form_data = {
				room_id: my_room,
				tour_id: my_tour,
				person_id: my_person,
				stay: my_stay,
				ajax: '1',
			};

			$.ajax({
				type: 'post',
				url: base_url + 'roommate/delete',
				data: form_data,
				success: function (data) {
					$('#roommate-block_' + my_room).html(data);
				},
			});

		}
	});

	$(document).on('click', '.duplicate-previous-stay', function () {
		my_id = this.id.split('_');
		form_data = {
			tour_id: my_id[1],
			stay: my_id[2],
		};
		$.ajax({
			type: 'post',
			url: base_url + 'roommate/duplicate',
			data: form_data,
			success: function (data) {
				window.location.href = base_url + 'roommate/view_for_tour/' + my_id[1] + '/' + my_id[2];
			},
		});

	});

	$(document).on('click', '.delete-room', function () {
		question = confirm('are you sure you want to delete this room? This cannot be undone!');
		if (question) {
			my_id = this.id.split('_')[1];
			form_data = {
				id: my_id,
			};

			$.ajax({
				type: 'post',
				data: form_data,
				url: base_url + 'room/delete',
				success: function (data) {
					$('#room_' + my_id).remove();

				},
			});
		}
	});

});//end document.ready

function sort_ids(elements) {
	elements.sort(function (a, b) {
		return parseInt(a.id) > parseInt(b.id);
	});

}
