<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 14, 2013 3:18:33 PM chrisdart@cerebratorium.com
if (! $for_tourist) {
    $buttons[] = array(
            "text" => "Create Tour",
            "type" => "span",
            "class" => "button new create-tour",
            "id" => "tour"
    );
    print create_button_bar($buttons);
}
?>
<? if(count($tours)>0): ?>
<table class="list">
	<thead>
		<tr>
			<th>Tour</th>
			<th>Start</th>
			<th>End</th>
			<th>Payment Due</th>
			<? if($for_tourist):?>
			<th>Amt Paid</th>
			<? endif;?>
			<!-- <th>Full Price</th>
			<th>Banquet Price</th>
			<th>Early Bird</th>
			<th>Regular</th>
			<th>Single Rate</th>
			<th>Triple Rate</th>
			<th>Quad Rate</th> -->
			<th></th>
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

		<a href="<?=site_url("tour/view/$tour->id");?>">
<?=$tour->tour_name;?>
</a>
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
<? if($for_tourist): ?>
<td>
<?=format_money($tour->amt_paid);?>
</td>
<td>
<a href="<?=site_url("payer/edit/?payer_id=$tour->payer_id&tour_id=$tour->tour_id");?>" class="button edit">Edit Payment</a>
</td>
<td><a class="button show-toursits mini"
				href="<?=site_url("/tourist/view_all/$tour->id");?>">Tourists</a></td>
<? else: ?>
			<td><a
				class="button show-hotels small"
				href="<?=site_url("/hotel/view_all/$tour->id");?>">Hotels</a></td>
			<td><a
				class="button show-toursits small"
				href="<?=site_url("/tourist/view_all/$tour->id");?>">Tourists</a></td>
				<td><a
				class="button show-letters small"
				href="<?=site_url("/tour/view/$tour->id");?>">Letter Templates</a></td>
		</tr>
<?
endif;
    if ($row_class == "odd") {
        $row_class = "even";
    } else {
        $row_class = "odd";
    }
}

?>
</tbody>
</table>
<? elseif($for_tourist):?>
<p>There are no tours on record for this person. Click on "Join Tour" to add this person to a current tour.</p>
<? endif;