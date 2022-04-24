<?php
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
			<?php foreach($fields  as $field):?>
	<?php $data = [
						'id' => $field,
						'wrapper' => 'div',
						'wrapper_classes' => ['horizontal'],
						'value' => get_value($contact, $field),
				];
	$this->load->view('elements/field-item',$data);
	?>

	<?php endforeach; ?>
		</div>
		<?php print create_button([
			"text" => "Edit Contact",
			"type" => "span",
			"class" => "button edit float-right small edit-contact",
			"id" => sprintf("edit-contact_%s", $contact->id),
		]); ?>
	</div>
<?php endforeach;
