<?php
defined('BASEPATH') or exit('No direct script access allowed');

// payer.php Chris Dart Dec 14, 2013 6:32:48 PM chrisdart@cerebratorium.com
$tourist_count = count($tourists);
$total_cost = ($tour_price - $payer->discount + $room_rate) * $tourist_count;
$amt_due = $total_cost - $payer->amt_paid;
?>
<div class="block header">
	<h4>
		Payer: <?php print sprintf("%s %s", $payer->first_name, $payer->last_name); ?></h4>
	<?php if ($action == "update"): ?>
		<h5>
			Tour: <a
				href="#"
				class="save-payer-edits"
				title="Save and return to tourist list"><?php print get_value($payer, "tour_name"); ?></a>
		</h5>

	<?php endif; ?>
</div>
<div
	id="payer-editor-block"
	class="block triptych field-box">
	<h4>Ticket Details</h4>
	<form
		name="payer-editor"
		id="payer-editor"
		method="post"
		action="<?php print base_url("index.php/payer/$action"); ?>">
		<input
			type="hidden"
			id="payer_id"
			name="payer_id"
			value="<?php print $payer_id; ?>"/> <input
			type="hidden"
			id="tour_id"
			name="tour_id"
			value="<?php print $tour_id; ?>"/>
		<input
			type="hidden"
			id="tourist_count"
			name="tourist_count"
			value="<?php print $tourist_count; ?>"/> <input
			type="hidden"
			id="room_rate"
			name="room_rate"
			value="<?php print $room_rate; ?>"/> <input
			type="hidden"
			id="tour_price"
			name="tour_price"
			value="<?php print $tour_price; ?>"/>
		<p>
			<label for="is_comp">Complementary Ticket: </label>
			<?php print form_checkbox("is_comp", "1", get_value($payer, "is_comp", "FALSE")); ?>
		</p>
		<p>
			<label for="is_cancelled">Cancelled: </label>
			<?php print form_checkbox("is_cancelled", "1", get_value($payer, "is_cancelled", "FALSE")); ?>
		</p>
		<p>
			<label for="payment_type">Payment Type</label>
			<?php print form_dropdown("payment_type", $payment_types, get_value($payer, "payment_type"), "class='change_payment_type'"); ?>
			&nbsp;$<span id="tour_price_display"><?php print $tour_price; ?></span>
		</p>
		<p>
			<label for="room_size">Room Size</label>
			<?php print form_dropdown("room_size", $room_sizes, get_value($payer, "room_size"), "class='change_room_size'"); ?>
			&nbsp;$<span id="room_rate_display"><?php print $room_rate; ?></span>
		</p>
		<p>
			<label for="total_cost">Total Cost: </label> &nbsp;$<span
				class="field"
				id="total_cost"><?php print $total_cost; ?></span>
		</p>
		<p>
			<label for="amt_paid">Amount Paid</label> $<input
				type="number"
				name="amt_paid"
				id="amt_paid"
				readonly
				class="edit-payer-amounts money"
				value="<?php echo $amount; ?>"
				readonly/>
		</p>
		<p>
			<label for="discount">Total Price Discount:</label> &nbsp;$<input
				type="number"
				class="edit-payer-amounts money"
				name="discount"
				id="discount"
				value="<?php print get_value($payer, "discount"); ?>"/>
		</p>
		<p>
			<label for="amt_due">Amount Due:</label> $<span
				class="field"
				id="amt_due"><?php print $total_cost - $amount; ?></span>
		</p>
		<p>
			<label for="note">Note</label><br/>
			<textarea
				id="note"
				name="note"
				style="width: 90%"><?php print get_value($payer, "note"); ?></textarea>
		</p>
		<p>

			<?php $buttons[] = [
				"text" => sprintf("<input type='submit' class='button edit' name='save' value='%s'/>", ucfirst($action)),
				"type" => "pass-through",
			]; ?>
			<?php if ($action == "update"): ?>
				<?php $buttons[] = [
					"text" => "Cancel",
					"class" => "button cancel cancel-payer-edit",
					"title" => "Cancel the changes to the above payment data.",
					'data' => ['tour_id' => $tour_id],
				]; ?>
				<?php $buttons[] = [
					"text" => "Delete Payer",
					"title" => "Completely delete this payer, payment, rooming, and tourist info for this payer",
					"class" => "button delete delete-payer",
					'data' => [
						'tour_id' => $tour_id,
						'payer_id' => $payer->payer_id,
					],
					"id" => sprintf("delete-payer_%s_%s", $payer->payer_id, $tour_id),
				]; ?>

			<?php endif; ?>
			<?php print create_button_bar($buttons); ?>

		</p>
	</form>
</div>
<div class="block triptych">
	<div
		id="payment-list-block"
		class="block field-box">
		<h4>Payment Details</h4>
		<?php

		$payment_data["payments"] = $payer->payments;
		$payment_data["tour_id"] = $payer->tour_id;
		$payment_data["payer_id"] = $payer->payer_id;

		$this->load->view("payment/list", $payment_data);
		$this->load->view("payment/reimbursement", $payment_data);

		?>
	</div>
</div>
<div
	id="payer-tourist-block"
	class="block triptych field-box">
	<h4>Tourists</h4>
	<?php $this->load->view("tourist/payer_list", $tourists); ?>
	<div id="mini-selector">
		<form
			name="tourist-mini-selector"
			id="tourist-mini-selector"
			method="get"
			action="">
			<p>
				<label for="tourist-dropdown">Type the name of a person <i>already
						in the address book</i> you want to add to this ticket. If no one
					is in the list, you will have an option to add them.
				</label>
			</p>
			<div id="tourist-dropdown-block">
				<input
					type="text"
					id="tourist-dropdown"
					name="tourist-dropdown"
					data-payer_id="<?php print $payer_id; ?>"
					data-tour_id="<?php print $tour_id; ?>"
					placeholder="Find a tourist"
					value=""/>
			</div>
		</form>
		<div id="add-new-tourist"></div>
	</div>
</div>
