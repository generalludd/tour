<?php

defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 6:21:25 PM chrisdart@cerebratorium.com

$output = array();
foreach ($phones as $phone) {
    $output[] = sprintf(
            "<div class='phone-row grouping field-set row' id='phone'>%s<span class='button delete small phone-delete'>Delete</span></div>",
            create_edit_field("phone",$phone->phone,ucfirst($phone->phone_type),array("envelope"=>"div","id"=>$phone->id)) );
}
print implode("\r", $output);