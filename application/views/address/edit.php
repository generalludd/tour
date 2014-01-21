<?php

defined('BASEPATH') or exit('No direct script access allowed');

// edit.php Chris Dart Dec 19, 2013 7:57:31 PM chrisdart@cerebratorium.com

?>
<form
	name="address-editor"
	id="address-editor"
	action="<?=site_url("address/$action");?>"
	method="post">
	<input
		type="hidden"
		id="person_id"
		name="person_id"
		value="<?=$person_id;?>" /> <input
		type="hidden"
		id="id"
		name="id"
		value="<?=get_value($address, "id");?>" />
	<?=create_input($address,"address","Street",array("format"=>"text","class"=>"address-street-field"));?>
	<?=create_input($address, "city","City", array("format"=>"text"));?>
<?=create_input($address, "state","State", array("format"=>"text","class"=>"address-state-field"));?>
<?=create_input($address, "zip","Zip", array("format"=>"int"));?>
	<div>
		<input
			type="submit"
			name="submit"
			value="<?=ucfirst($action);?>"
			class="button" />
	</div>
</form>