<?php

defined('BASEPATH') or exit('No direct script access allowed');

// tours_list.php Chris Dart Dec 25, 2013 8:07:04 PM chrisdart@cerebratorium.com
$tours["for_tourist"] = TRUE;
$buttons[] = array("text"=>"Person Details","href"=>site_url("person/view/$tourist->person_id"));
$buttons[] = array("text"=>"Join Tour", "type"=>"span","class"=>"button new select-tour", "id"=>sprintf("join-tour_%s",$tourist->person_id));
?>
<h3>Tour List for <?=sprintf("%s %s", $tourist->first_name, $tourist->last_name);?></h3>
<?=create_button_bar($buttons);?>
<? $this->load->view("tour/list", $tours);?>