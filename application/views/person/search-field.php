<?php
if(!empty($data)):
$data_values = [];
foreach($data as $key => $datum){
	$data_values['data-' . $key] = 'data-' . $key.'="'. $datum . '"';
}
?>
<input type="text" <?php print implode(' ', $data_values); ?> id="<?php print $field_name; ?>" value="" class="person-search" placeholder="<?php print $placeholder; ?>"/>
<?php endif;
