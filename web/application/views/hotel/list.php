<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 29, 2013 9:44:09 PM chrisdart@cerebratorium.com

$buttons[] = [
		"text" => "Add a Hotel",
		"class" => "button new dialog",
		"href" => site_url("hotel/create/$tour->id"),
];
$buttons[] = [
		"text" => "Tour Details",
		"href" => site_url("tours/view/$tour->id"),
		"class" => "button tour-details",
];

$buttons[] = [
		"text" => "Tourists",
		"href" => site_url("tourist/view_all/$tour->id"),
		"class" => "button tourist-list",
];

?>
<h3>
	Hotels for Tour: <a href="<?php print site_url("tours/view/$tour->id"); ?>"
											title="View tour details"><?php print $tour->tour_name; ?></a>
</h3>
<?php print create_button_bar($buttons); ?>
<table class="list">
	<thead>
	<tr>
		<th>Name</th>
		<th></th>
		<th></th>
		<th>Arrival</th>
		<th>Departure</th>
		<th>Phone</th>
		<th>Fax</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($tour->hotels as $hotel) : ?>
		<tr>
			<td>
				<a class="view-hotel" href="<?php print site_url('hotel/view/' .$hotel->id); ?>"
					 title="View hotel details"><?php print $hotel->hotel_name; ?></a>
			</td>
			<td>
				<a class="button edit dialog" href="<?php print site_url('/hotel/edit/' . $hotel->id); ?>">Edit
																																																	 Hotel</a>
			</td>
			<td>
				<a href="<?php print site_url('roommate/view_for_tour/' . $tour->id . '/' . $hotel->stay); ?>"
					 class="button view-roommates" title="Show all roommates for this hotel">Roommates</a>
			</td>
			<td><?php print format_datetime($hotel->arrival_date, $hotel->arrival_time); ?>
			</td>
			<td>
				<?php print format_datetime($hotel->departure_date, $hotel->departure_time); ?>
			</td>
			<td><?php print $hotel->phone; ?></td>
			<td><?php print $hotel->fax; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
