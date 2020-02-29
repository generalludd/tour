<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 29, 2013 9:44:09 PM chrisdart@cerebratorium.com

$buttons[] = array(
        "text" => "Add a Hotel",
        "type" => "span",
        "class" => "button new add-hotel",
        'data'=>[
        	'tour_id'=>$tour->id,
				],
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
		href="<?php print site_url("tour/view/$tour->id");?>"
		title="View tour details"><?php print $tour->tour_name;?></a>
</h3>
<?php print create_button_bar($buttons);?>
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
<?php foreach($hotels as $hotel): ?>
<tr>
			<td>
			<a
				class="view-hotel"
				href="<?php print site_url("hotel/view/$hotel->id");?>"
				title="View hotel details"><?php print $hotel->hotel_name;?></a></td>
			<td>
			<span
				class="button edit edit-hotel"
				id="<?php print sprintf("edit-hotel_%s",$hotel->id);?>">Edit Hotel</span></td>
			<td>
			<a
				href="<?php print site_url("roommate/view_for_tour/?tour_id=$tour->id&stay=$hotel->stay");?>"
				class="button view-roommates"
				title="Show all roommates for this hotel">Roommates</a></td>
			<td><?php print format_date($hotel->arrival_date);?>,
			<?php print format_time($hotel->arrival_time);?></td>
			<td>
<?php print format_date($hotel->departure_date);?>,
<?php print format_time($hotel->departure_time);?>
</td>
			<td><?php print $hotel->phone;?></td>
			<td><?php print $hotel->fax;?></td>
		</tr>
<?php endforeach; ?>
</tbody>
</table>
