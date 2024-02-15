<?php
if(empty($merge)){
	return NULL;
}

?>

<form
	id="merge-editor"
	name="merge-editor"
	action="<?php print site_url("merge/update_note");?>"
	method="post">
	<input
		type="hidden"
		id="id"
		name="id"
		value="<?php print $merge->id;?>" />
	<input
		type="hidden"
		id="payer_id"
		name="payer_id"
		value="<?php print $merge->payer_id;?>" />
	<input type="hidden" id="letter_id" name="letter_id" value="<?php print $merge->letter_id;?>">
<div class="note--wrapper">
	<label for="note" class="hidden">Note</label>
	<textarea
		id="note"
		name="note">
<?php print $merge->note;?>
</textarea>
</div>
	<input type="submit" value="Save Note" class="button edit">

</form>

