document.addEventListener('DOMContentLoaded', function () {
	// Print with letterhead.
	document.getElementById('print').addEventListener('change', function (event) {
		event.preventDefault();
		const letter = document.getElementById('letter');
		const checked = event.target.checked;
		if(checked){
			letter.classList.remove('hide-letterhead');
		}else{
			letter.classList.add('hide-letterhead');
		}
	});
});
