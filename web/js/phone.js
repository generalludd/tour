$(document).ready(function () {
	$(document).on("click", ".add-phone",function (e) {
		e.preventDefault();
		form_data = {
			ajax: 1
		};
		$.ajax({
			type: "get",
			url: $(this).attr('href'),
			data: form_data,
			success: function (data) {
				show_popup("Add Phone", data, "auto");
			}

		});

	});

	$(document).on("click", ".edit-phone", function (e) {
		e.preventDefault();
		let form_data = {
			ajax: "1"
		};
		$.ajax({
			type: "get",
			url: $(this).attr('href'),
			data: form_data,
			success: function (data) {
				show_popup("Edit Phone", data, "auto");
			}
		});
	});

	$(document).on("click", ".delete-phone", function () {
		let question = confirm("Are you sure you want to delete this phone number? It cannot be undone!");
		if (question) {
			let my_id = $(this).data('phone_id');
			form_data = {
				id: my_id,
				ajax: "1"
			};
			$.ajax({
				type: "post",
				url: base_url + "phone/delete",
				data: form_data,
				success: function (data) {
					if (data) {
						$("#delete-phone_" + my_id).parents("div.phone-row").remove();
					}
				}
			});
		}
	});
});
