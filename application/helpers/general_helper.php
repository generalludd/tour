<?php
defined('BASEPATH') or exit('No direct script access allowed');

// general_helper.php Chris Dart Dec 10, 2013 9:54:14 PM
// chrisdart@cerebratorium.com
/**
 * @return false|string
 */
function mysql_timestamp() {
	return date('Y-m-d H:i:s');
}

/**
 * @param $name
 * @param $value
 *
 * @return void
 */
function bake_cookie($name, $value) {
	if (is_array($value)) {
		$value = serialize($value);
	}
	set_cookie([
		"name" => $name,
		"value" => $value,
		"expire" => 0,
	]);
}

/**
 * @param $name
 *
 * @return void
 */
function burn_cookie($name) {
	set_cookie([
		"name" => $name,
		"value" => "",
		"expire" => NULL,
	]);
}

/*
 * @function format_date @params $date date string @params $format string
 * description: this shouldn't be in this file, but I didn't want to create a
 * new file with general formatting tools yet.
 */
/**
 * @param $date
 * @param string $format
 *
 * @return false|mixed|string
 */
function format_date($date, string $format = "standard") {
	if ($date) {
		switch ($format) {
			case "mysql":
				$date = date('Y-m-d', strtotime($date));
				break;
			case "standard":
				$date = date('m/d/Y', strtotime($date));
				break;
			case "no-year":
				$date = date('m/d', strtotime($date));
				break;
		}
	}
	return $date;
}

/**
 * @param $timestamp
 * @param string $format
 *
 * @return false|string
 */
function format_timestamp($timestamp, string $format = "standard") {
	$output = '';
	switch ($format) {
		case "standard":
			$output = date('m/d/Y, g:i:s A', strtotime($timestamp));
			break;
		case "date-only":
			$output = date('m/d/Y', strtotime($timestamp));
			break;
		case "time-only":
			$output = date('g:i:s A', strtotime($timestamp));
			break;
	}
	return $output;
}

/**
 * ideally this will produce a cleaned up version of a time entry.
 * for now it just trims a time entry--which could be any string.
 *
 * @param string|null $time
 * @param string $format
 *
 * @return false|string|null
 */
function format_time(string $time = NULL, string $format = "standard") {
	if (!empty($time)) {
		return date('g:i A', strtotime(trim($time)));
	}
	else {
		return NULL;
	}
}

function format_datetime(string $date = NULL, string $time = NULL){
	$output = [];
	if(!empty($date)){
		$output[] = format_date($date);
	}
	if(!empty($time)){
		$output[] = format_time($time);
	}
	return implode(', ', $output);
}

/**
 * @param $object
 * @param $variables
 *
 * @return void
 */
function prepare_variables($object, $variables) {
	for ($i = 0; $i < count($variables); $i++) {
		$my_variable = trim($variables[$i]);
		if ($object->input->post($my_variable)) {
			$my_value = trim($object->input->post($my_variable));
			/*if (strpos($my_variable, "date")) {
				$my_value = trim(format_date($my_value, "mysql"));
			}*/
			if (strpos($my_variable, "price") || strpos($my_variable, "room") || strpos($my_variable, "rate")) {
				$my_value = trim(format_money($my_value, "int"));
			}
			if (strpos("time", $my_variable)) {
				$my_value = trim(format_time($my_value));
			}
			$object->$my_variable = trim($my_value);
		}
	}
}

/*
 * @params $table varchar table name @params $data array consisting of "where"
 * string or array, and "select" comma-delimited string @returns an array of
 * key-value pairs reflecting a Database primary key and human-meaningful string
 */
/**
 * @param $list
 * @param $pairs
 * @param null $initial_blank
 * @param null $other
 * @param array $alternate
 *
 * @return array
 */
function get_keyed_pairs($list, $pairs, $initial_blank = NULL, $other = NULL, array $alternate = []): array {
	$output = [];
	if ($initial_blank) {
		$output = ['' => NULL];
	}
	if (!empty($alternate)) {
		$output[$alternate['name']] = $alternate['value'];
	}

	foreach ($list as $item) {
		$key_name = $pairs[0];
		$key_value = $pairs[1];
		$output[$item->{$key_name}] = $item->{$key_value};
	}
	if ($other) {
		$output['other'] = 'Other...';
	}
	return $output;
}

/**
 * @param $object
 * @param $item
 * @param $default
 *
 * @return mixed|null
 */
function get_value($object, $item, $default = NULL) {
	$output = $default;
	if ($object instanceof stdClass && !empty($object->{$item})) {
		$output = $object->{$item};
	}
	return $output;
}

/**
 * Accepts an integer and a value (either "standard" or "int") depending on
 * whether the desired output is currency with $ or a number stripped of $ and
 * extras.
 *
 * @param float|null $int $int
 * @param string $format
 *
 * @return string
 */
function format_money(float $int = NULL, string $format = "standard"): string {
	if ($format == 'int') {
		$int = round($int, 0);
	}
	$fmt =  NumberFormatter::create('en_US', \NumberFormatter::CURRENCY);
	return !empty($int)? $fmt->formatCurrency($int, 'USD'): '';
}

/**
 * @param $email
 *
 * @return string
 */
function format_email($email) {
	$output = "";
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$output = sprintf("<a href='mailto:%s'>%s</a>", $email, $email);
	}
	return $output;
}

/**
 * @param $field
 *
 * @return string
 */
function format_field_name($field) {
	$field = str_replace("_", " ", $field);
	$field = ucwords($field);
	return $field;
}

/**
 *
 * @param object $address
 * @param string $format $format should be "postal" which is the address on two
 *        lines, or
 *        inline
 */
function format_address($address, $format = "postal") {
	$output = NULL;
	$street = $address->address;
	$locality = sprintf("%s, %s %s", $address->city, $address->state, $address->zip);
	if ($format == "postal") {
		$output = sprintf("%s<br/>%s", $street, $locality);
	}
	elseif ($format == "inline") {
		$output = sprintf("%s, %s", $street, $locality);
	}
	elseif ($format = 'vcard') {
		$output = sprintf('%s\n%s', $street, $locality);
	}
	return $output;
}

/**
 * given any list of numbers, find the first opening in the list.
 * BUG: only works if only one number is missing from the list;
 *
 * @param array of objects $list
 * @param object value $field
 *
 * @return number while statement and algorithm from
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *         http://stackoverflow.com/questions/4163164/find-missing-numbers-in-array
 */
function get_first_missing_number($list, $field) {
	$item_array = [];
	foreach ($list as $item) {
		$item_array[] = $item->$field;
	}
	$full_array = [
		1,
	];
	$output = 1;
	if (!empty($item_array)) {
		$full_array = range(1, max($item_array));
	}


	foreach ($item_array as $key => $value) {
		if ($key != ($value - 1)) {
			$output = $full_array[$key];
		}
		else {
			$output = max($item_array) + 1;
		}
	}

	return $output;
}

/**
 * @param $price
 * @param $rate
 * @param $ticket_count
 * @param $discount
 * @param $amt_paid
 *
 * @return float|int
 */
function get_amt_due($price, $rate, $ticket_count, $discount, $amt_paid) {
	$total = $price * $ticket_count + ($rate - $discount - $amt_paid);
	return $total;
}

/**
 * @param $payer
 *
 * @return int|mixed
 */
function get_tour_price($payer) {
	if ($payer->is_comp == 1) {
		$tour_price = 0;
	}
	else {
		switch ($payer->payment_type) {
			case "full_price":
				$tour_price = $payer->full_price;
				break;
			case "banquet_price":
				$tour_price = $payer->banquet_price;
				break;
			case "early_price":
				$tour_price = $payer->early_price;
				break;
			case "regular_price":
				$tour_price = $payer->regular_price;
				break;
			default:
				$tour_price = 0;
				break;
		}
	}
	return $tour_price;
}

/**
 * @param $payer
 *
 * @return int|mixed
 */
function get_room_rate($payer) {
	switch ($payer->room_size) {
		case "single_room":
			$room_rate = $payer->single_room;
			break;
		case "triple_room":
			$room_rate = $payer->triple_room;
			break;
		case "quad_room":
			$room_rate = $payer->quad_room;
			break;
		default:
			$room_rate = 0;
			break;
	}
	if ($payer->is_comp == 1) {
		$room_rate = 0;
	}
	return $room_rate;
}

/**
 * @param $contact
 *
 * @return string
 */
function format_contact($contact) {
	$name = [];
	$contact_info = [];

	if (array_key_exists("name", $contact) && $contact["name"]) {
		$name[] = $contact["name"];
	}
	if (array_key_exists("position", $contact) && $contact["position"]) {
		$name[] = $contact["position"];
	}

	if (array_key_exists("phone", $contact) && $contact["phone"]) {
		$contact_info[] = sprintf("Phone: %s", $contact["phone"]);
	}
	if (array_key_exists("email", $contact) && $contact["email"]) {
		$contact_info[] = sprintf("<a href='mailto:%s'>%s</a>", $contact["email"], $contact["email"]);
	}
	$output = sprintf("%s<br/>%s", implode(", ", $name), implode(", ", $contact_info));

	return $output;
}

/**
 *
 * @param string $glue
 * @param array $list
 * @param string $conjunction creates a list in proper English list format
 *        (lists less than 3 have no comma, list with 3 or more have commas and
 *        final conjunction)
 */
function grammatical_implode($glue, $list, $conjunction = "and") {
	$adjusted_list = [];
	$output = $list;
	if (is_array($list)) {
		if (count($list) == 1) {
			$output = implode("", $list);
		}
		elseif (count($list) == 2) {
			$output = implode(" $conjunction ", $list);
		}
		else {
			for ($i = 0; $i < count($list); $i++) {
				$prefix = "";
				if ($i + 1 == count($list)) {
					$prefix = $conjunction;
				}
				$adjusted_list[] = $prefix . " " . $list[$i];
			}
			$output = implode($glue, $adjusted_list);
		}
	}
	return $output;
}

function get_payment_due($payer){
		$tourist_count = count($payer->tourists);

		return $payer->price * $tourist_count + ($payer->room_rate - $payer->amt_paid - $payer->discount);
}

/**
 * @param $people
 * @param $format
 *
 * @return array|string
 */
function format_salutation($people, $format = "informal") {
	$first_names = [];
	$last_names = [];
	$names = [];
	$current_name = "";
	foreach ($people as $person) {

		$first_names[] = $person->first_name;
		if (!in_array($current_name, $last_names)) {
			$last_names[] = $person->last_name;
			$current_name = $person->last_name;
		}
		$names[] = sprintf("%s %s", $person->first_name, $person->last_name);
	}
	if ($format == "informal") {
		$output = grammatical_implode(", ", $first_names);
	}
	else {
		switch (count($first_names) - count($last_names)) {
			case 0:
				$output = grammatical_implode(", ", $names);
				break;
			case 1:
				$firsts = grammatical_implode(", ", $first_names);
				$last = implode("", $last_names);
				$output = sprintf("%s %s", $firsts, $last);
				break;
			case 2:
			default:
				$output = grammatical_implode(", ", $names);
		}
	}
	return $output;
}

/**
 * custopm sort an array by key values
 *
 * @param array $array array to be sorted
 * @param array $order array representing the order of the list
 *
 * @return array
 */
function array_custom_sort(array $array, array $order): array {
	foreach ($array as $key => $value) {
		$new_array[$key] = [
			'rank' => array_search($key, $order),
			'key' => $key,
			'value' => $value,
		];
	}
	array_multisort($new_array);
	return $new_array;
}

/**
 * @param string $room_size
 *
 * @return int
 */
function get_room_size(string $room_size): int {
	switch ($room_size) {
		case "single_room":
			$size = 1;
			break;
		case "triple_room":
			$size = 3;
			break;
		case "quad_room":
			$size = 4;
			break;
		case "double_room":
		default:
			$size = 2;
			break;
	}
	return $size;
}

/**
 * @param string $path
 * @param string $text
 * @param array $options
 *
 * @return string
 */
function create_link(string $path, string $text, array $options = []): string {
	$values = [
		'href="' . base_url($path) . '"',
	];
	if ($options) {
		foreach ($options as $key => $value) {
			$values[] = $key . '="' . $value . '"';
		}
	}

	return '<a ' . implode(' ', $values) . '>' . $text . '</a>';
}

/**
 * Link to the person based on an object that has an attribute $id that
 *   represents a person.id.
 *
 * @param \stdClass $person
 *  An object that must contain an attribute that points to a person entity.
 * @param string $id
 *  This is the attribute of $person that relates to a person entity.
 *
 * @return string
 */
function person_link(stdClass $person, string $id = 'id'): string {
	$text = $person->first_name . ' ' . $person->last_name;
	$title = sprintf('View %s\'s record', $text);
	$options = [
		'title' => $title,
	];
	$path = 'person/view/' . $person->{$id};
	return create_link($path, $text, $options);
}
