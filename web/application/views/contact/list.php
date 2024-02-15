<?php

if(empty($contacts)){
	return NULL;
}
$fields = [
		'contact',
		'position',
		'phone',
		'fax',
		'email',
];

foreach ($contacts as $contact): ?>
	<div class="contact-row row">
		<div class="contact-info">
			<?php foreach ($fields as $field): ?>
				<?php $data = [
						'id' => $field,
						'wrapper' => 'div',
						'wrapper_classes' => ['horizontal'],
						'value' => get_value($contact, $field),
				];
				$this->load->view('elements/field-item', $data);
				?>

			<?php endforeach; ?>
		</div>
		<?php $object = get_button_bar_object([
				[
						'text' => 'Edit Contact',
						'href' => base_url('contact/edit/' . $contact->id),
						'class' => 'button edit dialog',

				],
				[
						'text' => 'Delete',
						'class' => 'button delete dialog',
						'href' => base_url('contact/delete?contact_id=' . $contact->id . '&hotel_id=' . $contact->hotel_id),
				],
		]); ?>
		<?php $this->load->view('elements/button-bar', ['data' => $object]); ?>
	</div>
<?php endforeach;
