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
<?php if(count($tours)>0): ?>
<table class="list">
	<thead>
		<tr>
			<th>Tour</th>
			<th>Start</th>
			<th>End</th>
			<th>Payment Due</th>
			<?php if($for_tourist):?>
			<th>Amt Paid</th>
			<?php endif;?>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php
$row_class = "odd";

foreach ($tours as $tour) {
    ?>
<tr class="<?php print $row_class;?>">
			<td>

		<a href="<?php print site_url("tour/view/$tour->id");?>">
<?php print $tour->tour_name;?>
</a>
</td>
			<td>
<?php print format_date($tour->start_date);?>
</td>
			<td>
<?php print format_date($tour->end_date);?>
</td>
			<td>
<?php print format_date($tour->due_date);?>
</td>
<?php if($for_tourist): ?>
<td>
<?php print format_money($tour->amt_paid);?>
</td>
<td>
<a href="<?php print site_url("payer/edit/?payer_id=$tour->payer_id&tour_id=$tour->tour_id");?>" class="button edit">Edit Payment</a>
</td>
<td><a class="button show-toursits mini"
				href="<?php print site_url("/tourist/view_all/$tour->id");?>">Tourists</a></td>
<?php else: ?>
			<td><a
				class="button show-hotels small"
				href="<?php print site_url("/hotel/view_all/$tour->id");?>">Hotels</a></td>
			<td><a
				class="button show-toursits small"
				href="<?php print site_url("/tourist/view_all/$tour->id");?>">Tourists</a></td>
				<td><a
				class="button show-letters small"
				href="<?php print site_url("/tour/letters/$tour->id");?>">Letter Templates</a></td>
		</tr>
<?php
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
<?php elseif($for_tourist):?>
<p>There are no tours on record for this person. Click on "Join Tour" to add this person to a current tour.</p>
<?php endif;
