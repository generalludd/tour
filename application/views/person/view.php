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
	'text' => 'Export vCard',
	'title' => sprintf('Export a universal address book card for ', $person->first_name),
	'href' => base_url('person/vcard/' . $person->id),
	'class' => 'button export',
];
if (get_value($person, "id", FALSE) && $tour_count == 0) {
	$buttons[] = [
		'text' => 'Delete',
		'href' => base_url('person/delete?type=delete&id=' . $person->id),
		'data' => [
			'id' => $person->id,
			'redirect' => 'person/view_all',
		],
		'class' => ['button', 'delete', 'dialog'],
	];
}
elseif ($person->status == 0) {
	$buttons[] = [
		'text' => 'Restore',
		'title' => 'Restore this person\'s record.',
		'href' => base_url('person/restore?id=' . $person->id),
		'data' => [
			'id' => $person->id,
			'redirect' => 'person/view/' . $person->id,
		],
		'class' => ['button', 'new'],
	];
}
else {
	$buttons[] = [
		'text' => 'Disable',
		'title' => 'This person has been on tours so they cannot be deleted.',
		'href' => base_url('person/disable?type=disable&id=' . $person->id),
		'data' => [
			'id' => $person->id,
			'redirect' => 'person/view/' . $person->id,
		],
		'class' => ['button', 'delete', 'dialog'],
	];
}

$phone_button[] = [
	"text" => "Add Phone",
	"class" => "button small new add-phone",
	'href' => base_url('phone/create/' . $person->id),
];
?>
<h3> <?php print sprintf("%s %s", $person->first_name, $person->last_name); ?></h3>

<div class="content">
	<fieldset
		class="person-info"
		id="person">

		<?php print create_button_bar($buttons); ?>
		<div class="diptych">
			<fieldset
				class=" block person-info"
				id="person">
				<?php if (get_value($person, "status") == 0): ?>
					<div>
						This person's record has been disabled which means you deleted it at
						some point, but, because they were on at least one tour, they could
						not
						be permanently deleted from the database.
					</div>

				<?php endif; ?>
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
		<div <?php if(!empty($tours) && !empty($duplicates)):?>class="diptych"<?php endif;?>>
			<div class="tours">
				<?php if (!empty($tours) && !empty($tour_count)): ?>
					<fieldset class="person--tour-list">
						<legend>Tours</legend>
						<?php $this->load->view('tourist/tour_list', ['tourists' => $tours]); ?>
					</fieldset>
				<?php endif; ?>
			</div>
			<?php if (!empty($duplicates)): ?>
				<fieldset class="person--duplicate-list">
					<legend>Possible Duplicates</legend>
					<?php $this->load->view('person/duplicate_list', [
						'duplicates' => $duplicates,
						'source_id' => $person->id,
					]); ?>
				</fieldset>
			<?php endif; ?>
		</div>
