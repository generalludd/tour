<?php
defined('BASEPATH') or exit('No direct script access allowed');

// payer.php Chris Dart Dec 14, 2013 6:32:48 PM chrisdart@cerebratorium.com

$tourist_count = count($tourists);
$total_cost = ($tour_price - $payer->discount + $room_rate) * $tourist_count;
$amt_due = $total_cost - $payer->amt_paid;
?>
<h2>Ticket Details</h2>

<div class="block triptych">
	<fieldset
			id="payer-editor-block"
			class="field-box">

		<legend>Ticket Summary</legend>
		<p><label>Payer: </label><?php print person_link($payer, 'payer_id'); ?>
		</p>
		<?php if ($action == "update"): ?>
			<p>
				<a
						href="#"
						class="save-payer-edits"
						title="Save and return to tourist list"><?php print get_value($payer, "tour_name"); ?></a>
			</p>

		<?php endif; ?>
		<form
				name="payer-editor"
				id="payer-editor"
				method="post"
				action="<?php print base_url('index.php/payer/' . $action); ?>">
			<?php $hidden_fields = [
					'payer_id',
					'tour_id',
					'tourist_count',
					'room_rate',
					'tour_price',
			]; ?>
			<?php foreach ($hidden_fields as $field): ?>
				<?php $data = [
						'id' => $field,
						'attributes' => [
								'type' => 'hidden',
								'value' => ${$field},
						],
				]; ?>
				<?php $this->load->view('elements/input-field', $data); ?>
			<?php endforeach; ?>

			<p>
				<label for="is_comp">Complementary Ticket: </label>
				<?php $is_comp = get_value($payer, 'is_comp') == 1; ?>
				<?php print form_checkbox('is_comp', '1', $is_comp); ?>
			</p>
			<p>
				<label for="is_cancelled">Cancelled: </label>
				<?php $is_canceled = get_value($payer, 'is_cancelled') == 1; ?>
				<?php print form_checkbox('is_cancelled', '1', $is_canceled); ?>
			</p>
			<?php $payment_select = [
					'id' => 'payment_type',
					'attributes' => [
							'required' => TRUE,
					],
					'options' => $payment_types,
					'selected' => get_value($payer, 'payment_type'),
					'classes' => ['change_payment_type'],
					'label' => 'Payment Type',
					'wrapper' => 'p',
					'suffix' => '$<span
					id="tour_price_display">' . $tour_price . '</span>',
			];
			$this->load->view('elements/select-field', $payment_select);
			?>
			<p>
				<label for="room_size">Room Size</label>
				<?php print form_dropdown('room_size', $room_sizes, get_value($payer, 'room_size'), 'class="change_room_size"'); ?>
				&nbsp;$<span
						id="room_rate_display"><?php print $room_rate; ?></span>
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
						value="<?php print $amount; ?>"
						readonly/>
			</p>
			<p>
				<label for="discount">Total Price Discount:</label>
				&nbsp;$<input
						type="number"
						class="edit-payer-amounts money"
						name="discount"
						id="discount"
						value='<?php print get_value($payer, 'discount'); ?>'/>
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
						style='width: 90%'><?php print get_value($payer, 'note'); ?></textarea>
			</p>
			<p>

				<?php $buttons[] = [
						'text' => sprintf('<input type="submit" class="button edit" name="save" value="%s"/>', ucfirst($action)),
						'type' => 'pass-through',
				]; ?>
				<?php if ($action == 'update'): ?>
					<?php $buttons[] = [
							'text' => 'Cancel',
							'class' => 'button cancel cancel-payer-edit',
							'title' => 'Cancel the changes to the above payment data.',
							'data' => ['tour_id' => $tour_id],
					]; ?>

					<?php if($amt_due === 0 && (empty($payer->amt_paid) || $payer->amt_paid == 0) ) {
						$buttons[] = [
								'text' => 'Delete Payer',
								'title' => 'Completely delete this payer, payment, rooming, and tourist info for this payer',
								'class' => 'button delete dialog',
							'href' => base_url('payer/delete?tour_id=' . $tour_id . '&payer_id=' . $payer_id),
								'data' => [
										'tour_id' => $tour_id,
										'payer_id' => $payer_id,
								],
						];
					} ?>

				<?php endif; ?>
				<?php print create_button_bar($buttons); ?>

			</p>
		</form>
	</fieldset>
	<fieldset
			id="payment-list-block"
			class="field-box">
		<legend>Payment Details</legend>
		<?php

		$payment_data['payments'] = $payer->payments;
		$payment_data['tour_id'] = $tour_id;
		$payment_data['payer_id'] = $payer_id;

		$this->load->view('payment/list', $payment_data);
		$this->load->view('payment/reimbursement', $payment_data);

		?>
	</fieldset>

	<fieldset
			id="payer-tourist-block"
			class="field-box">
		<legend>Tourists</legend>
		<?php $this->load->view('tourist/payer_list', $tourists); ?>
		<div id="mini-selector">
			<p>Type the name of a person <i>already in the address book</i> you
				want to add to this
				ticket. If no one is in the list, you will have an option to add
				them.</p>
			</p>
			<div id="tourist-dropdown-block">
				<?php $field_data = [
						'field_name' => 'search-tourists',
						'data' => [
								'url' => base_url('tourist/find_by_name/' . $tour_id . '/' . $payer_id),
								'target' => '#search-list',
						],
						'placeholder' => 'Search for Tourists',

				];
				$this->load->view('person/search-field', $field_data);
				?>
			</div>
			<div id="add-new-tourist"></div>
		</div>
	</fieldset>
</div>
