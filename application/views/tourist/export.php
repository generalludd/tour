<?php

defined('BASEPATH') or exit('No direct script access allowed');

// export.php Chris Dart Jan 18, 2014 6:33:46 PM chrisdart@cerebratorium.com
$total_due = 0;
$total_paid = 0;
$total_payers = 0;
$total_tourists = 0;
$date_stamp = date("Y-m-d_H-i");
$file_name = sprintf("tourists_%s.csv",$date_stamp);
$output = array(
        "Payer,Tourists, Payment Type, Price, Discount, Room Size, Room Rate, Amount Paid, Amount Due, Address, City, State, Zip"
);
foreach ($payers as $payer) {
	if($payer->is_cancelled == 0){
    $line["payer"] = sprintf("%s %s", $payer->first_name, $payer->last_name);
    $tourist_list = array();
    foreach ($payer->tourists as $tourist) {
        if ($tourist->person_id != $payer->payer_id) {
            $tourist_list[] = sprintf("%s %s", $tourist->first_name, $tourist->last_name);
        }
    }
    $line["tourists"] = implode(",", $tourist_list);

    //declare these keys in order so that later updates will be in the proper order for exporting
    $line["payment_type"] = "";
    $line["price"] = 0;
    $line["discount"] = 0;
    $line["room_size"] = "";
    $line["room_rate"] = 0;
    $line["amt_paid"] =0;
    $line["amt_due"] = 0;

    if ($payer->is_comp == 1) {
        $line["payment_type"] = "Complementary";
        $line["room_size"] = format_field_name($payer->room_size);
        $line["amt_paid"] = $payer->amt_paid;

    } elseif ($payer->is_cancelled == 1) {
        $line["payment_type"] = "Cancelled";
    } else {
        $line["payment_type"] = format_field_name($payer->payment_type);
        $line["price"] = $payer->price;
        $line["discount"] = $payer->discount;
        $line["room_size"] = format_field_name($payer->room_size);
        $line["room_rate"] = $payer->room_rate;
        $line["amt_paid"] = $payer->amt_paid;
        $line["amt_due"] = $payer->amt_due;
    }
    $line["address"] = $payer->address;
    $line["city"] = $payer->city;
    $line["state"] = $payer->state;
    $line["zip"] = $payer->zip;
    $output[] = sprintf("\"%s\"", implode("\",\"", $line));
    $line = NULL;
}
}

$data = implode("\n", $output);
force_download($file_name, $data);

