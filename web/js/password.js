document.addEventListener('DOMContentLoaded', function () {
	// Use document or a closer static parent as the event listener attachment point
	document.addEventListener('keyup', function (e) {
		// Check if the event is triggered by your target elements
		if (e.target.id === 'new_password' || e.target.id === 'check_password') {
			// Corrected function call to pass values directly
			match_passwords();
		}
	});

	// Assuming this listener is for a button click to validate the passwords before an action
	document.addEventListener('click', function (e) {
		if (e.target.id === 'change-password') {
			e.preventDefault();
			// Corrected function call to pass values directly
			const match = match_passwords();
			if(match) {
				// submit the parent form
				e.target.closest('form').submit();
			}
		}
		if(e.target.id === 'show_password'){
			const newPass = document.getElementById('new_password');
			const checkPass = document.getElementById('check_password');
			if(newPass.type === 'password' && checkPass.type === 'password'){
				newPass.type = 'text';
				checkPass.type = 'text';
			} else {
				newPass.type = 'password';
				checkPass.type = 'password';
			}
		}
	});
});

// Adjusted function to accept values directly, not elements
function match_passwords() {
	const newPass = document.getElementById('new_password').value;
	const checkPass = document.getElementById('check_password').value;
	let match = false;
	if(newPass === '' && checkPass === ''){
		match = true;
	} else {
		match = newPass === checkPass;
	}
	const message = document.getElementById('password_message');
	const button = document.getElementById('change-password');
	if (match && message) {
		message.classList.add('hidden');
		button.disabled = false;

	} else if (!match && message) {
		// Show or update the message if needed
		message.classList.remove('hidden');
		message.classList.add('alert');
		message.textContent = 'Passwords do not match';
		button.disabled = true;
	}
	return match;

}
