$("body").on("click", ".save-note", function(){
	my_id = $("#id").val();
	const my_note = editor.getData();
	console.log(my_note);
	form_data = {
		id: my_id,
		field: "note",
		value: my_note,
		ajax: 1
	};
	$.ajax({
		type: "post",
		url: base_url + "merge/update_value",
		data: form_data,
		success: function(data){
			$("#merge-note").html(data);
			$("#note-button-block").html("<span class='button edit small edit-merge-note'>Edit Custom Note</span>");
		}
	});
});

document.addEventListener('DOMContentLoaded', function () {
	document.getElementById('content').addEventListener('click', function (event) {

	});
});
