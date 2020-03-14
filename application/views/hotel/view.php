<?php
defined('BASEPATH') or exit ('No direct script access allowed');

// view.php Chris Dart Dec 29, 2013 10:17:41 PM chrisdart@cerebratorium.com
$buttons [] = [
	"text" => "Edit",
	"href" => base_url('hotel/edit/' . $hotel->id),
	"class" => "button edit edit-hotel",
	'data' => [
		'hotel' => $hotel->id,
	],
];
$buttons [] = [
	"text" => "Roommates",
	"href" => site_url(sprintf("roommate/view_for_tour/?tour_id=%s&stay=%s", get_value($hotel, "tour_id"), get_value($hotel, "stay"))),
];
?>
<h3>Information for Hotel <?php print $hotel->hotel_name; ?></h3>
<?php print create_button_bar($buttons); ?>

<input type="hidden" id="id" name="id"
			 value="<?php print get_value($hotel, "id"); ?>"/>
<div class="grouping block hotel-info" id="hotel">
	<div class="column">
		<?php print create_field("hotel_name", get_value($hotel, "hotel_name"), "Hotel Name", ["envelope" => "div"]); ?>
		<?php print create_field("address", get_value($hotel, "address"), "Address", [
			"class" => "textarea",
			"envelope" => "div",
		]); ?>

		<?php print create_field("phone", get_value($hotel, "phone"), "Phone", [
			"envelope" => "div",
			"format" => "tel",
			"type" => "tel",
		]); ?>
		<?php print create_field("fax", get_value($hotel, "fax"), "Fax", [
			"envelope" => "div",
			"format" => "tel",
			"type" => "tel",
		]); ?>
		<?php print create_field("email", get_value($hotel, "email"), "Email", [
			"envelope" => "div",
			"format" => "email",
			"type" => "email",
		]); ?>
		<?php print create_field("url", get_value($hotel, "url"), "Website", [
			"envelope" => "div",
			"format" => "url",
			"type" => "url",
		]); ?>
		<?php print create_button([
			"text" => "Add Contact",
			"href" => base_url('contact/create/' . $hotel->id),
			"class" => "button new small add-contact",
		]); ?>

		<?php if (!empty($contacts)): ?>
			<h4>Contacts</h4>
			<?php foreach ($contacts as $contact): ?>
				<div class="contact-row row">
					<div class="contact-info">
						<?php print format_contact([
							"name" => get_value($contact, "contact", FALSE),
							"position" => get_value($contact, "position", FALSE),
							"phone" => get_value($contact, "phone", FALSE),
							"fax" => get_value($contact, "fax", FALSE),
							"email" => get_value($contact, "email", FALSE),
						]); ?>
					</div>
					<?php print create_button([
						"text" => "Edit Contact",
						"type" => "span",
						"class" => "button edit float-right small edit-contact",
						"id" => sprintf("edit-contact_%s", $contact->id),
					]); ?>
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
		<?php print create_field("stay", get_value($hotel, "stay"), "Stay", [
			"envelope" => "div",
			"format" => "number",
			"type",
			"number",
		]); ?>
		<?php print create_field("arrival_date", format_date(get_value($hotel, "arrival_date")), "Arrival Date", [
			"envelope" => "div",
			"format" => "date",
			"type" => "date",
		]); ?>
		<?php print create_field("arrival_time", get_value($hotel, "arrival_time"), "Arrival Time", [
			"envelope" => "div",
			"format" => "time",
			"type" => "time",
		]); ?>
		<?php print create_field("departure_date", format_date(get_value($hotel, "departure_date")), "Departure Date", [
			"envelope" => "div",
			"format" => "date",
			"type" => "date",
		]); ?>
		<?php print create_field("departure_time", get_value($hotel, "departure_time"), "Departure Time", [
			"envelope" => "div",
			"format" => "time",
			"type" => "time",
		]); ?>
	</div>
	<div class="column">
		<p>
			<strong>Room Type Count:</strong>
		</p>
		<?php foreach ($room_types as $room_type => $count): ?>
			<div>
				<?php printf("%ss: %s", format_field_name($room_type), $count); ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
