<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (empty($contact)) {
	return FALSE;
}

$buttons['submit'] = [
		'type' => 'pass-through',
		'text' => sprintf("<input type='submit' name='submit' class='button' value='%s'/>", ucfirst($action)),
];
if ($action == 'update') {
	$buttons['delete'] = [
			'text' => 'Delete',
			'type' => 'span',
			'class' => 'button delete delete-contact',
			'data' => [
					'id' => $contact->id,
					'hotel_id' => $contact->hotel_id,
			],
	];
}
?>
<form
		name="contact-editor"
		id="contact-editor"
		action="<?php print site_url('contact/' . $action); ?>"
		method="post">
	<input
			type="hidden"
			name="id"
			id="id"
			value="<?php print get_value($contact, 'id'); ?>"/>
	<input
			type="hidden"
			name="hotel_id"
			id="hotel_id"
			value="<?php print get_value($contact, 'hotel_id', $hotel_id); ?>"/>

	<?php $fields = [
			'contact' => [
					'id' => 'contact',
					'label' => 'Contact',
					'value' => get_value($contact, 'contact'),
					'size' => 25,
			],
			'position' => [
					'id' => 'position',
					'label' => 'Position',
					'value' => get_value($contact, 'position'),
					'size' => 25,
			],
			'phone' => [
					'id' => 'phone',
					'label' => 'Phone',
					'value' => get_value($contact, 'phone'),
					'type' => 'tel',
					'size' => 25,
			],
			'fax' => [
					'id' => 'fax',
					'label' => 'Fax',
					'value' => get_value($contact, 'fax'),
					'size' => 25,
					'type' => 'tel',
			],
			'email' => [
					'id' => 'email',
					'label' => 'Email',
					'value' => get_value($contact, 'email'),
					'size' => 25,
					'type' => 'email',
			],
	];

	foreach ($fields as $field) {
		$this->load->view('elements/input-field', $field);
	}
	?>
	<?php print create_button_bar($buttons); ?>
</form>
