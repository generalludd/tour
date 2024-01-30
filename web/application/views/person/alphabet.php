<?php defined('BASEPATH') OR exit('No direct script access allowed');

// alphabet.php Chris Dart Jan 1, 2014 8:05:15 PM chrisdart@cerebratorium.com
$previous_letter = NULL;
$buttons = [];
if(!empty($initials)) {
	foreach ($initials as $initial) {
		$letter = $initial->initial;
		$active = '';
		if ($letter != $previous_letter) {
			if ($this->input->get('initial') == $letter) {
				$active = 'active';
			}

			$buttons[] = [
				'text' => $letter,
				'href' => site_url('person?initial='. $letter),
				'class' => 'button letter $active'
			];
		}
		$previous_letter = $letter;
	}

	print create_button_bar($buttons);

}
