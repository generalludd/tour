<?php ?>

<h2>Are you sure you want to delete <?php print $entity_name; ?>?</h2>
<form name="entity-delete" action="<?php print base_url($action);?>" method="post">
	<input type="hidden" name="entity_id" value="<?php print $entity_id;?>"/>
	<input type="submit" class="delete" value="Delete"/>
</form>
