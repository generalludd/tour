function addHotel(event) {
	fetch(event.target.getAttribute('href') + '?ajax=1')
		.then(response => response.text())
		.then(data => {
			showPopup( data);
		});
}




function deleteHotel(event) {
	const myHotel = event.target.dataset.hotel_id;
	const myTour = event.target.dataset.tour_id;
	const decision = confirm('This will completely delete this hotel from the database including all related contacts. Are you sure you want to continue? This cannot be undone!');
	if (decision) {
		const finalDecision = confirm('You have decided to permanently delete this hotel from the database. Be sure to update all other hotel records to fill the gap this leaves! This cannot be undone. Are you sure?');
		if (finalDecision) {
			const form_data = {
				id: myHotel,
				ajax: 1,
			};
			fetch(base_url + 'hotel/delete', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify(form_data),
			})
				.then(response => response.text())
				.then(data => {
					window.location.href = base_url + 'hotel/view_for_tour/' + myTour;
				});
		}
	}
}

// convert the functions below to plain Javascript
document.addEventListener('DOMContentLoaded', function () {
	// Click listener
	document.getElementById('content').addEventListener('click', function (event) {
		if (event.target.classList.contains('add-hotel')) {
			event.preventDefault();
			addHotel(event);
		}
		if (event.target.classList.contains('delete-hotel')) {
			event.preventDefault();
			deleteHotel(event);
		}
	});
});

