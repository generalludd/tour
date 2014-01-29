<?php
defined('BASEPATH') or exit('No direct script access allowed');

// export.php Chris Dart Jan 18, 2014 3:12:18 PM chrisdart@cerebratorium.com
$file_name = "people.csv";
$output = array(
        "First Name,Last Name,email, Address, City, State, Zip"
);
$current_address = $people[0]->address_id;
$names = array();
foreach ($people as $person) {
    $line[] = $person->first_name;
    $line[] = $person->last_name;
    $line[] = $person->email;
    $line[] = $person->address;
    $line[] = $person->city;
    $line[] = $person->state;
    $line[] = $person->zip;
    $output[] = implode(",", $line);
    $line = NULL;
    $current_address = $person->address_id;
}

$data = implode("\n", $output);
force_download($file_name, $data);
