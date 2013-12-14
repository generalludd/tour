<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 13, 2013 10:15:59 AM chrisdart@cerebratorium.com

/*
 * Array ( [0] => stdClass Object ( [id] => 1 [address_id] => 1 [first_name] =>
         * Julian [last_name] => Loscalzo [email] => ballparktours@qwestoffice.net
         * [shirt_size] => XL [salutation] => Dear Julian: [phone_id] => 1 [person_id]
         * => 1 [phone] => 651-227-3437 [phone_type] => home [num] => 1141 [street] =>
         * Portland Ave [unit_type] => [unit] => [city] => Saint Paul [state] => MN
         * [zip] => 55104 ) )
*/

?>

<div class="grouping block person-info">
<fieldset>
<legend><?="$person->first_name $person->last_name"; ?></legend>
<input type="hidden" id="id" name="id" value="<?=get_value($person,"id");?>"/>
<input type="hidden" id="address_id" name="address_id" value="<?=get_value($person,"id");?>"/>

<p>
<?=create_input($person, "first_name","First Name");?>
<br/>
<?=create_input($person, "last_name","Last Name");?>
<br/>
<?=create_input($person, "email", "Email");?>
<br/>
<?=create_input($person, "shirt_size","Shirt Size");?>
</p>
</fieldset>
</div>