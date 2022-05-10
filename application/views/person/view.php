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

$move_button[] = [
		"text" => "Move",
		"type" => "span",
		"title" => "move this person to another address in the database",
		"class" => "button small edit change-housemate",
		"id" => sprintf("change-housemate_%s", get_value($person, "id", $id)),
];
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
<?php if (get_value($person, "status") == 0): ?>
	<div class="notice">
		This person's record has been disabled which means you deleted it at
		some point, but, because they were on at least one tour, they could not
		be permanently deleted from the database.<br/>
		<?php print create_button_bar($restore_button); ?>
	</div>


<?php endif; ?>
<?php //if (empty($ajax)): ?>
<!--	--><?php //print create_button_bar($nav_buttons); ?>
<?php //endif; ?>
<!--<h3>Person-->
<!--	Record: --><?php //print sprintf("%s %s", $person->first_name, $person->last_name); ?><!--</h3>-->
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
		<div class='field-set'>
			<?php print create_field("first_name", get_value($person, "first_name"), "First Name", ["envelope" => "div"]); ?>
		</div>
		<div class='field-set'>
			<?php print create_field("last_name", get_value($person, "last_name"), "Last Name", ["envelope" => "div"]); ?>
		</div>
		<?php $this->load->view('elements/field-item', ['id' => 'email',
			'label' => 'Email',
			'value' => get_value($person,'email'),
			'type' => 'email',
		]);?>
		<div class='field-set'>
			<?php print create_field("shirt_size", get_value($person, "shirt_size"), "Shirt Size", [
					"envelope" => "div",
					"class" => "dropdown",
					"attributes" => "menu='shirt_size'",
			]); ?>
		</div>
		<div class='field-set'>
			<label for="note">Note:</label><br/>
			<?php print get_value($person, "note"); ?>
		</div>
		<div class="field-set">
			<label for="is_veteran">Is Veteran: </label>
			<?php print !empty($person->is_veteran) ? 'Yes' : 'No'; ?>
		</div>
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
			class=" block address-info"
			id="address">
		<?php $this->load->view('address/view', ['person' => $person]); ?>
	</fieldset>
</div>
