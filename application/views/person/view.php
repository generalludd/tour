<?php
defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 11, 2013 7:47:55 PM chrisdart@cerebratorium.com
$buttons[] = [
		"text" => "Edit Person",
		'title' => 'Edit ' . $person->first_name . '&rsquo;s Record',
		"href" => site_url('person/edit/' . $person->id),
		"class" => 'button edit dialog',
];
$buttons[] = [
		"text" => "Join Tour",
		"href" => base_url('tour/show_current/' . $person->id),
		"class" => "button new mini select-tour",
		"id" => sprintf("join-tour_%s", $person->id),
];

$buttons[] = [
		"text" => sprintf("Tour History", $person->first_name),
		"href" => site_url("/tourist/view_for_tourist/$person->id"),
		"class" => "button show-tours-for-tourist",
];
$buttons[] = [
		'text' => 'Export vCard',
		'title' => sprintf('Export a universal address book card for ', $person->first_name),
		'href' => base_url('person/vcard/' . $person->id),
		'class' => 'button export',
];
if (get_value($person, "id", FALSE) && $tour_count == 0) {
	$buttons[] = [
			'text' => 'Delete',
			'href' => base_url('person/delete'),
			'data' => [
					'id' => $person->id,
					'redirect' => 'person/view_all',
			],
			'class' => ['button', 'delete', 'delete-action'],
	];
}
else {
	$buttons[] = [
			'text' => 'Disable',
			'title' => 'This person has been on tours so they cannot be deleted.',
			'href' => base_url('person/disable'),
			'data' => [
					'id' => $person->id,
					'redirect' =>'person/view/' . $person->id,
			],
			'class' => ['button', 'delete', 'delete-action'],
	];
}


$phone_button[] = [
		"text" => "Add Phone",
		"class" => "button small new add-phone",
		'href' => base_url('phone/create/' . $person->id),
];
$restore_button[] = [
		"text" => "Restore Record",
		"type" => "span",
		"class" => "button new restore-person",
		"id" => sprintf("restore-person_%s", get_value($person, "id")),
];
?>
<h3> <?php print sprintf("%s %s", $person->first_name, $person->last_name); ?></h3>

<div class="content diptych">
	<fieldset
			class="person-info"
			id="person">
		<?php if (get_value($person, "status") == 0): ?>
			<div class="notice">
				This person's record has been disabled which means you deleted it at
				some point, but, because they were on at least one tour, they could not
				be permanently deleted from the database.<br/>
				<?php print create_button_bar($restore_button); ?>
			</div>

		<?php endif; ?>
		<?php print create_button_bar($buttons); ?>

<div class="diptych">
	<fieldset
			class=" block person-info"
			id="person">
		<input
				type="hidden"
				id="id"
				name="id"
				value="<?php print get_value($person, "id", $id); ?>"/> <input
				type="hidden"
				id="address_id"
				name="address_id"
				value="<?php print get_value($person, "address_id"); ?>"/>
		<?php $this->load->view('elements/field-item', [
				'id' => 'first_name',
				'value' => get_value($person, 'first_name'),
				'wrapper' => 'div',
				'wrapper_classes' => ['field-set'],
		]);
		?>
		<?php $this->load->view('elements/field-item', [
				'id' => 'last_name',
				'value' => get_value($person, 'last_name'),
				'wrapper' => 'div',
				'wrapper_classes' => ['field-set'],
		]);
		?>
		<?php $this->load->view('elements/field-item', [
				'id' => 'email',
				'value' => get_value($person, 'email'),
				'type' => 'email',
				'wrapper' => 'div',
				'wrapper_classes' => ['field-set'],
		]); ?>
		<?php $this->load->view('elements/field-item', [
				'id' => 'shirt_size',
				'value' => get_value($person, 'shirt_size'),
				'wrapper' => 'div',
				'wrapper_classes' => ['field-set'],
		]);
		?>
		<?php $this->load->view('elements/field-item', [
				'id' => 'note',
				'value' => get_value($person, 'note'),
				'wrapper' => 'div',
				'wrapper_classes' => ['field-set'],
		]);
		?>
		<?php $this->load->view('elements/field-item', [
				'id' => 'is_veteran',
				'value' => !empty($person->is_veteran) ? 'Yes' : 'No',
				'wrapper' => 'div',
				'wrapper_classes' => ['field-set'],
		]);
		?>
		<div id="phone" class="grouping phone-grouping">
			<?php if (get_value($person, "phones", FALSE)) : ?>
				<p>
					<label>Phones</label>
				</p>
				<?php $this->load->view("phone/view", $person->phones); ?>
			<?php endif; ?>
			<?php print create_button_bar($phone_button); ?>
		</div>
	</fieldset>
	<fieldset
			class="block address-info"
			id="address">
		<?php $this->load->view('address/view', ['person' => $person]); ?>
	</fieldset>
</div>
