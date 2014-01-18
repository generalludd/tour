<?php

defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 28, 2013 4:47:05 PM chrisdart@cerebratorium.com
$total_due = 0;
$total_paid = 0;
$total_payers = 0;
$total_tourists = 0;
?>
<h2><?=$tour->tour_name;?></h2>
<? $buttons[] = array("text"=> "Tour Details", "href" => site_url("tour/view/$tour->id"), "class"=>"button show-tour");?>
<? $buttons[] = array("text" => "Hotels and Roommates", "href"=> site_url("hotel/view_all/$tour->id"), "class"=>"button show-hotels");?>
<?=create_button_bar($buttons);?>
<table class="list">
	<thead>
		<tr>
			<th style="width:15ex;">Payer</th>
			<th style="width:20ex">Tourists</th>
			<th>Contact Info</th>
			<th>Payment Type<br/>Price</th>
			<th>Paid</th>
			<th>Discount</th>
			<th>Room Size<br/>Rate</th>
			<th>Due</th>
			<th></th>
		</tr>
	</thead>
	<tbody>

<? foreach ($payers as $payer) : ?>
<? $total_payers++;?>
<tr class="row row-break">
			<td><a href="<?=site_url("person/view/$payer->payer_id");?>"><?=sprintf("%s %s", $payer->first_name,$payer->last_name);?></a></td>
			<td>
<? foreach($payer->tourists as $tourist) :?>
    <? $total_tourists++;?>
    <? $tourist_name = sprintf("%s %s", $tourist->first_name,$tourist->last_name);?>
    <? if($tourist->person_id != $payer->payer_id) : ?>
    <? $tourist_name = sprintf("<a href='%s'>%s</a>",site_url("person/view/$tourist->person_id"),$tourist_name);?>
    <? endif; ?>
    <?=$tourist_name;?>
    <? if(get_value($tourist, "shirt_size", FALSE)): ?>
        &nbsp;(<?=$tourist->shirt_size;?>)
    <? endif;?>
    <br />
<? endforeach; ?>
</td>
			<td>
            <? if($payer->phones || $payer->email): ?>
                <? if(get_value($payer, "email", FALSE)): ?>
                    <?=format_email($payer->email);?><br />
                <? endif; ?>
                <? foreach($payer->phones as $phone):?>
                    <?=sprintf("%s: %s",$phone->phone_type, $phone->phone);?><br />
                <? endforeach;?>
            <? endif; ?>
            </td>
			<?
    if ($payer->is_comp == 1) :
        ?>
            <td>Complementary</td>
        <? elseif ($payer->is_cancelled == 1): ?>
            <td class='cancelled'>Cancelled</td>
        <? else: ?>
            <td><?=sprintf("%s<br/>%s",format_field_name($payer->payment_type), format_money($payer->price));?>
           </td>
        <? endif; ?>
			<td><?=format_money($payer->amt_paid);?></td>
			<td><?=format_money($payer->discount);?></td>
			<td><?=sprintf("%s<br/>%s", format_field_name($payer->room_size),format_money($payer->room_rate));?></td>
			<td><?=format_money($payer->amt_due);?></td>
			<td><span
				class="button edit edit-payer"
				id="edit-payer_<?=$payer->payer_id;?>_<?=$payer->tour_id;?>"> Edit</span></td>
		</tr>
				<? if(get_value($payer, "note",FALSE)): ?>
		<tr>
		<td></td>
		<td colspan="10"><?=get_value($payer,"note");?></td>
		</tr>
		<? endif;?>
		<?

$total_due += $payer->amt_due;
    $total_paid += $payer->amt_paid;
    ?>

		<? endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td>Total Payers: <?=$total_payers;?>
	</td>
			<td>Total Tourists: <?=$total_tourists;?>
	</td>
			<td colspan='2'></td>
			<td>
	<?=format_money($total_paid);?>
	</td>
			<td colspan='2'></td>
			<td>
	<?=format_money($total_due);?>
	</td>
		</tr>
	</tfoot>
</table>
