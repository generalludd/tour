document.addEventListener('DOMContentLoaded', function () {
	const deleteButton = document.querySelector('.delete-template');
	if (deleteButton) {
		deleteButton.addEventListener('click', function (event) {
			event.preventDefault();

			const question = confirm('Are you sure you want to delete this letter? This cannot be undone!');
			if(question){
				const tour_id = deleteButton.getAttribute('data-tour_id');
				const letter_id = deleteButton.getAttribute('data-letter_id');
				const form_data = {
					id: letter_id,
					tour_id: tour_id
				};
				const xhr = new XMLHttpRequest();
				xhr.open('POST', base_url + 'letter/delete', true);
				xhr.setRequestHeader('Content-Type', 'application/json');
				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4 && xhr.status === 200) {
						window.location.href = base_url + 'tours/view/' + tour_id;
					}
				};
				xhr.send(JSON.stringify(form_data));
			}

		});
	}
});
