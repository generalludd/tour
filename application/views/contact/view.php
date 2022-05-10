<?php
 if(empty($contact)){
	 return FALSE;
 }
 ?>

<div class="contact-info">
	<?php if(!empty($contact->contact)):?>
	<div class="name"><?php print $contact->contact; ?></div>
	<?php endif; ?>
	<?php if(!empty($contact->position)):?>
	<div class="position"><?php print $contact->position; ?></div>
	<?php endif; ?>
	<?php if(!empty($contact->phone)):?>
		<div class="phone"><?php print $contact->phone; ?></div>
	<?php endif; ?>
	<?php if(!empty($contact->fax)):?>
		<div class="fax"><?php print $contact->fax; ?></div>
	<?php endif; ?>
	<?php if(!empty($contact->email)):?>
		<div class="email"><?php print $contact->email; ?></div>
	<?php endif; ?>
	<?php print create_button([
		'text' => 'Edit Contact',
		'href' => base_url('contact/edit/' . $contact->id),
		'class' => 'button edit float-right small dialog',
	]); ?>
</div>
