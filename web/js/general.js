$(document).ready(function () {

	const inputs = document.querySelectorAll('#content input');
	inputs.forEach(function (input) {
		input.addEventListener('blur', function () {
			const value = input.value;
			input.value = value.trim();
		});
	});
	// trim all inputs on blur

	document.addEventListener('keyup', function (event) {
		const element = event.target;
		if(element.classList.contains('person-search')) {
			const my_name = element.value;
			const field_id = element.id;
			const my_url = element.dataset.url;
			const my_target = element.dataset.target;
			if (my_name.length > 0) {
				let form_data = {
					ajax: 1,
					name: my_name
				};
				// Convert the ajax below to XMLHttpRequest
				const xhr = new XMLHttpRequest();
				xhr.open('GET', my_url + '?ajax=1&name=' + my_name, true);
				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4 && xhr.status === 200) {
						const data = xhr.responseText;
						const targetElement = document.getElementById(my_target);
						targetElement.style.zIndex = "1000";
						targetElement.innerHTML = data;
						const fieldElement = document.getElementById(field_id);
						const sourceRect = fieldElement.getBoundingClientRect();
						console.log(targetElement.style);
						targetElement.style.top = sourceRect.bottom.toString() + "px"
						targetElement.style.left = sourceRect.left.toString() + "px"
						// position the targetElement to the bottom of the fieldElement

						targetElement.style.display = "block";
					}
				};
				xhr.send();
			} else {
				const targetElement = document.querySelector(my_target);
				targetElement.style.display = "none";
			}
		}
	});



	$(document).on('blur', '.person-search',function(event) {
		let my_target = $(this).data('target');
		$(this).val('');
		$(my_target).fadeOut();
	});

	$(document).on("click", ".field-envelope .edit-field", function () {
		let me = $(this);
		let my_parent = me.parent().attr("id");
		let my_attr = my_parent.split("__");
		let my_type = "text";
		let my_category = me.attr('menu');
		let my_name = me.attr("name");
		if (me.hasClass("dropdown")) {
			my_type = "dropdown";
		} else if (me.hasClass("checkbox")) {
			my_type = "checkbox";
		} else if (me.hasClass("multiselect")) {
			my_type = "multiselect";
		} else if (me.hasClass("textarea")) {
			my_type = "textarea";
		} else if (me.hasClass("autocomplete")) {
			my_type = "autocomplete";
		} else if (me.hasClass("date")) {
			my_type = "date";
		}
		let form_data = {
			table: my_attr[0],
			field: my_name,
			id: my_attr[2],
			type: my_type,
			category: my_category,
			value: me.html()
		};
		$.ajax({
			type: "get",
			url: base_url + "menu/edit_value",
			data: form_data,
			success: function (data) {
				$("#" + my_parent + " .edit-field").html(data);
				$("#" + my_parent + " .edit-field").removeClass("edit-field").removeClass("field").addClass("live-field").addClass("text");
				$("#" + my_parent + " .live-field input").focus();

			}
		});
	});

	$(".field-envelope").on("blur", ".live-field.text", function () {
		//id, field, value {post}
		update_field(this);

	});

	$(".field-envelope").on("change", ".live-field.dropdown", function () {
		//id, field, value {post}
		update_field(this);

	});


	$(".checkbox .save-checkbox").on("click", function () {
		update_field($(this));
	});

	$(".multiselect .save-multiselect").on("click", function () {
		save_field($(this));
	});

	$(".history-back").on("click", function () {
		history.back();
		history.back();
	});




	$(".dialog").click("click", function (e) {
		e.preventDefault();
		let redirect_url = $(location).attr("href");
		console.log(redirect_url);
		let url = $(this).attr("href");
		let form_data = {
			ajax: 1
		};

		$.ajax({
			type: "get",
			data: form_data,
			url: url,
			success: function (data) {
				show_popup('Edit', data, 600);
				$("#redirect_url").val(redirect_url);
			}
		});
	});

});

/* set up floating button bars for working with long lists*/

$(window).scroll(function () {
	var top = $('.button-box.float');
	if ($(window).scrollTop() > 250) {
		if (top.css('position') != 'fixed') {
			top.css('position', 'fixed');
			top.css('top', 10);
			top.css('background-color', '#000');
		}
	} else {
		if (top.css('position') != 'static') {
			top.css('position', 'static');
			top.css('top', 'inherit');
			top.css('background-color', 'inherit');
		}
	}
});

function show_popup(my_title, data, popup_width, x, y) {
	if (!popup_width) {
		popup_width = 300;
	}
	var myDialog = $('<div id="popup">').html(data).dialog({
		autoOpen: false,
		title: my_title,
		modal: true,
		width: popup_width
	});

	if (x) {
		myDialog.dialog({position: x});
	}


	myDialog.fadeIn().dialog('open', {width: popup_width});

	return false;
}

function create_dropdown(my_field, my_category, my_value) {
	form_data = {
		field: my_field,
		category: my_category,
		value: my_value
	};
	$.ajax({
		type: "get",
		url: base_url + "menu/get_dropdown",
		data: form_data,
		success: function (output) {
			return output;
		}

	});
}


/**
 * takes a dom element and gets the parent with the .field-envelope class. The id of this envelope item
 * is field_[name] where [name] is the db field name (without brackets) .
 * @param me
 */
function edit_field(me) {

	my_field = me.parents(".field-envelope").attr("id").split("-")[1];
	my_value = me.html();
	my_class = "save-field";
	my_type = "text";
	if (me.hasClass("dropdown")) {
		my_category = me.attr("menu");
		form_data = {
			field: my_field,
			category: my_category,
			value: my_value
		};
		$.ajax({
			type: "get",
			url: base_url + "menu/get_dropdown",
			data: form_data,
			success: function (output) {
				me.html(output).removeClass("edit-field");
			}

		});
	} else if (me.hasClass("multiselect")) {
		my_category = me.attr("menu");
		form_data = {
			field: my_field,
			category: my_category,
			value: my_value
		};
		$.ajax({
			type: "get",
			url: base_url + "menu/get_multiselect",
			data: form_data,
			success: function (output) {
				me.html(output).removeClass("edit-field");
			}

		});

	} else if (me.hasClass("textarea")) {
		me.html("<br/><textarea name='" + my_field + "'class='" + my_class + "'>" + my_value + "</textarea>").removeClass("edit-field");

	} else if (me.hasClass("checkbox")) {
		my_category = me.attr("menu");
		form_data = {
			field: my_field,
			category: my_category,
			value: my_value
		};
		$.ajax({
			type: "get",
			url: base_url + "menu/get_checkbox",
			data: form_data,
			success: function (output) {
				me.html(output).removeClass("edit-field");
			}
		});

	} else {
		if (me.attr("format")) {
			my_format = me.attr("format");

			switch (my_format) {
				case "currency":
					my_value = my_value.split("$")[1];
					my_type = "number";
					break;
				case "number":
					my_type = "number";
					break;
				case "date":
					my_type = "date";
					break;
				case "tel":
					my_type = "tel";
					break;
				case "time":
					my_type = "time";
					break;
				case "url":
					my_type = "url";
					break;
				case "email":
					my_type = "email";
					break;
			}
		}
		me.html(
			"<input type='"
			+ my_type +
			"' name='" +
			my_field +
			"' class='"
			+ my_class
			+ "' value='"
			+ my_value + "'/>").removeClass("edit-field");
		$(".save-field").focus();
	}
}


function save_field(me) {
	table = $(me).parents(".grouping").attr("id");
	my_parent = $(me).parents("span");
	my_id = $("#id").val();
	if (table == "phone") {
		my_id = me.parents(".field").attr("id").split("_")[1];
	}
	my_format = $(me).parents("span").attr("format");
	if (my_format == "multiselect") {
		my_field = $(me).parent().children("select").attr("name");
		my_value = my_value.join(",", my_value);
	} else {
		my_field = $(me).attr("name");
		my_value = $(me).val();
	}
	form_data = {
		field: my_field,
		value: my_value,
		format: my_format,
		id: my_id
	};
	my_url = base_url + table + "/update_value";

	$.ajax({
		type: "post",
		url: my_url,
		data: form_data,
		success: function (output) {
			my_parent.addClass("edit-field").html(output);

		}
	});
}


function update_field(me) {
	my_parent = $(me).parent().attr("id");
	my_attr = my_parent.split("__");
	my_value = $(me).children().val();
	form_data = {
		table: my_attr[0],
		field: my_attr[1],
		id: my_attr[2],
		value: my_value
	};
	$.ajax({
		type: "post",
		url: base_url + my_attr[0] + "/update_value",
		data: form_data,
		success: function (data) {
			$("#" + my_parent + " .live-field").html(data);
			$("#" + my_parent + " .live-field").addClass("edit-field field").removeClass("live-field text");
		}
	});
}

function show_modal(me) {
	let target = $(me).attr("href");
	let form_data = {
		ajax: 1
	};
	$.ajax({
		type: "get",
		data: form_data,
		url: target,
		success: function (data) {
			$("#popup").html(data);
			$("#my_dialog").modal("show");
		}
	});
}


function revealBlock(element) {
	let target = element.data('target_id');
	console.log(target);
	$("#" + target).show('500');
}
function hideBlock(element) {
	let target = element.data('target_id');
	$("#" + target).hide(500);
}
	function positionElement(element, settings) {
		const { my, at, of, collision } = settings;
		const myArr = my.split(" ");
		const atArr = at.split(" ");
		const atX = atArr[0];
		const atY = atArr[1];
		const myX = myArr[0];
		const myY = myArr[1];
		const collisionArr = collision.split(" ");
		const collisionX = collisionArr[0];
		const collisionY = collisionArr[1];

		const targetRect = of.getBoundingClientRect();
		const elementRect = element.getBoundingClientRect();
		const offsetX = targetRect.left;
		const offsetY = targetRect.top;
		let left, top;

		if (myX === "left") {
			left = offsetX;
		} else if (myX === "center") {
			left = offsetX + (targetRect.width - elementRect.width) / 2;
		} else if (myX === "right") {
			left = offsetX + targetRect.width - elementRect.width;
		}

		if (myY === "top") {
			top = offsetY;
		} else if (myY === "center") {
			top = offsetY + (targetRect.height - elementRect.height) / 2;
		} else if (myY === "bottom") {
			top = offsetY + targetRect.height - elementRect.height;
		}

		if (collisionX === "fit") {
			if (left < offsetX) {
				left = offsetX;
			} else if (left + elementRect.width > offsetX + targetRect.width) {
				left = offsetX + targetRect.width - elementRect.width;
			}
		}

		if (collisionY === "fit") {
			if (top < offsetY) {
				top = offsetY;
			} else if (top + elementRect.height > offsetY + targetRect.height) {
				top = offsetY + targetRect.height - elementRect.height;
			}
		}

		element.style.left = left + "px";
		element.style.top = top + "px";
	}
