<?php
if(empty($type)){
	$type = 'delete';
}
?>

<h5>Are you sure you want to <?php print $type;?> <?php print $entity; ?>?</h5>
<p><?php print $message; ?></p>
<form name="entity-delete" action="<?php print base_url($action); ?>"
	  method="post">
	<?php foreach ($identifiers as $key => $identifier): ?>
		<input type="hidden" name="<?php print $key; ?>"
			   value="<?php print $identifier; ?>"/>
	<?php endforeach; ?>
	<input type="submit" class="delete" value="<?php print ucfirst($type); ?>"/>
</form>
