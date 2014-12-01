<?php defined('BASEPATH') OR exit('No direct script access allowed');

// alphabet.php Chris Dart Jan 1, 2014 8:05:15 PM chrisdart@cerebratorium.com

foreach($initials as $initial){
    $active = "";
    if($this->input->get("intial") == $initial->initial){
        $active = "active";
    }
$buttons[] = array("text"=>ucfirst($initial->initial),"href"=>site_url("person?initial=$initial->initial"),"class"=>"button letter $active");
}

print create_button_bar($buttons);

