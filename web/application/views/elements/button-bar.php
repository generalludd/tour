<?php
if(empty($data) || empty($data->buttons)){
	return FALSE;
}
if(empty($data->classes)){
	$data->classes = [];
}
	$data->classes[] = "button-bar";
if(empty($data->id)){
	$data->id = NULL;
}
else {
	$data->id = "id='$data->id'";
}
?>
<div class="<?php print implode(' ', $data->classes);?>" <?php print $data->id;?> >
	<div class="button-list">
		<?php foreach($data->buttons as $button):?>
			<div class="button-item">
				<?php print create_button($button); ?>
			</div>
		<?php endforeach;?>
	</div>
</div>
