<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 13, 2013 10:15:59 AM chrisdart@cerebratorium.com

$first_name = get_value($person, "first_name",FALSE);
$last_name = get_value($person, "last_name", FALSE);
$full_name = sprintf("%s %s", $first_name, $last_name);
if(!$first_name){
$full_name = "Adding a New Person";
}

?>

<div class="grouping block person-info">
<form name="person-editor" id="person-editor" action="<?=site_url("person/$action");?>" method="post">
<fieldset>
<legend><?="$full_name"; ?></legend>
<input type="hidden" id="id" name="id" value="<?=get_value($person,"id");?>"/>
<input type="hidden" id="address_id" name="address_id" value="<?=get_value($person,"address_id");?>"/>

<p>
<?=create_input($person, "first_name","First Name");?>
<br/>
<?=create_input($person, "last_name","Last Name");?>
<br/>
<?=create_input($person, "email", "Email", array("type","email"));?>
<br/>
<?=create_input($person, "shirt_size","Shirt Size");?>
</p>
</fieldset>
<input type="submit" name="submit-person-editor" id="submit-person-editor" class="button" value="<?=ucfirst($action);?>"/>
</form>
</div>