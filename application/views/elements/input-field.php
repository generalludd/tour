<?php
if (empty($id)){
	die('Missing ID');
}
if(empty($wrapper)){
	$wrapper = 'p';
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
<input name="<?php print $id; ?>" id="<?php print $id; ?>" <?php print implode(' ', $attribute_values);?>/>
<?php if($attributes['type'] != 'hidden'):?>
</<?php print $wrapper;?>>
<?php endif;
