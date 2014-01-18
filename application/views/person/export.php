<?php defined('BASEPATH') OR exit('No direct script access allowed');

// export.php Chris Dart Jan 18, 2014 3:12:18 PM chrisdart@cerebratorium.com


$file_name="people.csv";
$output = array("First Name,Last Name,email, House Number, Street, Apartment, City, State, Zip");
foreach($people as $person) {
    $line[] = $person->first_name;
    $line[] = $person->last_name;
    $line[] = $person->email;
    $line[] = $person->num;
    $line[] = $person->street;
    $line[] = $person->unit;
    $line[] = $person->city;
    $line[] = $person->state;
    $line[] = $person->zip;
    $output[] = implode(",",$line);
    $line = NULL;
}

$data = implode("\n", $output);
force_download($file_name, $data);
