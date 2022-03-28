<?php
defined('BASEPATH') or exit ('No direct script access allowed');

// view.php Chris Dart Dec 29, 2013 10:17:41 PM chrisdart@cerebratorium.com
$buttons [] = [
		'text' => 'Edit',
		'href' => base_url('hotel/edit/' . $hotel->id),
		'class' => 'button edit edit-hotel',
		'data' => [
				'hotel' => $hotel->id,
		],
];
$buttons [] = [
		'text' => 'Roommates',
		'href' => site_url('roommate/view_for_tour/' . $hotel->tour_id . '/' .  $hotel->stay),
];
?>
<h3>Information for Hotel <?php print $hotel->hotel_name; ?></h3>
<?php print create_button_bar($buttons); ?>

<input type="hidden" id="id" name="id"
	   value="<?php print get_value($hotel, 'id'); ?>"/>
<div class="grouping block hotel-info" id="hotel">
	<div class="column">

		<?php print create_field("address", get_value($hotel, "address"), "Address", [
				"class" => "textarea",
				"envelope" => "div",
		]); ?>
		<?php $fields = [
				'hotel_name' => [
						'id' => 'hotel_name',
						'value' => get_value($hotel, 'hotel_name'),
						'label' => 'Hotel Name',
						'size' => 25,
						'wrapper' => 'div',
				],
			'address' => [
					'label' => 'address',
					'value' => get_value($hotel, 'address'),
				'wrapper' => 'div',
],
				'phone' => [
						'id' => 'phone',
						'type' => 'tel',
						'value' => get_value($hotel, 'phone'),
						'label' => 'Phone',
						'wrapper' => 'div',

				],
				'fax' => [
						'id' => 'fax',
						'type' => 'tel',
						'value' => get_value($hotel, 'fax'),
						'label' => 'Fax',
						'wrapper' => 'div',

				],
				'email' => [
						'id' => 'email',
						'type' => 'email',
						'value' => get_value($hotel, 'email'),
						'label' => 'Email',
						'wrapper' => 'div',

				],
				'url' => [
						'id' => 'url',
						'type' => 'url',
						'value' => get_value($hotel, 'url'),
						'label' => 'Website',
						'wrapper' => 'div',

				],

		];
		foreach ($fields as $field) {
			$this->load->view('elements/field-item', $field);
		}
		?>

		<?php print create_button([
				'text' => 'Add Contact',
				'href' => base_url('contact/create/' . $hotel->id),
				'class' => 'button new small dialog',
		]); ?>

		<?php if (!empty($contacts)): ?>
			<h4>Contacts</h4>
			<?php foreach ($contacts as $contact): ?>
				<div class="contact-row row">
					<?php $this->load->view('contact/view', $contact); ?>
				</div>
			<?php endforeach; ?>

		<?php endif; ?>
	</div>
	<div class="column">
		<div class="field-envelope" id="field-tour_id">
			<label>Tour Name:&nbsp;</label>
			<a class="field" id="tour_name"
			   href="<?php print site_url("tour/view/$hotel->tour_id"); ?>"><?php print $hotel->tour_name; ?></a>
		</div>
		<?php $fields = [
				'stay' => [
						'id' => 'stay',
						'label' => 'Stay',
						'value' => get_value($hotel, 'stay'),
						'wrapper' => 'div',
				],
				'arrival_date' => [
						'id' => 'arrival_date',
						'value' => format_date(get_value($hotel, 'arrival_date')),
						'label' => 'Arrival Date',
						'wrapper' => 'div',
				],
				'arrival_time' => [
						'id' => 'arrival_time',
						'value' => get_value($hotel, 'arrival_time'),
						'label' => 'Arrival Time',
						'wrapper' => 'div',
				],
				'departure_date' => [
						'id' => 'departure_date',
						'value' => format_date(get_value($hotel, 'departure_date')),
						'label' => 'Departure Date',
						'wrapper' => 'div',
				],
				'departure_time' => [
						'id' => 'departure_time',
						'value' => get_value($hotel, 'departure_time'),
						'label' => 'Departure Time',
						'wrapper' => 'div',
				],

		];
		?>

	</div>
	<div class="column">
		<p>
			<strong>Room Type Count:</strong>
		</p>
		<?php foreach ($room_types as $room_type => $count): ?>
			<div>
				<?php print format_field_name($room_type) . ':' . $count; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
