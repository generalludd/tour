<?php
?>
<p>Remove <?php echo $person->first_name . ' ' . $person->last_name;?> from this address?</p>
<p><?php echo format_address($address);?></p>
<form id="remove-address" name="remove-address" action="<?php echo base_url('person/remove_address/' . $person->id . '/'. $address->id);?>" method="POST">
	<input type="hidden" name="person_id" value="<?php echo $person->id;?>"/>
	<input type="hidden" name="address_id" value="<?php echo $address->id;?>"/>
	<input type="hidden" name="delete" value="1"/>
	<input type="submit" class="delete button" value="Remove">
</form>
