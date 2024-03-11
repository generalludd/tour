<?php
if (empty($id)){
	die('Missing ID');
}
if(empty($wrapper)){
	$wrapper = 'div';
}
$wrapper_class = ['input-block'];
if(!empty($wrapper_classes)){
	$wrapper_class = array_merge($wrapper_classes, $wrapper_class);
}
if(empty($attributes)){
	die('Missing attributes');
}
if(empty($attributes['type'])){
	$attributes['type'] = 'text';
}

if(empty($label)){
	$label = ucwords(str_replace('_',' ', $id));
}
$attribute_values = [];
foreach($attributes as $key=>$value){
	$attribute_values[] = $key .'="'. $value. '"';
}
?>
<?php if($attributes['type'] != 'hidden'): ?>
	<<?php print $wrapper;?> class="<?php  print implode(' ', $wrapper_class); ?>">
<label for="<?php print $id; ?>>">
	<?php print $label; ?>
</label>
<?php endif; ?>
<div class="input">
<?php print !empty($prefix) ? '<div class="input">' . $prefix : ''; ?>
<input name="<?php print $id; ?>" id="<?php print $id; ?>" <?php print implode(' ', $attribute_values);?>/>
<?php print !empty($suffix) ? $suffix . '</div>' : ''; ?>
<?php if($attributes['type'] != 'hidden'):?>
</<?php print $wrapper;?>>
<?php endif;?>

