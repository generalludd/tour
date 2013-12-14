<?php defined('BASEPATH') OR exit('No direct script access allowed');

// general_helper.php Chris Dart Dec 10, 2013 9:54:14 PM chrisdart@cerebratorium.com


function bake_cookie($name,$value){
    set_cookie(array("name"=>$name,"value"=>$value,"expire"=>0));
}

function burn_cookie($name){
    set_cookie(array("name"=>$name,"value"=>"","expire"=>NULL));
}

function create_input($object, $name, $label, $id=NULL)
{
    if(!$id){
        $id = $name;
    }
    return sprintf("<label for='%s'>%s: </label><input type='text' name='%s' id='%s' value='%s'/>",$name,$label,$name,$id,get_value($object,$name));

}


function prepare_variables($object, $variables){
    for($i = 0; $i < count($variables); $i++){
        $myVariable = $variables[$i];
        if($object->input->post($myVariable)){
            $object->$myVariable = $object->input->post($myVariable);
        }
    }
}

function get_current_year()
{
    if(date("m") > 7){ //after July
        $year = date("Y") + 1;
    }else{
        $year = date("Y");
    }
    return $year;
}

/*
 * @params $table varchar table name
* @params $data array consisting of "where" string or array, and "select" comma-delimited string
* @returns an array of key-value pairs reflecting a Database primary key and human-meaningful string
*/
function get_keyed_pairs($list,$pairs,$initialBlank = NULL,$other = NULL,$alternate = array()){
    $output=false;
    if($initialBlank){
        $output[] = "";
    }
    if(!empty($alternate)){
        $output[$alternate['name']] = $alternate['value'];
    }

    foreach($list as $item){
        $key_name = $pairs[0];
        $key_value = $pairs[1];
        $output[$item->$key_name] = $item->$key_value;
    }
    if($other){
        $output["other"] = "Other...";
    }
    return $output;

}


function get_value($object, $item, $default = null){
    $output = $default;

    if($default){
        $output = $default;
    }
    if($object){

        $var_list = get_object_vars($object);
        $var_keys = array_keys($var_list);
        if (in_array($item, $var_keys) ){
            $output = $object->$item;
        }
    }
    return $output;
}

function get_as_price($int){
    $parts = explode(".",$int);
    if(count($parts) == 2){
        $output = sprintf("$%s", $int);
    }else{
        $output = sprintf("$%s.00", $int);
    }
    return $output;
}

