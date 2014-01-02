<?php defined('BASEPATH') OR exit('No direct script access allowed');

// alphabet.php Chris Dart Jan 1, 2014 8:05:15 PM chrisdart@cerebratorium.com

foreach($initials as $letter){
    $active = "";
    if($this->input->get("letter") == $letter->initial){
        $active = "active";
    }
$buttons[] = array("text"=>ucfirst($letter->initial),"href"=>site_url("person?letter=$letter->initial"),"class"=>"button letter $active");
}

print create_button_bar($buttons);