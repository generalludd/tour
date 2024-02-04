document.addEventListener('DOMContentLoaded', function () {
	// Locate any item with the class .message and allow it to be hidden.
	const messages = document.querySelectorAll('.message');
	messages.forEach(function (message) {
		const closeBox = message.querySelector('button.close');
		if (closeBox) {
			closeBox.addEventListener('click', function (event) {
				message.classList.add('hidden');

			});
		}

		// Reset the message when a backup is requested.
		const doBackup = message.querySelector('a.do-backup');
		if (doBackup) {
			doBackup.addEventListener('click', function (event) {
				const content = message.querySelector('.content');
				content.innerHTML = 'The backup should be downloaded in your Downloads folder. Please move to your ballpark tours folder.';
				message.classList.remove('warning');
				message.classList.add('notice');
			});
		}
	});

	// Trims the value of any input when it loses focus.
	const inputs = document.querySelectorAll('#content input');
	inputs.forEach(function (input) {
		input.addEventListener('blur', function () {
			const value = input.value;
			input.value = value.trim();
		});
	});

});
