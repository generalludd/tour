<?php

defined('BASEPATH') or exit('No direct script access allowed');

// message_helper.php Chris Dart Jan 14, 2014 8:44:39 PM
// chrisdart@cerebratorium.com
function replace_text ($text, $key, $value)
{
    $search = "/\b$key/";
    $text = preg_replace($search, $value, $text);
    return $text;
}

function format_salutation ($tourists)
{
	switch(count($tourists)){
		case 1:
		    $output = $tourists[0]->first_name;
		    break;
		case 2:
		    $output = sprintf("%s and %s", $tourists[0]->first_name, $tourists[1]->first_name);
		    break;
		case 3:
		    $output = sprintf("%s, %s, and %s", $tourists[0]->first_name, $tourists[1]->first_name, $tourists[2]->first_name);
		    break;
	}

    return $output;
}

