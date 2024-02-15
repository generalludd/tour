<?php
defined('BASEPATH') or exit ('No direct script access allowed');
if (empty($hotel)) {
	return FALSE;
}
// view.php Chris Dart Dec 29, 2013 10:17:41 PM chrisdart@cerebratorium.com
$buttons [] = [
		'text' => 'Edit',
		'href' => base_url('hotel/edit/' . $hotel->id),
		'class' => 'button edit dialog',
];
$buttons [] = [
		'text' => 'Roommates',
		'href' => site_url('roommate/view_for_tour/' .  get_value($hotel, 'tour_id') . '/' .  get_value($hotel, 'stay')),
];
$buttons[] = [
	'text' => 'Delete',
	'href' => base_url('hotel/delete?id=' . $hotel->id),
	'class' => 'button delete dialog',
]
?>
<h3>Information for Hotel <?php print $hotel->hotel_name; ?></h3>
<?php print create_button_bar($buttons); ?>

<input type="hidden" id="id" name="id"
			 value="<?php print get_value($hotel, "id"); ?>"/>
<div class="triptych hotel-info" id="hotel">
	<div class="info">
	<fieldset>
		<legend>Contact Info</legend>
		<?php
		$contact_info = [
				'hotel_name' => [
						'value' => get_value($hotel, 'hotel_name'),
				],
				'address' => [
						'value' => get_value($hotel, 'address'),
				],
				'phone' => [
						'value' => get_value($hotel, 'phone'),
				],
				'fax' => [
						'value' => get_value($hotel, 'fax'),
				],
				'email' => [
						'value' => get_value($hotel, 'email'),
				],
				'website' => [
						'value' => get_value($hotel, 'url'),
				],
		];
		foreach ($contact_info as $key => $info) {
			if (!empty($info['value'])) {
				$info['wrapper'] = 'div';
				$info['id'] = $key;
				$info['wrapper_classes'] = ['horizontal'];
				$this->load->view('elements/field-item', $info);
			}

		}

		?>
	</fieldset>

		<fieldset>
			<legend>Contacts</legend>
		<?php print create_button([
				'text' => 'Add Contact',
				'href' => base_url('contact/create/' . $hotel->id),
				'class' => 'button new small dialog',
		]); ?>

		<?php if (!empty($contacts)): ?>

			<?php $this->load->view('contact/list', ['contacts'=> $contacts]);?>

		<?php endif; ?>
	</fieldset>
	</div>
	<fieldset>
		<legend>Tour Info</legend>
		<div class="field-envelope" id="field-tour_id">
			<label>Tour Name:&nbsp;</label>
			<a class="field" id="tour_name"
				 href="<?php print site_url("tours/view/$hotel->tour_id"); ?>"><?php print $hotel->tour_name; ?></a>
		</div>
		<?php
		$tour_info = [
				'stay' => [
						'value' => get_value($hotel, 'stay'),
				],
				'arrival' => [
						'value' => format_datetime(get_value($hotel, 'arrival_date'), get_value($hotel, 'arrival_time')),

				],
				'departure' => [
						'value' => format_datetime(get_value($hotel, 'departure_date'), get_value($hotel, 'departure_time')),
				],
		];

		foreach ($tour_info as $key => $info) {
			if (!empty($info['value'])) {
				$info['wrapper'] = 'div';
				$info['id'] = $key;
				$info['wrapper_classes'] = ['horizontal'];
				$this->load->view('elements/field-item', $info);
			}
		}

		?>
	</fieldset>
	<?php if (!empty($room_types)): ?>
		<fieldset>
			<legend>
				Room Type Count
			</legend>
			<?php foreach ($room_types as $room_type => $count): ?>
				<div>
					<?php $this->load->view('elements/field-item', [
							'id' => $room_type,
							'wrapper' => 'div',
							'wrapper_classes' => ['horizontal'],
							'value' => $count,
					]);
					?>
				</div>
			<?php endforeach; ?>
		</fieldset>
	<?php endif; ?>
</div>
