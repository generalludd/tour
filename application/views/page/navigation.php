<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

$buttons [] = array (
		"selection" => "index",
		"text" => "Home",
		"class" => array (
				"button" 
		),
		"href" => site_url ( "" ),
		"title" => "Home" 
);
$search_data = [
	'field_name' => 'person-search',
	'placeholder' => 'Find People',
	'data' => [
		'url' => base_url('person/find_by_name'),
		'target' => '#search-list'
	]
];
$search_field = $this->load->view('person/search-field', $search_data, TRUE);
$buttons [] = array (
		"selection" => "search",
		"text" => $search_field,
		"type" => "pass-through" 
);

$buttons [] = array (
		"selection" => "tour",
		"text" => "Tours",
		"class" => array (
				"button",
				"show-tours" 
		),
		"href" => site_url ( "tour" ),
		"title" => "View the list of all tours" 
);

$buttons [] = array (
		"selection" => "person",
		"text" => "People",
		"class" => array (
				"button",
				"show-people" 
		),
		"href" => site_url ( "person" ),
		"title" => "View a List of All People" 
);

print create_button_bar($buttons,['id'=>'navigation-buttons'] );
