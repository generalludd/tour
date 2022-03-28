<?php
if(empty($id)){
	return FALSE;
}

if(!empty($class)){
	if(!is_array($class)){
		$classes = explode(' ', $class);
	}
}
else {
	$classes = ['field-envelope'];
}
if(empty($wrapper)){
	$wrapper= 'p';
}
if(empty($field_wrapper)){
	$field_wrapper = 'span';
}
if(empty($required)){
	$required = '';
}
?>

<<?php print $wrapper;?> class="<?php print implode(' ', $classes);?>" data-target-id="<?php print $id; ?>">
	<label for="<?php print $id; ?>"><?php print $label;?></label>
	<<?php print $field_wrapper; ?> class="field" id="<?php print $id; ?> <?php print $required; ?>"><?php print $value; ?></<?php print $field_wrapper; ?>>
</<?php print $wrapper;?>>
