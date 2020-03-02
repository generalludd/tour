<?php
if(isset($person)):?>
BEGIN:VCARD
VERSION:4.0
N;<?php print $person->last_name; ?>;<?php print $person->last_name;?>;;
FN;<?php print $person->first_name . ' ' . $person->last_name;?>;
<?php foreach ($person->phones as $phone):?>
TEL;TYPE=<?php print $phone->phone_type; ?>,voice;VALUE=uri:tel:+1-<?php print $phone->phone; ?>
<?php endforeach; ?>
ADR;TYPE=HOME;LABEL="<?php print format_address($person->address,'vcard'); ?>":;;<?php print $person->address->address; ?>;<?php print $person->address->city; ?>;<?php print $person->address->state; ?>;<?php print $person->address->zip; ?>;United States of America
EMAIL;<?php print $person->email; ?>

END:VCARD
<?php endif;

