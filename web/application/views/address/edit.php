<?php

defined('BASEPATH') or exit('No direct script access allowed');

// edit.php Chris Dart Dec 19, 2013 7:57:31 PM chrisdart@cerebratorium.com

?>
<form
	name="address-editor"
	id="address-editor"
	action="<?php print site_url("address/$action");?>"
	method="post">
	<input
		type="hidden"
		id="person_id"
		name="person_id"
		value="<?php print $person_id;?>" /> <input
		type="hidden"
		id="id"
		name="id"
		value="<?php print get_value($address, "id");?>" />
	<?php print create_input($address,"address","Street",array("format"=>"text","class"=>"address-street-field"));?>
	<?php print create_input($address, "city","City", array("format"=>"text"));?>
<?php print create_input($address, "state","State", array("format"=>"text","class"=>"address-state-field"));?>
<?php print create_input($address, "zip","Zip", array("format"=>"int"));?>
<?php print create_input($address, "informal_salutation","Informal Salutation",array("format"=>"text"));?>
<?php print create_input($address, "formal_salutation", "Formal Salutation", array("format"=>"text"));?>
	<div>
		<input
			type="submit"
			name="submit"
			value="<?php print ucfirst($action);?>"
			class="button" />
	</div>
</form>