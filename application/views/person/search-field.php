<?php
if(!empty($data)):
$data_values = [];
foreach($data as $key => $datum){
	$data_values['data-' . $key] = 'data-' . $key.'="'. $datum . '"';
}
if(empty($title)){
	$title = 'Enter start typing to find people.';
}
?>
<input type="text" title="<?php print $title;?>" <?php print implode(' ', $data_values); ?> id="<?php print $field_name; ?>" value="" class="person-search" placeholder="<?php print $placeholder; ?>"/>
<?php endif;
