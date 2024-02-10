<?php

if (isset($person->address)): ?>
	<label>Address:</label><br/>
	<?php print format_address($person->address, "inline"); ?>&nbsp;<?php
	$edit_buttons[] = [
		"text" => "Edit",
		'href' => base_url('address/edit/' . $person->address_id . '/' . $person->id),
		"class" => "button small edit dialog",
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
$this->load->view('elements/button-bar', ['data' => get_button_bar_object($edit_buttons)]);
 ?>
<?php $this->load->view('elements/field-item', [
		'id' => 'informal_salutation',
		'value' => $person->address->informal_salutation,
		'label' => 'Informal Salutation',
		'type' => 'text',
		'size' => 25,
	]);
$this->load->view('elements/field-item', [
		'id' => 'formal_salutation',
		'value' => $person->address->formal_salutation,
		'label' => 'Formal Salutation',
		'type' => 'text',
		'size' => 25,
]);
?>
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
	<?php	$address_buttons['add_address'] = [
			'text' => 'Add Address',
			'class' => 'button small new dialog',
			'href' => base_url('address/create?person_id=' . $person->id),
	]?>
	<?php $this->load->view('elements/button-bar', ['data' => get_button_bar_object($address_buttons)]); ?>
<?php  $this->load->view('address/find_housemate', ['person_id'=>$person->id]); ?>
<?php endif; ?>
