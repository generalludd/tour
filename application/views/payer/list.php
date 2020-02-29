<?php defined('BASEPATH') OR exit('No direct script access allowed');

// list.php Chris Dart Dec 28, 2013 4:47:05 PM chrisdart@cerebratorium.com
?>
<h2><?php print $tour->tour_name;?></h2>
<?php $buttons[] = array("text"=> "Tour Details", "href" => site_url("tour/view/$tour->id"), "class"=>"button mini show-tour");?>
<?php print create_button_bar($buttons);?>
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
			<th>Adjustment</th>
			<th>Room Size (Rate)</th>
			<th>Due</th>
			<th></th>
		</tr>
	</thead>
	<tbody>

<?php foreach ($payers as $payer) : ?>

<tr class="row row-break">
			<td><a href="<?php print site_url("person/view/$payer->payer_id");?>"><?php print sprintf("%s %s", $payer->first_name,$payer->last_name);?></a></td>

            <td>
<?php foreach($payer->tourists as $tourist) :?>
<?php if($tourist->person_id != $payer->payer_id) : ?>
<table><tr><td><a href="<?php print site_url("person/view/$tourist->person_id");?>"><?php print sprintf("%s %s", $tourist->first_name,$tourist->last_name);?></a></td></tr></table>
<?php endif; ?>
<?php endforeach; ?>
</td>
 <td>
            <?php if($payer->phones || $payer->email): ?>
                <?php if(get_value($payer, "email", FALSE)): ?>
                    <?php print format_email($payer->email);?><br />
                <?php endif; ?>
                <?php foreach($payer->phones as $phone):?>
                    <?php print sprintf("%s: %s",$phone->phone_type, $phone->phone);?><br />
                <?php endforeach;?>
            <?php endif; ?>
            </td>
			<?php
        if ($payer->is_comp == 1) :
            ?>
            <td>Complementary</td>
        <?php elseif ($payer->is_cancelled == 1): ?>
            <td class='cancelled'>Cancelled</td>
        <?php else: ?>
            <td><?php print format_field_name($payer->payment_type);?></td>
        <?php endif; ?>
			<td>
			<?php print format_money($payer->price);?></td>
			<td><?php print format_money($payer->amt_paid);?></td>
			<td><?php print format_money($payer->discount);?></td>
			<td><?php print sprintf("%s (%s)", format_field_name($payer->room_size),format_money($payer->room_rate));?></td>
			<td><?php echo $payer->is_cancelled==1?0:format_money($payer->amt_due);?></td>
			<td><span
				class="button edit edit-payer"
				data-payer_id="<?php print $payer->payer_id;?>"
				data-tour_id="<?php print $payer->tour_id;?>">
					Edit</span></td>
		</tr>
		<?php if(get_value($payer, "note",FALSE)): ?>
		<tr>
		<td colspan="11"><?php print get_value($payer->note);?></td>
		</tr>
		<?php endif;?>
		<?php endforeach; ?>
	</tbody>
</table>
