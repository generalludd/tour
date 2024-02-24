document.addEventListener('DOMContentLoaded', function () {
	// Function to initialize ClassicEditor
	function initializeClassicEditor(selector) {
		const element = document.getElementById(selector);
		if (element) {
			ClassicEditor
				.create(element)
				.catch(error => {
					console.error(error);
				});
		}
	}

	// Initialize editors for existing elements
	initializeClassicEditor('body');
	initializeClassicEditor('cancellation');

	// MutationObserver to observe changes in the #content area
	const contentArea = document.getElementById('page');
	if (contentArea) {
		const observer = new MutationObserver(function (mutations) {
			mutations.forEach(function (mutation) {
				mutation.addedNodes.forEach(function (addedNode) {
					// Check if the added node or any of its children have the 'note' id
					const note = addedNode.querySelector ? addedNode.querySelector('#note') : null;
					// If note element is found within the added node, initialize ClassicEditor on it
					if (note) {
						initializeClassicEditor('note');
					}
				});
			});

		});

		document.getElementById('font-size').addEventListener('change', function (event) {
			const value = event.target.value;
			document.getElementById('printed-content').style.fontSize = value + 'em';
		});


		// Configuration of the observer:
		const config = { childList: true, subtree: true };

		// Start observing the target node for configured mutations
		observer.observe(contentArea, config);
	}
});
