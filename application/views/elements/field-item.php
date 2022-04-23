<?php
if (empty($id)) {
	return FALSE;
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

<<?php print $wrapper; ?> class="field-envelope" data-target-id="<?php print $id; ?>">
<?php if ($wrapper != 'td'): ?>
	<label for="<?php print $id; ?>"><?php print $label; ?></label>
<?php endif; ?>
<<?php print $field_wrapper; ?> class="<?php print $classes; ?>" id="<?php print $id; ?> <?php print $required; ?>"><?php print $value; ?></<?php print $field_wrapper; ?>>
</<?php print $wrapper; ?>>
