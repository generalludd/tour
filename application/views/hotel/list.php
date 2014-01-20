<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 29, 2013 9:44:09 PM chrisdart@cerebratorium.com

$buttons[] = array(
        "text" => "Add a Hotel",
        "type" => "span",
        "class" => "button new add-hotel",
        "id" => sprintf("add-hotel_%s", $tour->id)
);
$buttons[] = array(
        "text" => "Tour Details",
        "href" => site_url("tour/view/$tour->id"),
        "class" => "button tour-details"
);

$buttons[] = array(
        "text" => "Tourists",
        "href" => site_url("tourist/view_all/$tour->id"),
        "class" => "button tourist-list"
);

?>
<h3>
	Hotels for Tour: <a
		href="<?=site_url("tour/view/$tour->id");?>"
		title="View tour details"><?=$tour->tour_name;?></a>
</h3>
<?=create_button_bar($buttons);?>
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
<? foreach($hotels as $hotel): ?>
<tr>
			<td>
			<a
				class="view-hotel"
				href="<?=site_url("hotel/view/$hotel->id");?>"
				title="View hotel details"><?=$hotel->hotel_name;?></a></td>
			<td>
			<span
				class="button edit edit-hotel"
				id="<?=sprintf("edit-hotel_%s",$hotel->id);?>">Edit Hotel</span></td>
			<td>
			<a
				href="<?=site_url("roommate/view_for_tour/?tour_id=$tour->id&stay=$hotel->stay");?>"
				class="button view-roommates"
				title="Show all roommates for this hotel">Roommates</a></td>
			<td><?=format_date($hotel->arrival_date);?>,
			<?=format_time($hotel->arrival_time);?></td>
			<td>
<?=format_date($hotel->departure_date);?>,
<?=format_time($hotel->departure_time);?>
</td>
			<td><?=$hotel->phone;?></td>
			<td><?=$hotel->fax;?></td>
		</tr>
<? endforeach; ?>
</tbody>
</table>