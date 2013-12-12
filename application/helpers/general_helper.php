<?php defined('BASEPATH') OR exit('No direct script access allowed');

// general_helper.php Chris Dart Dec 10, 2013 9:54:14 PM chrisdart@cerebratorium.com

function prepare_variables($object, $variables){
    for($i = 0; $i < count($variables); $i++){
        $myVariable = $variables[$i];
        if($object->input->post($myVariable)){
            $object->$myVariable = $object->input->post($myVariable);
        }
    }
}