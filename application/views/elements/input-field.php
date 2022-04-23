<?php
if (empty($id)){
	die('Missing ID');
}
if(empty($wrapper)){
	$wrapper = 'p';
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

<<?php print $wrapper;?> class="input-block">
<label for="<?php print $id; ?>>">
	<?php print $label; ?>
</label>
<input name="<?php print $id; ?>" id="<?php print $id; ?>" <?php print implode(' ', $attribute_values);?>/>
</<?php print $wrapper;?>>
