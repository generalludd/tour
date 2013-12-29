<?php defined('BASEPATH') OR exit('No direct script access allowed');

// list.php Chris Dart Dec 28, 2013 4:47:05 PM chrisdart@cerebratorium.com
?>
<h2><?=$tour->tour_name;?></h2>
<? $buttons[] = array("text"=> "Tour Details", "href" => site_url("tour/view/$tour->id"), "class"=>"button mini show-tour");?>
<?=create_button_bar($buttons);?>
<table class="list">
	<thead>
		<tr>
			<th>Payer</th>
			<th>Tourists</th>
			<th>Shirt Size</th>
			<th>Contact Info</th>
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

<? foreach ($payers as $payer) : ?>

<tr class="row row-break">
			<td><a href="<?=site_url("person/view/$payer->payer_id");?>"><?=sprintf("%s %s", $payer->first_name,$payer->last_name);?></a></td>

            <td>
<? foreach($payer->tourists as $tourist) :?>
<? if($tourist->person_id != $payer->payer_id) : ?>
<table><tr><td><a href="<?=site_url("person/view/$tourist->person_id");?>"><?=sprintf("%s %s", $tourist->first_name,$tourist->last_name);?></a></td></tr></table>
<? endif; ?>
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
            <td><?=format_field_name($payer->payment_type);?></td>
        <? endif; ?>
			<td>
			<?=format_money($payer->price);?></td>
			<td><?=format_money($payer->amt_paid);?></td>
			<td><?=format_money($payer->discount);?></td>
			<td><?=sprintf("%s (%s)", format_field_name($payer->room_size),format_money($payer->room_rate));?></td>
			<td><?=format_money($payer->amt_due);?></td>
			<td><span
				class="button edit edit-payer"
				id="edit-payer_<?=$payer->payer_id;?>_<?=$payer->tour_id;?>">
					Edit</span></td>
		</tr>
		<? endforeach; ?>
	</tbody>
</table>