<?php defined('BASEPATH') OR exit('No direct script access allowed');

// alphabet.php Chris Dart Jan 1, 2014 8:05:15 PM chrisdart@cerebratorium.com
$last_initial = NULL;
$buttons = [];
if(!empty($initials)) {
	foreach ($initials as $initial) {
		$active = "";
		if ($initial->initial != $last_initial) {
			if ($this->input->get("intial") == $initial->initial) {
				$active = "active";
			}

			$buttons[] = [
				"text" => ucfirst($initial->initial),
				"href" => site_url("person?initial=$initial->initial"),
				"class" => "button letter $active"
			];
		}
		$last_initial = $initial->initial;
	}

	print create_button_bar($buttons);

}
