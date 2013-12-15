<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 14, 2013 4:30:04 PM chrisdart@cerebratorium.com
?>
<h2><?=$tour_name;?></h2>
<table class="list">
	<thead>
		<tr>
			<th>Name</th>
			<th>Shirt Size</th>
			<th>Email</th>
			<th>Phones</th>
			<th>Payment Type</th>
			<th>Price</th>
			<th>Paid</th>
			<th>Discount</th>
			<th>Room Size (Rate)</th>
			<th>Due</th>
			<th></th>
		</tr>
	</thead>
	<tbody>

<?
foreach ($tourists as $tourist) :
    ?>
    <?
    $class = "";
    if ($tourist->is_payer):
    $class = "row-break";
    endif;
    ?>
<tr class="row <?=$class;?>">
			<td><?=sprintf("%s %s", $tourist->first_name,$tourist->last_name);?></td>
			<td><?=$tourist->shirt_size;?></td>
			<td><?=format_email($tourist->email);?></td>
		<? if($tourist->is_payer): ?>
		<? if($tourist->phones): ?>
		<td>
				<table>
				<? foreach($tourist->phones as $phone):?>
                    <tr>
						<td><?=$phone->phone_type;?></td>
						<td><?=$phone->phone;?> </td>
					</tr>
				<? endforeach;?>
				</table>
			</td>
		<? endif; ?>

		<td><?=format_field_name($tourist->payment_type);?></td>
			<td><?=format_money($tourist->price); ?></td>
			<td><?=format_money($tourist->amt_paid);?></td>
			<td><?=format_money($tourist->discount);?></td>
			<td><?=sprintf("%s (%s)", format_field_name($tourist->room_size),format_money($tourist->room_rate));?></td>
			<td><?=format_money($tourist->amt_due);?></td>
<td><span class="button edit edit_payer" id="edit-payer_<?=$tourist->payer_id;?>_<?=$tourist->tour_id;?>">
Edit</span></td>
		<? endif; ?>
		</tr>
		<? endforeach; ?>
	</tbody>
</table>
