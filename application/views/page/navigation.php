<?php defined('BASEPATH') OR exit('No direct script access allowed');

 $buttons[] = array("selection" => "index",
"text" => "Home",
"class" => array("button"),
"href"=> site_url(""),
"title" => "Home");
 $buttons[] = array("selection" =>"search", "text" => '<input type="text" id="person_search" name="person_search" size="20" value="find people" />', "type" => "pass-through");

$buttons[] = array("selection" => "tour",
"text" => "Tours",
"class" => array("button","show-tours"),
"href"=> site_url("tour"),
"title" => "View the list of all tours");

$buttons[] = array("selection" => "person",
        "text" => "People",
        "class" => array("button","show-people"),
        "href"=> site_url("person"),
        "title" => "View a List of All People");
/*
$buttons[] = array("selection" => "all",
		"text" => "Search Common Names",
		"class" => array("button","search-common-names"),
		"type" => "span",
		"title" => "Search among the common names");

$buttons[] = array("selection" => "color",
"text" => "Color Listing",
"class" => array("button"),
"href" => site_url("color"),
"title" => "view the color listings",
);
*/
print create_button_bar($buttons, array("id" =>"navigation-buttons"));
