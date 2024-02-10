<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 19, 2013 8:40:02 PM chrisdart@cerebratorium.com

?>
<form name="phone-editor" id="phone-editor" action="<?php print site_url("phone/$action");?>" method="post">
<input type="hidden" id="person_id" name="person_id" value="<?php print $person_id;?>"/>
<input type="hidden" id="phone_id" name="phone_id" value="<?php print get_value($phone,"id");?>"/>
<label for="phone_type">Type: </label>
<?php print form_dropdown("phone_type",array("Home"=>"Home","Mobile"=>"Mobile","Work"=>"Work"), get_value($phone,"phone_type"));?>

<?php print create_input($phone,"phone","Phone", array("type"=>"telephone"));?>
<label for="is_primary">Is Primary Phone: </label>
<?php print form_checkbox("is_primary",1, get_value($phone, "is_primary",FALSE) ? TRUE:FALSE);?>

<input type="submit" name="submit" class="button mini" value="<?php print ucfirst($action);?>"/>

</form>
