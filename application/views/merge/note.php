<?php

defined('BASEPATH') or exit('No direct script access allowed');

// note.php Chris Dart Mar 15, 2014 6:26:08 PM chrisdart@cerebratorium.com
?>
<script
	type="text/javascript"
	src="<?=base_url("tiny_mce/jquery.tinymce.js");?>"></script>
<script
	type="text/javascript"
	src="<?=base_url("js/editor.js");?>"></script>
<p>
	NOTE: DO NOT PASTE DIRECTLY FROM ANOTHER WORD PROCESSOR, USE THE <span
		id="word-icon"></span> BUTTON BELOW
</p>
<textarea
	id="note"
	class="tinymce"
	name="note">
<?=get_value($merge, "note");?>
</textarea>
<?=create_button_bar(array(array("text"=>"Save Note","class"=>"button save save-note", "type"=>"span")));