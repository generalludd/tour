<?php
defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 11, 2013 7:47:55 PM chrisdart@cerebratorium.com

/*
 * Array ( [0] => stdClass Object ( [id] => 1 [address_id] => 1 [first_name] =>
 * Julian [last_name] => Loscalzo [email] => ballparktours@qwestoffice.net
 * [shirt_size] => XL [salutation] => Dear Julian: [phone_id] => 1 [person_id]
 * => 1 [phone] => 651-227-3437 [phone_type] => home [num] => 1141 [street] =>
 * Portland Ave [unit_type] => [unit] => [city] => Saint Paul [state] => MN
 * [zip] => 55104 ) )
 */

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
<div class="grouping block address-info" id="address">
<div class="field-set">
	<?=create_edit_field("num", $person->num, "House Number",array("envelope"=>"span"));?>
	<?=create_edit_field("street", $person->street, "Street",array("envelope"=>"span"));?>
	<?=create_edit_field("unit_type", $person->unit_type, "Unit Type",array("envelope"=>"span"));?>
	<?=create_edit_field("unit", $person->unit, "Unit",array("envelope"=>"span"));?>


</div>
</div>