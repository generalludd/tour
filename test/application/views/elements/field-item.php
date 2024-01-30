<?php
if (empty($id)) {
	return FALSE;
}

if(empty($label)) {
	$label = ucwords(str_replace('_', ' ', $id));
}

$wrapper_class = ['field-envelope'];
if(!empty($wrapper_classes)){
	$wrapper_class = array_merge($wrapper_class, $wrapper_classes);
}


if (!empty($classes)) {
	$classes = implode(' ', $classes);
}
else {
	$classes = 'field-envelope';
}
if (empty($wrapper)) {
	$wrapper = 'p';
}
if (empty($field_wrapper)) {
	$field_wrapper = 'span';
}
if (empty($required)) {
	$required = '';
}
?>

<<?php print $wrapper; ?> class="<?php print implode(' ', $wrapper_class);?>" data-target-id="<?php print $id; ?>">
<?php if ($wrapper != 'td'): ?>
	<label for="<?php print $id; ?>"><?php print $label; ?>: </label>
<?php endif; ?>
<<?php print $field_wrapper; ?> class="<?php print $classes; ?>" id="<?php print $id; ?> <?php print $required; ?>"><?php print $value; ?></<?php print $field_wrapper; ?>>
</<?php print $wrapper; ?>>
