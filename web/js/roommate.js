function addRoommateRow(event) {
	const button = event.target;
	const myUrl = button.getAttribute('href') + '?ajax=1';
	fetch(myUrl, {
			method: 'GET',
		},
	)
		.then(response => {

			return response.json();
		})
		.then(data => {
			const row = document.getElementById('roommate-block_' + data.room_id);
			console.log(row);
			row.innerHTML = data.html;
		})
		.catch(error => {
			console.error('Fetch error:', error);
		});
}

function addPlaceholderField(event) {
	const button = event.target;
	const myUrl = button.getAttribute('href');
	console.log(myUrl);
	fetch(myUrl, {
			method: 'GET',
		},
	)
		.then(response => {
			return response.json();
		})
		.then(data => {
			// @todo replace this with a CodeIgniter-generated html
			/// Get the parent class of button
			const parent = button.parentElement;
			// remove the button
			parent.removeChild(button);
			// create a new input element from data.input
			const newInput = document.createElement('input');
			newInput.classList.add('insert-placeholder');
			newInput.setAttribute('type', 'text');
			newInput.setAttribute('name', 'placeholder');
			newInput.setAttribute('placeholder', data.placeholder);
			newInput.setAttribute('data-room_id', data.room_id);
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

function insertPlaceholderRoommate(event) {
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
			//@todo Replace this with a CodeIgniter-generated html.
			// Find the parent by the data-room_id attribute;
			const row = document.getElementById('roommate-block_' + data.room_id);
			row.innerHTML = data.html;

		})
		.catch(error => {
			console.error('Fetch error:', error);
		});
}

function insertRoommate(event) {
	const formData = {
		tour_id: event.target.dataset.tour_id,
		stay: event.target.dataset.stay,
		room_id: event.target.dataset.room_id,
		person_id: event.target.value,
	};
	console.log(formData);
	const myUrl = event.target.dataset.href + '?ajax=1';
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
			const roomRow = document.getElementById('roommate-block_' + data.room_id);
			roomRow.innerHTML = data.html;
		});
}

function deleteRoommate(event){
	const question = confirm('Are you sure you want to delete this roommate? This cannot be undone!');
	if (question) {
		const formData = {
			room_id: event.target.dataset.room_id,
			tour_id: event.target.dataset.tour_id,
			person_id: event.target.dataset.person_id,
			stay: event.target.dataset.stay,
		};
		console.log(formData);
		const myUrl = base_url + 'roommate/delete?ajax=1';
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
				const roomRow = document.getElementById('roommate-block_' + data.room_id);
				roomRow.innerHTML = data.html;
			});
	}
}

function deleteRoom(event) {
	const question = confirm('Are you sure you want to delete this room? This cannot be undone!');
	if (question) {
		const formData = {
			room_id: event.target.dataset.room_id,
			tour_id: event.target.dataset.tour_id,
		};
		const myUrl = base_url + 'room/delete?ajax=1';
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
				const roomRow = document.getElementById('roommate-block_' + data.room_id);
				roomRow.remove();
			});
	}
}

document.addEventListener('DOMContentLoaded', function () {

	// Add a click event listener to the #content of the page.
	document.getElementById('content').addEventListener('click', function (event) {
		event.preventDefault();
		if (event.target.classList.contains('add-roommate')) {
			addRoommateRow(event);
		}

		if (event.target.classList.contains('add-placeholder')) {
			addPlaceholderField(event);
		}

		if(event.target.classList.contains('delete-roommate')){
			 deleteRoommate(event);
		}

		if(event.target.classList.contains('delete-room')){
			deleteRoom(event);
		}
	});

	// add a focusout listener to the #content of the page
	document.getElementById('content').addEventListener('focusout', function (event) {

		if (event.target.classList.contains('insert-placeholder')) {
			insertPlaceholderRoommate(event);
		}
		if (event.target.classList.contains('roomless-tourists') && event.target.value !== '') {
			insertRoommate(event);
		}
	});


});
$(document).ready(function () {



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



});//end document.ready

function sort_ids(elements) {
	elements.sort(function (a, b) {
		return parseInt(a.id) > parseInt(b.id);
	});

}
