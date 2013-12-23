<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 14, 2013 3:18:33 PM chrisdart@cerebratorium.com

$buttons[] = array(
        "text" => "Create Tour",
        "type" => "span",
        "class" => "button new create-tour",
        "id" => "tour"
);
print create_button_bar($buttons);
?>
<table class="list">
	<thead>
		<tr>
			<th>Tour</th>
			<th>Start</th>
			<th>End</th>
			<th>Payment Due</th>
			<!-- <th>Full Price</th>
			<th>Banquet Price</th>
			<th>Early Bird</th>
			<th>Regular</th>
			<th>Single Rate</th>
			<th>Triple Rate</th>
			<th>Quad Rate</th> -->
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?
$row_class = "odd";

foreach ($tours as $tour) {
    ?>
<tr class="<?=$row_class;?>">
			<td>
<?=$tour->tour_name;?>
</td>
			<td>
<?=format_date($tour->start_date);?>
</td>
			<td>
<?=format_date($tour->end_date);?>
</td>
			<td>
<?=format_date($tour->due_date);?>
</td>
			<!-- <td>
<?=format_money($tour->full_price);?>
</td>
			<td>
<?=format_money($tour->banquet_price);?>
</td>
			<td>
<?=format_money($tour->early_price);?>
</td>
			<td>
<?=format_money($tour->regular_price);?>
</td>
			<td>
<?=format_money($tour->single_rate);?>
</td>
			<td>
<?=format_money($tour->triple_rate);?>
</td>
			<td>
<?=format_money($tour->quad_rate);?>
</td> -->
			<td>
			<a class="button show-tour mini"
				href="<?=base_url("/index.php/tour/view/$tour->id");?>">Details</a></td>
			<td><a
				class="button shour-toursits mini"
				href="<?=base_url("/index.php/tourist/show_all/$tour->id");?>">Tourists</a>
			</td>
		</tr>
<?
    if ($row_class == "odd") {
        $row_class = "even";
    } else {
        $row_class = "odd";
    }
}

?>
</tbody>
</table>