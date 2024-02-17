document.addEventListener('DOMContentLoaded', function(){
	document.addEventListener('click', function(event){
		if(event.target.classList.contains('select-housemate')){
			event.preventDefault();
			const url = event.target.dataset.href;
			const person_id = event.target.dataset.person_id;
			const address_id = event.target.dataset.address_id;
			const formData = {
				id: person_id,
				field: 'address_id',
				value: address_id,
				target: 'address/view'
			}
			// Post the data to get the address info.
			fetch( url + '?ajax=1', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'  // Set Content-Type header
					},
					body: JSON.stringify(formData),
				}
			)
				.then(response => response.json())
				.then(data => {
					const address = document.getElementById('address');
					address.innerHTML = data.result;
					const menu = document.getElementById('search-list');
					menu.classList.add('hidden');
				});

		}
	});
});
