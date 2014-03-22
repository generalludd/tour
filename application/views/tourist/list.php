<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 28, 2013 4:47:05 PM chrisdart@cerebratorium.com
$total_due = 0;
$total_paid = 0;
$total_payers = 0;
$total_tourists = 0;
$buttons[] = array(
        "text" => "Tour Details",
        "href" => site_url("tour/view/$tour->id"),
        "class" => "button show-tour",
);
$buttons[] = array(
        "text" => "Hotels and Roommates",
        "href" => site_url("hotel/view_all/$tour->id"),
        "class" => "button show-hotels",
);
$buttons[] = array(
	"text" => "Letter Templates",
        "href"=> site_url("tour/view/$tour->id"),
        "class" => "button show-tour",
);
$buttons[] = array(
        "text" => "Export List for Mail Merge",
        "href" => site_url("tourist/view_all/$tour->id?export=TRUE"),
        "class" => "button export export-tourists"
);
?>
<h2><?=$tour->tour_name;?></h2>
<?=create_button_bar($buttons);?>
<table class="list">
	<thead>
		<tr>
			<th style="width: 25ex">Payer (&#42;) &#36; Tourists</th>
			<th></th>
			<th>Contact Info</th>
			<th>Payment Type<br />Price
			</th>
			<th>Paid</th>
			<th>Discount</th>
			<th>Room Size<br />Rate
			</th>
			<th>Due</th>
		</tr>
	</thead>
	<tbody>

<? foreach ($payers as $payer) : ?>
<? $total_payers++;?>
<tr class="row row-break">
			<td>
<? foreach($payer->tourists as $tourist) :?>
    <? $total_tourists++;?>
    <? $tourist_name = sprintf("%s %s", $tourist->first_name,$tourist->last_name);?>
    <? printf("<a href='%s' title='View %s&rsquo;s address book entry'>%s</a>",site_url("person/view/$tourist->person_id"),$tourist_name, $tourist_name);?>
    <? if($tourist->person_id == $payer->payer_id) : ?>
    <?="*";?>
    <? endif; ?>
    <? if(get_value($tourist, "shirt_size", FALSE)): ?>
        &nbsp;(<?=$tourist->shirt_size;?>)
    <? endif;?>
    <br />
<? endforeach; ?>
<br/>
	<span class="button new select-letter" id="<?=sprintf("select-letter_%s_%s", $payer->payer_id, $payer->tour_id);?>">Send Letter</span>
			<td>
			<a
			href="<?=site_url("payer/edit?payer_id=$payer->payer_id&tour_id=$payer->tour_id");?>"
				class="button edit">Edit
					Payment</a>
					</td>
			<td>
            <? if($payer->phones || $payer->email): ?>
                <? if(get_value($payer, "email", TRUE)): ?>
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
			<td><?=format_money($payer->amt_paid);?>
			</td>
			<td><?=format_money($payer->discount);?></td>
			<td><?=sprintf("%s<br/>%s", format_field_name($payer->room_size),format_money($payer->room_rate));?></td>
			<td><?=format_money($payer->amt_due);?>
			</td>
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
