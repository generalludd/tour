<?php
if(isset($person)):?>
BEGIN:VCARD

VERSION:3.0

FN;CHARSET=UTF-8:<?php print $person->first_name . ' ' . $person->last_name;?>

N;CHARSET=UTF-8:<?php print $person->last_name;?>;<?php print $person->first_name; ?>;;;

EMAIL;CHARSET=UTF-8;type=HOME,INTERNET:<?php print $person->email; ?>

<?php foreach ($person->phones as $phone):?>
TEL;TYPE=<?php print strtoupper($phone->phone_type); ?>,VOICE:<?php print $phone->phone; ?>

<?php endforeach; ?>
ADR;CHARSET=UTF-8;TYPE=HOME:;;<?php print $person->address->address; ?>;<?php print $person->address->city; ?>;<?php print $person->address->state; ?>;<?php print $person->address->zip; ?>;USA

END:VCARD
<?php endif;

