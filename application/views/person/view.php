<?php
defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 11, 2013 7:47:55 PM chrisdart@cerebratorium.com

?>

<div class="grouping block person-info" id="person">
<input type="hidden" id="id" name="id" value="<?=$person->id;?>"/>
<input type="hidden" id="address_id" name="address_id" value="<?=$person->address_id;?>"/>
<div class='field-set'>
	<?=create_edit_field("first_name", $person->first_name, "First Name",array("envelope"=>"div"));?>
</div>
<div class='field-set'>
	<?=create_edit_field("last_name", $person->last_name, "Last Name",array("envelope"=>"div"));?>
</div>
<div class='field-set'>
	<?=create_edit_field("email", $person->email, "Email",array("envelope"=>"div"));?>
</div>
<div class='field-set'>
	<?=create_edit_field("shirt_size", $person->shirt_size, "Shirt Size",array("envelope"=>"div"));?>
</div>
</div>
<div class="grouping block phone-info" id="phone">
<? $this->load->view("phone/view",$phones); ?>
</div>
<div class="grouping block address-info" id="address">
<div class="field-set">
	<?=create_edit_field("num", $person->num, "House Number",array("envelope"=>"div"));?>
	<?=create_edit_field("street", $person->street, "Street",array("envelope"=>"div"));?>
	<?=create_edit_field("unit_type", $person->unit_type, "Unit Type" ,array("envelope"=>"div"));?>
	<?=create_edit_field("unit", $person->unit, "Unit", array("envelope"=>"div"));?>
</div>
<div class="field-set">
	<?=create_edit_field("City", $person->city, "City", array("envelope"=>"div"));?>
	<?=create_edit_field("State", $person->state, "State", array("envelope"=>"div"));?>
	<?=create_edit_field("Zip", $person->zip, "Zip", array("envelope"=>"div"));?>
</div>
</div>
