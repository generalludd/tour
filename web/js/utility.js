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

});
