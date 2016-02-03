<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

// export.php Chris Dart Jan 18, 2014 3:12:18 PM chrisdart@cerebratorium.com
$date_stamp = date ( "Y-m-d_H-i-s" );

$file_name = sprintf ( "people_%s.csv", $date_stamp );
$output = array (
		"Formal Salutation, Informal Salutation, Address, City, State, Zip, Email" 
);
$current_address = FALSE;
foreach ( $addresses as $address ) {
	if ( $address->id != $current_address) {
		if (empty ( $address->formal_salutation )) {
			$line [] =  $address->first_name . " " .  $address->last_name;
		}
		else {
			$line [] = $address->formal_salutation;
		}
		
		if(empty($address->informal_salutation)){
			$line[] = $address->first_name;
		}else{
		$line [] = $address->informal_salutation;
		}
		$line [] = $address->address;
		$line [] = $address->city;
		$line [] = $address->state;
		$line [] = $address->zip;
		$line [] = $address->email;
		$output [] = sprintf ( "\"%s\"", implode ( "\",\"", $line ) );
		$line = NULL;
		$current_address = $address->id;
	}
}

$data = implode ( "\n", $output );
force_download ( $file_name, $data );
