<?php
defined('BASEPATH') or exit('No direct script access allowed');

// general_helper.php Chris Dart Dec 10, 2013 9:54:14 PM
// chrisdart@cerebratorium.com
function bake_cookie ($name, $value)
{
    set_cookie(array(
            "name" => $name,
            "value" => $value,
            "expire" => 0
    ));
}

function burn_cookie ($name)
{
    set_cookie(array(
            "name" => $name,
            "value" => "",
            "expire" => NULL
    ));
}

/*
 * @function format_date @params $date date string @params $format string
 * description: this shouldn't be in this file, but I didn't want to create a
 * new file with general formatting tools yet.
 */
function format_date ($date, $format = "standard")
{
    if ($date) {
        // $format=mysql//yyyy-mm-dd
        // $format=standard//mm/dd/yyyy
        $date = str_replace("/", "-", $date);
        switch ($format) {
            case "mysql":
                $parts = explode("-", $date);
                $month = $parts[0];
                $day = $parts[1];
                $year = $parts[2];
                $date = "$year-$month-$day";
                break;
            case "standard":
                $parts = explode("-", $date);
                $year = $parts[0];
                $month = $parts[1];
                $day = $parts[2];
                $date = "$month/$day/$year";
                break;
            case "no-year":
                $parts = explode("-", $date);
                $year = $parts[0];
                $month = $parts[1];
                $day = $parts[2];
                $date = "$month/$day";
                break;
            default:
                $date = $date;
        }
    }
    return $date;
}

function prepare_variables ($object, $variables)
{
    for ($i = 0; $i < count($variables); $i ++) {
        $myVariable = $variables[$i];
        if ($object->input->post($myVariable)) {
            $object->$myVariable = $object->input->post($myVariable);
        }
    }
}

/*
 * @params $table varchar table name @params $data array consisting of "where"
 * string or array, and "select" comma-delimited string @returns an array of
 * key-value pairs reflecting a Database primary key and human-meaningful string
 */
function get_keyed_pairs ($list, $pairs, $initialBlank = NULL, $other = NULL, $alternate = array())
{
    $output = false;
    if ($initialBlank) {
        $output[] = "";
    }
    if (! empty($alternate)) {
        $output[$alternate['name']] = $alternate['value'];
    }

    foreach ($list as $item) {
        $key_name = $pairs[0];
        $key_value = $pairs[1];
        $output[$item->$key_name] = $item->$key_value;
    }
    if ($other) {
        $output["other"] = "Other...";
    }
    return $output;
}

function get_value ($object, $item, $default = null)
{
    $output = $default;

    if ($default) {
        $output = $default;
    }
    if ($object) {

        $var_list = get_object_vars($object);
        $var_keys = array_keys($var_list);
        if (in_array($item, $var_keys)) {
            $output = $object->$item;
        }
    }
    return $output;
}

function format_money ($int, $format = "standard")
{
    $output = 0;

    if ($int != "") {
        if ($format == "int") {
            $output = str_replace("\$", "", $int);
        } else {
            $parts = explode(".", $int);
            if (count($parts) == 2) {
                $output = sprintf("$%s", $int);
            } else {
                $output = sprintf("$%s.00", $int);
            }
        }
    }
    return $output;
}

function format_email ($email)
{
    $output = "";
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $output = sprintf("<a href='mailto:%s'>%s</a>", $email, $email);
    }
    return $output;
}

function format_field_name($field)
{
    $field = str_replace("_", " ", $field);
    $field = ucwords($field);
return $field;
}