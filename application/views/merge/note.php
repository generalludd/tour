<?php

defined('BASEPATH') or exit('No direct script access allowed');

// note.php Chris Dart Mar 15, 2014 6:26:08 PM chrisdart@cerebratorium.com
?>


<textarea
	id="note"
	class="tinymce"
	name="note">
<?php print get_value($merge, "note");?>
</textarea>
<?php print create_button_bar(array(array("text"=>"Save Note","class"=>"button save save-note", "type"=>"span")));?>

<script>
	let editor;

	ClassicEditor
			.create( document.querySelector( '#note' ) )
			.then( newEditor => {
				editor = newEditor;
			} )
			.catch( error => {
				console.error( error );
			} );

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
</script>
