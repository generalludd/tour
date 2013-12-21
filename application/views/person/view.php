<?php
defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 11, 2013 7:47:55 PM chrisdart@cerebratorium.com
?>
<?=create_button_bar(array(array("text"=>"Join Tour", "type"=>"span","class"=>"button new mini select-tour", "id"=>sprintf("join-tour_%s",$person->id))));?>

<fieldset class="grouping block person-info" id="person">
<legend>General Info</legend>
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
</fieldset>
<fieldset class="grouping block phone-info" id="phone">
<legend>Phones</legend>
<? $this->load->view("phone/view",$person->phones); ?>
<?
print create_button_bar(array(array("text" => "Add Phone", "type"=>"span", "class"=>"button mini new add_phone","id"=>sprintf("add-phone_%s",$person->id))));?>
</fieldset>
<fieldset class="grouping block address-info" id="address">
<legend>Address</legend>
<? if(count($person->address) == 1):?>
<div class="field-set">
	<?=create_edit_field("num", $person->address->num, "House Number",array("envelope"=>"div"));?>
	<?=create_edit_field("street", $person->address->street, "Street",array("envelope"=>"div"));?>
	<?=create_edit_field("unit_type", $person->address->unit_type, "Unit Type" ,array("envelope"=>"div"));?>
	<?=create_edit_field("unit", $person->address->unit, "Unit", array("envelope"=>"div"));?>
</div>
<div class="field-set">
	<?=create_edit_field("City", $person->address->city, "City", array("envelope"=>"div"));?>
	<?=create_edit_field("State", $person->address->state, "State", array("envelope"=>"div"));?>
	<?=create_edit_field("Zip", $person->address->zip, "Zip", array("envelope"=>"div"));?>
</div>
</fieldset>
<?else: ?>
<?=create_button_bar(array("text" => "Add Address", "type"=>"span", "class"=>"button mini new add_address","id"=>sprintf("add-address_%s",$person->id)));?>

<?endif;?>
<fieldset class="grouping block housemates" id="housemates">
<legend>Housemates</legend>
<?=create_button_bar(array(array("text" => "Add Housemate", "type"=>"span", "class"=>"button mini new add-housemate","id"=>sprintf("add-housemate_%s%s",$person->id, $person->address->id))));?>

</fieldset>
