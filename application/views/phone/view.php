<?php

defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 6:21:25 PM chrisdart@cerebratorium.com

$output = array(); ?>
<div id="phone" class="grouping phone-grouping">

<? foreach ($person->phones as $phone) {
    $output[] = sprintf(
            "<div class='phone-row field-set row' >%s<span class='button delete small delete-phone' id='delete-phone_%s'>Delete</span></div>",
            create_edit_field("phone",$phone->phone,ucfirst($phone->phone_type),array("envelope"=>"span","id"=>$phone->id)), $phone->id );
}
print implode("\r", $output); ?>
</div>