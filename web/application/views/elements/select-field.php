<?php

if(empty($id)){
	exit('Error: missing ID');
}
if(empty($options)){
	exit('Missing options');
}
if(empty($selected)){
	$selected = '';
}
$base_classes = [];
if (empty($classes)) {
	$classes = $base_classes;
}
$classes = array_merge($classes, $base_classes);
$base_attributes = ['name' => $id, 'id' => $id];
if (empty($attributes)) {
$attributes = [];
}
$attributes = array_merge($base_attributes, $attributes);
$attributes_string = '';
foreach ($attributes as $key => $attribute) {
	$attributes_string .= $key . '="' . $attribute . '" ';
}
if (empty($wrapper)) {
	$wrapper = 'div';
}


?>
<<?php print $wrapper; ?>>
<?php if(!empty($label)):?>
<label for="<?php print $id; ?>>">
	<?php print $label; ?>
</label>
<?php endif; ?>
<select <?php print $attributes_string; ?>
		class="<?php print implode(' ', $classes); ?>">
	<?php foreach ($options as $key => $value): ?>
	  <option value="<?php print $key; ?>" <?php if($selected === $key):?>selected<?php endif;?>><?php print $value;?></option>
	<?php endforeach; ?>
</select>
<?php if(!empty($suffix)):?>
<?php print $suffix; ?>
<?php endif; ?>
</<?php print $wrapper; ?>>
