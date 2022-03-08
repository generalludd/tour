<?php
defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 11, 2013 7:47:55 PM chrisdart@cerebratorium.com
$buttons[] = [
		"text" => "Edit Person",
		"href" => site_url("person/edit/$person->id"),
		"class" => "button edit dialog",
		"id" => sprintf("edit-person_%s", $person->id),
];
$buttons[] = [
		"text" => "Join Tour",
		"href" => base_url('tour/show_current/' . $person->id),
		"class" => "button new mini select-tour",
		"id" => sprintf("join-tour_%s", $person->id),
];
$buttons[] = [
		'text' => 'Join Past Tour',
		'href' => base_url('tour/show_missed_tours/' . $person->id),
		'class' => 'button new mini  select-tour',
		'description' => 'Add to a tour that happened in the past',
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

$nav_buttons[] = [
		"text" => "<- Previous Record",
		"class" => "button navigation previous-person-record",
		"href" => site_url("person/view_previous/$person->id"),
];
$nav_buttons[] = [
		"text" => "Next Record ->",
		"class" => "button navigation next-person-record",
		"href" => site_url("person/view_next/$person->id"),
];

$address_buttons["select_housemate"] = [
		"text" => "Select Housemate",
		"type" => "span",
		"class" => "button small edit change-housemate",
		"id" => sprintf("change-housemate_%s", get_value($person, "id", $id)),
];
$address_buttons["add_address"] = [
		"text" => "Add Address",
		"type" => "span",
		"class" => "button small new add-address",
		"id" => sprintf("add-address_%s", get_value($person, "id", $id)),
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
<?php if (empty($ajax)): ?>
	<?php print create_button_bar($nav_buttons); ?>
<?php endif; ?>
<h3>Person
	Record: <?php print sprintf("%s %s", $person->first_name, $person->last_name); ?></h3>
<?php print create_button_bar($buttons); ?>

<div class="content">
	<div
			class="grouping block person-info"
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
		<div class='field-set'>
			<?php print create_field("email", get_value($person, "email"), "Email", [
					"envelope" => "div",
					"format" => "email",
			]); ?>
		</div>
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
	</div>
	<fieldset
			class="grouping block address-info"
			id="address">
		<!--<h5>Address</h5>-->
		<?php if (isset($person->address)): ?>
			<label>Address:</label><br/>
			<?php print format_address($person->address, "inline"); ?>&nbsp;<?php

			$edit_buttons[] = [
					"text" => "Edit",
					'href' => base_url('address/edit/' . $person->address_id . '/' . $person->id),
					"class" => "button small edit edit-address",
			];
			$edit_buttons[] = [
					'text' => 'Delete',
					'class' => 'button small delete',
					'data' => [
							'address_id' => $person->address_id,
							'person_id' => $person->id,
					],
					'href' => base_url('person/remove_address/' . $person->id . '/' . $person->address_id),
			];
			print create_button_bar($edit_buttons); ?>
			<?php print create_field("informal_salutation", $person->address->informal_salutation, "Informal Salutation"); ?>
			<?php print create_field("formal_salutation", $person->address->formal_salutation, "Formal Salutation"); ?>
			<div class="block housemate-info"
				 id="housemate">
				<?php if (count($person->housemates) > 0): ?>
					<p>
						<label>Housemates</label>
					</p>
					<table class="block">
						<?php foreach ($person->housemates as $housemate): ?>
							<tr>
								<td><a
											href="<?php print site_url("person/view/$housemate->id"); ?>"><?php print sprintf("%s %s", $housemate->first_name, $housemate->last_name); ?></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				<?php endif; ?>
				<?php print create_button_bar([
						[
								"text" => "Add Housemate",
								"class" => "button small new add-housemate",
								'href' => base_url('person/add_housemate/' . $person->address->id),
								"id" => sprintf("add-housemate_%s_%s", $person->id, $person->address->id),
						],
				]); ?>
			</div>
		<?php else: ?>
			<?php print create_button_bar($address_buttons); ?>
		<?php endif; ?>
	</fieldset>
</div>
