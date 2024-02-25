document.addEventListener('DOMContentLoaded', function () {
	let debounceTimer;
	const debounceDelay = 250;
	// Clears the search box when it loses focus.
	const personSearch = document.getElementById('search-list');
	personSearch.addEventListener('blur', function (event) {
		personSearch.classList.add('hidden');
	});

	// When the user types in the search box, the search results are displayed.
	document.addEventListener('keyup', function (event) {
		const element = event.target;
		if (element.classList.contains('person-search')) {
			// Clear the existing timer on each keyup to reset the debounce timer
			clearTimeout(debounceTimer);

			const my_name = element.value;
			const field_id = element.id;
			const my_url = element.dataset.url;

			if (my_name.length > 0) {
				// Debounce setup: Delay execution of the following block
				debounceTimer = setTimeout(function() {
					const xhr = new XMLHttpRequest();
					xhr.open('GET', my_url + '?ajax=1&name=' + encodeURIComponent(my_name), true);
					xhr.onreadystatechange = function () {
						if (xhr.readyState === 4 && xhr.status === 200) {
							const data = xhr.responseText;
							const targetElement = document.getElementById('search-list');
							targetElement.classList.remove('hidden');
							targetElement.style.zIndex = '1000';
							targetElement.innerHTML = data;

							const fieldElement = document.getElementById(field_id);
							const sourceRect = fieldElement.getBoundingClientRect();
							targetElement.style.top = (sourceRect.bottom + window.scrollY).toString() + 'px';
							targetElement.style.left = (sourceRect.left + window.scrollX).toString() + 'px';
						}
					};
					xhr.send();
				}, debounceDelay);
			} else {
				// Hide the search list if the input is empty
				const targetElement = document.getElementById('search-list');
				targetElement.classList.add('hidden');
			}
		}
	});


});
