<?php

if (empty($action) || empty($identifiers)) {
	return NULL;
}
if (empty($message)) {
	$message = 'Are you sure you want to delete this?';
}

if(empty($button_override)) {
	$button_override = 'Delete';
}

?>
<p><?php print $message; ?></p>
<form name="entity-delete" action="<?php print base_url($action); ?>"
			method="post">
	<?php foreach ($identifiers as $key => $identifier): ?>
		<input type="hidden" name="<?php print $key; ?>"
					 value="<?php print $identifier; ?>"/>
	<?php endforeach; ?>
	<?php if (!empty($additional_fields)): ?>
		<?php foreach ($additional_fields as $name => $field): ?>
		<label><?php print $name; ?>
			<?php print $field; ?></label>
		<?php endforeach; ?>
	<?php endif; ?>
	<input type="submit" class="delete button" value="<?php print $button_override;?>"/>
</form>
