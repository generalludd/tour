<?php

defined('BASEPATH') or exit('No direct script access allowed');

// edit.php Chris Dart Dec 13, 2013 10:15:59 AM chrisdart@cerebratorium.com

$first_name = get_value($person, "first_name", FALSE);
$last_name = get_value($person, "last_name", FALSE);
$full_name = sprintf("%s %s", $first_name, $last_name);
if (! $first_name) {
    $full_name = "Adding a New Person";
}

?>
<div class="grouping block person-info">
	<form
		name="person-editor"
		id="person-editor"
		action="<?php print site_url("person/$action");?>"
		method="post">
		<h4><?php print "$full_name"; ?></h4>
		<input
			type="hidden"
			id="id"
			name="id"
			value="<?php print get_value($person,"id");?>" /> <input
			type="hidden"
			id="address_id"
			name="address_id"
			value="<?php print get_value($person,"address_id");?>" />

<?php print create_input($person, "first_name","First Name");?>

<?php print create_input($person, "last_name","Last Name");?>
<?php print create_input($person, "email", "Email", array("type"=>"email"));?>
<label for="shirt_size">Shirt Size:</label>
<?php print form_dropdown("shirt_size",$shirt_sizes,get_value($person, "shirt_size", array("id"=>"shirt_size")));?>
<?php print create_input($person,"is_veteran","Is Veteran",array("type"=>"checkbox"));?>
<div>
<label for="note">Note about <?php print get_value($person,"first_name","this person"); ?>:</label><br/>
<textarea id="note" name="note" class="save-field"><?php print get_value($person,"note");?></textarea>
</div>
<p>
<input
			type="submit"
			name="submit-person-editor"
			id="submit-person-editor"
			class="button"
			value="<?php print ucfirst($action);?>" />
<?php if( $action == "update" ): ?>
<?php if(get_value($person, "id", FALSE) && $tour_count == 0 ):?>
<span
			class="button delete delete-person"
			id="<?php print sprintf("delete-person_%s",$person->id);?>">Delete</span>
<?php else: ?>
<span
			class="button delete disable-person"
			id="<?php print sprintf("disable-person_%s",$person->id);?>"
			title="This person has been on tours, they will be disabled instead of deleted">Delete</span>
<?php endif; ?>
<?php endif;?>
</p>
</form>
</div>