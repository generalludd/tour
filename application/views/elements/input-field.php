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
<input type="<?php print $type; ?>" name="<?php print $id; ?>" id="<?php print $id; ?>"
						  value="<?php print $value; ?>"  size="<?php print $size; ?>" class="input ">
</<?php print $wrapper;?>>