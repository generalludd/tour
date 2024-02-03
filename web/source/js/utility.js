document.addEventListener('DOMContentLoaded', function () {
	// Locate any item with the class .message
	const messages = document.querySelectorAll('.message');
	messages.forEach(function (message) {
		const closeBox = message.querySelector('button.close');
		if (closeBox) {
			closeBox.addEventListener('click', function (event) {

				message.classList.add('hidden');

			});
		}
	});

});
