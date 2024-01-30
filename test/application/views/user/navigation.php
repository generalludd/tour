<?php
$buttons = array();

$buttons[] = array("selection"=>"user/view","text"=>"Account Info","href"=>site_url("user/view/$user_id"));

$options["selection"] = $this->uri->segment(1);
$options["id"] = "user-buttons";
$button_bar = create_button_bar($buttons, $options);

print $button_bar;
