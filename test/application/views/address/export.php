<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
if(!empty($addresses)) {
	// export.php Chris Dart Jan 18, 2014 3:12:18 PM chrisdart@cerebratorium.com
	$date_stamp = date("Y-m-d_H-i-s");

	$file_name = sprintf("people_%s.csv", $date_stamp);
	$output = [
		"Formal Salutation, Address, City, State, Zip"
	];
	foreach ($addresses as $address) {
		$line [] = $address->formal_salutation;
		$line [] = $address->address;
		$line [] = $address->city;
		$line [] = $address->state;
		$line [] = $address->zip;
		$output [] = sprintf("\"%s\"", implode("\",\"", $line));
		$line = NULL;
	}

	$data = implode("\n", $output);
	force_download($file_name, $data);
}
