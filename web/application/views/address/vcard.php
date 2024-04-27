<?php
if (isset($person)):
	$notes = [];
	if (!empty($person->note)):
		$person_note = str_replace([";", ","], ["\;", "\,"], $person->note); // Escape commas and semicolons
		$notes[] = str_replace("\n", "\\n", $person_note); // Replace newlines with \n
	endif;
	if (!empty($person->shirt_size)):
		$notes[] = "Shirt size: " . $person->shirt_size;
	endif;
	if (!empty($person->is_veteran)):
		$notes[] =  "BPT Veteran";
	endif;
		?>
BEGIN:VCARD

VERSION:3.0

FN;CHARSET=UTF-8:<?php print $person->first_name . ' ' . $person->last_name; ?>

N;CHARSET=UTF-8:<?php print $person->last_name; ?>;<?php print $person->first_name; ?>;;;

EMAIL;CHARSET=UTF-8;type=HOME,INTERNET:<?php print $person->email; ?>

<?php foreach ($person->phones as $phone): ?>
TEL;TYPE=<?php print strtoupper($phone->phone_type); ?>:<?php print $phone->phone; ?>

<?php endforeach; ?>

ADR;CHARSET=UTF-8;TYPE=HOME:;;<?php print $person->address->address; ?>;<?php print $person->address->city; ?>;<?php print $person->address->state; ?>;<?php print $person->address->zip; ?>;USA

<?php if (!empty($notes)): ?>
<?php array_unshift($notes, "Notes from db.ballparktours.net:"); ?>
NOTE:<?php print implode('\\n', $notes); ?>

<?php endif; ?>
END:VCARD
<?php endif;

