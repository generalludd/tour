<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

// export.php Chris Dart Jan 18, 2014 3:12:18 PM chrisdart@cerebratorium.com
$date_stamp = date ( "Y-m-d_H-i" );

$file_name = sprintf ( "addresses_%s.csv", $date_stamp );
$output = array (
		"Formal Salutation, Informal Salutation, Address, City, State, Zip" 
);

foreach ( $addresses as $address )
{
	$line [] = $address->formal_salutation;
	$line [] = $address->informal_salutation;
	$line [] = $address->address;
	$line [] = $address->city;
	$line [] = $address->state;
	$line [] = $address->zip;
    $output[] = sprintf("\"%s\"", implode("\",\"", $line));
	$line = NULL;
}

$data =  implode ( "\n", $output );
force_download ( $file_name, $data );
