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

?>
<h4>Hotels for Tour: <?=$tour->tour_name;?></h4>
<?=create_button_bar($buttons);?>
<table class="list">
	<thead>
		<tr>
			<th>Name</th>
			<th>Arrival</th>
			<th>Departure</th>
			<th>Phone</th>
			<th>Fax</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<? foreach($hotels as $hotel): ?>
<tr>
			<td><a
				class="view-hotel"
				href="<?=site_url("hotel/view/$hotel->id");?>"><?=$hotel->hotel_name;?></a></td>
			<td><?=format_date($hotel->arrival_date);?>,
			<?=format_time($hotel->arrival_time);?></td>
			<td>
<?=format_date($hotel->departure_date);?>,
<?=format_time($hotel->departure_time);?>
</td>
			<td><?=$hotel->phone;?></td>
			<td><?=$hotel->fax;?></td>
			<td><span
				class="button edit edit-hotel"
				id="<?=sprintf("edit-hotel_%s",$hotel->id);?>">Edit</span></td>
		</tr>
<? endforeach; ?>
</tbody>
</table>