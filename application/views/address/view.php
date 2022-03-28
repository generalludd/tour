<?php

if (isset($person->address)): ?>
	<label>Address:</label><br/>
	<?php print format_address($person->address, "inline"); ?>&nbsp;<?php

	$edit_buttons[] = [
		"text" => "Edit",
		'href' => base_url('address/edit/' . $person->address_id . '/' . $person->id),
		"class" => "button small edit edit-address",
	];
	$edit_buttons[] = [
		'text' => 'Delete',
		'class' => 'button small delete dialog',
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
						<td><?php print person_link($housemate);?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>
<?php else: ?>
<?php $address_buttons['select_housemate'] = [
			'type' => 'passthrough',
		'value' => $this->load->view('address/find_housemate', ['person_id'=>$person->id])

	];
	$address_buttons['add_address'] = [
			'text' => 'Add Address',
			'class' => 'button small new add-address',
			'href' => base_url('address/create'),
			'data' => [
					'person_id' => $person->id,
					'target' => 'address/view',
			],
	]?>
	<?php print create_button_bar($address_buttons); ?>
<?php endif; ?>
