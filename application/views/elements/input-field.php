<?php
if(empty($label) || empty($id)){
	return FALSE;
}
if(empty($wrapper)){
	$wrapper = 'p';
}
if(empty($type)){
	$type = 'text';
}
if(empty($size)){
	$size = '60';
}
?>

<<?php print $wrapper;?> class="input-block">
<label for="<?php print $id; ?>>">
	<?php print $label; ?>
</label>
<?php if($type == 'textarea'):?>
<textarea name="<?php print $id; ?>" id="<?php print $id; ?>">
	<?php print $value; ?>
</textarea>
<?php else: ?>
<input type="<?php print $type; ?>" name="<?php print $id; ?>" id="<?php print $id; ?>"
						  value="<?php print $value; ?>"  size="<?php print $size; ?>" class="input ">
<?php endif; ?>
</<?php print $wrapper;?>>
