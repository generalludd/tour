document.addEventListener('DOMContentLoaded', function () {
	// Clears the search box when it loses focus.
	const personSearch = document.getElementById('search-list');
	personSearch.addEventListener('blur', function (event) {
		personSearch.classList.add('hidden');
	});

	// When the user types in the search box, the search results are displayed.
	document.addEventListener('keyup', function (event) {
		const element = event.target;
		if (element.classList.contains('person-search')) {
			const my_name = element.value;
			const field_id = element.id;
			const my_url = element.dataset.url;
			console.log(my_url);
			if (my_name.length > 0) {
				let form_data = {
					ajax: 1,
					name: my_name,
				};
				// Convert the ajax below to XMLHttpRequest
				const xhr = new XMLHttpRequest();
				xhr.open('GET', my_url + '?ajax=1&name=' + my_name, true);
				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4 && xhr.status === 200) {
						const data = xhr.responseText;
						const targetElement = document.getElementById('search-list');
						targetElement.classList.remove('hidden');
						targetElement.style.zIndex = '1000';
						targetElement.innerHTML = data;
						const fieldElement = document.getElementById(field_id);
						const sourceRect = fieldElement.getBoundingClientRect();
						targetElement.style.top = sourceRect.bottom.toString() + 'px';
						targetElement.style.left = sourceRect.left.toString() + 'px';
					}
				};
				xhr.send();
			} else {
				const targetElement = document.getElementById('search-list');
				targetElement.classList.add('hidden');
			}
		}
	});


});
