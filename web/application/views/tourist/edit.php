<?php defined('BASEPATH') or exit('No direct script access allowed');

// edit.php Chris Dart Dec 28, 2013 7:49:30 PM chrisdart@cerebratorium.com
?>
<form id="add_tourist" name="edit-tourist"
	  action="<?php print base_url('tourist/' . $action); ?>" method="post">
	<p><strong>This will add a new person to the address book as well as to this
			tour!</strong></p>
	<input type="hidden" name="person_id" id="person_id"
		   value="<?php print get_value($tourist, "person_id"); ?>"/>
	<input type="hidden" name="payer_id" id="payer_id" value="<?php print $payer_id;?>"/>
	<input type="hidden" name="tour_id" id="tour_id"
		   value="<?php print $tour_id; ?>"/>
	<div>

		<label for="first_name">First Name</label>
		<input type="text" name="first_name" id="first_name"
			   value="<?php print get_value($tourist, "first_name"); ?>"/>
	</div>
	<div>
		<label for="last_name">Last Name</label>
		<input type="text" name="last_name" id="last_name"
			   value="<?php print get_value($tourist, "last_name"); ?>"/>
	</div>
	<div>
		<label for="shirt_size">Shirt Size</label>
		<?php print form_dropdown("shirt_size", $shirt_sizes, get_value($tourist, "shirt_size"), 'id="shirt_size"'); ?>
	</div>
	<div>
		<input class="button new" type="submit" name="save"
			   value="<?php print ucfirst($action); ?>"/>
	</div>

</form>
