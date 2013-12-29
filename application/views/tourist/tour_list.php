<?php

defined('BASEPATH') or exit('No direct script access allowed');

// tours_list.php Chris Dart Dec 25, 2013 8:07:04 PM chrisdart@cerebratorium.com
$tours["for_tourist"] = TRUE;

?>
<h3>Tour List for <?=sprintf("%s %s", $tourist->first_name, $tourist->last_name);?></h3>
<? $this->load->view("tour/list", $tours);


