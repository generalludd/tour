<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 19, 2013 8:40:02 PM chrisdart@cerebratorium.com

?>
<form name="phone-editor" id="phone-editor" action="<?=site_url("phone/$action");?>" method="post">
<input type="hidden" id="person_id" name="person_id" value="<?=$person_id;?>"/>
<input type="hidden" id="phone_id" name="phone_id" value="<?=get_value($phone,"id");?>"/>
<?=form_dropdown("phone_type",array("Home"=>"Home","Mobile"=>"Mobile","Work"=>"Work"), get_value($phone,"phone_type"));?>

<?=create_input($phone,"phone","Phone", array("type"=>"tel"));?>
<label for="is_primary">Is Primary Phone: </label>
<?=form_checkbox("is_primary",get_value($phone, "is_primary"),"Is Primary");?>

<input type="submit" name="submit" class="button mini" value="<?=ucfirst($action);?>"/>

</form>