<?php

defined('BASEPATH') or exit('No direct script access allowed');

// payer.php Chris Dart Dec 14, 2013 6:32:48 PM chrisdart@cerebratorium.com
$tourist_count = count($tourists);
$total_cost = ($tour_price - $payer->discount + $room_rate) * $tourist_count;
$amt_due = $total_cost - $payer->amt_paid;
?>
<form
	name="payer-editor"
	id="payer-editor"
	method="post"
	action="<?=base_url("index.php/payer/$action");?>">
	<input
		type="hidden"
		id="payer_id"
		name="payer_id"
		value="<?=$payer->payer_id;?>" />
		<input
		type="hidden"
		id="tour_id"
		name="tour_id"
		value="<?=$tour_id;?>" />
		<input
		type="hidden"
		id="tourist_count"
		name="tourist_count"
		value="<?=$tourist_count;?>" />
		<input
		type="hidden"
		id="room_rate"
		name="room_rate"
		value="<?=$room_rate;?>" />
		<input
		type="hidden"
		id="tour_price"
		name="tour_price"
		value="<?=$tour_price;?>" />
	<h4><?=sprintf("%s %s", $payer->first_name, $payer->last_name);?></h4>
<?if($action == "update"):?>
<h5><?=get_value($payer,"tour_name");?></h5>
<? endif;?>
<p>
		<label for="is_comp">Complementary Ticket: </label>
<?=form_checkbox("is_comp","1",get_value($payer,"is_comp","FALSE"));?>
</p>
	<p>
		<label for="is_cancelled">Cancelled: </label>
<?=form_checkbox("is_cancelled","1",get_value($payer,"is_cancelled","FALSE"));?>
</p>
	<p>
		<label for="payment_type">Payment Type</label>
<?=form_dropdown("payment_type",$payment_types, get_value($payer,"payment_type"), "class='change_payment_type'");?>
&nbsp;$<span id="tour_price_display"><?=$tour_price;?></span>
	</p>
	<p>
		<label for="room_size">Room Size</label>
<?=form_dropdown("room_size",$room_sizes,get_value($payer, "room_size"), "class='change_room_size'");?>
&nbsp;$<span id="room_rate_display"><?=$room_rate;?></span>
	</p>
	<p>
		<label for="discount">Discount:</label> &nbsp;$<input
			type="number"
			class="edit-payer-amounts money"
			name="discount"
			id="discount"
			value="<?=get_value($payer, "discount");?>" />
	</p>
	<p>
		<label for="total_cost">Total Cost: </label> &nbsp;$<span
			class="field"
			id="total_cost"><?=$total_cost;?></span>
	</p>
	<p>
		<label for="amt_paid">Amount Paid</label> $<input
			type="number"
			name="amt_paid"
			id="amt_paid"
			class="edit-payer-amounts money"
			value="<?=get_value($payer,"amt_paid");?>" />
	</p>
	<p>
		<label for="amt_due">Amount Due:</label> <span
			class="field"
			id="amt_due"><?=format_money($amt_due);?></span>
	</p>
	<p>
	<textarea id="note" name="note" style="width:90%"><?=get_value($payer, "note");?></textarea>
	</p>
	<p>
		<input
			type="submit"
			class="button"
			name="save"
			id="save"
			value="<?=ucfirst($action);?>" />
			<? if($action == "update"): ?>
			<span class="button delete delete-payer" id=<?=sprintf("delete-payer_%s_%s", $payer->payer_id, $tour_id);?>">Delete</span>
			        <? endif; ?>
	</p>
</form>
<div id="payer-tourist-block block">
	<h4>Tourists</h4>
<? $this->load->view("tourist/payer_list", $tourists);?>
<div id="mini-selector">
		<form
			name="tourist-mini-selector"
			id="tourist-mini-selector"
			method="get"
			action="">
			<input
				type="hidden"
				id="tour_id"
				name="tour_id"
				value="<?=$tour_id;?>" /> <input
				type="hidden"
				id="payer_id"
				name="payer_id"
				value="<?=$payer_id;?>" />
			<p>
				<label for="tourist-dropdown">Type the Name of the Tourist You Want
					to Add</label><br /> <input
					type="text"
					id="tourist-dropdown"
					name="tourist-dropdown"
					value="" />
			</p>
			<fieldset>
			<span class="button new small create-new-tourist">Add a New Tourist</span>
			<div id="add-new-tourist">
			</div>
			</fieldset>
		</form>
	</div>
</div>
