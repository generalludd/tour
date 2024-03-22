<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (empty($payer) || empty($action)) {
	return;
}
// payer.php Chris Dart Dec 14, 2013 6:32:48 PM chrisdart@cerebratorium.com
$tourist_count = count($payer->tourists);
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
				'payer_id' => $payer->payer_id,
				'tour_id' => $payer->tour_id,
				'tourist_count' => count($payer->tourists),
				'room_rate' => $payer->room_rate,
				'price' => $payer->price,
			]; ?>
			<?php foreach ($hidden_fields as $field => $value): ?>
				<?php $data = [
					'id' => $field,
					'attributes' => [
						'type' => 'hidden',
						'value' => $value,
					],
				]; ?>
				<?php $this->load->view('elements/input-field', $data); ?>
			<?php endforeach; ?>

			<p>
				<label for="is_comp">Complementary Ticket: </label>
				<?php $is_comp = get_value($payer, 'is_comp') == 1; ?>
				<?php print form_checkbox('is_comp', '1', $is_comp, [
					'class' => 'update-value',
					'data-url' => base_url('payer/update_value/' . $payer->payer_id . '/' . $payer->tour_id),
				]); ?>
			</p>
			<p>
				<label for="is_cancelled">Cancelled: </label>
				<?php $is_canceled = get_value($payer, 'is_cancelled') == 1; ?>
				<?php print form_checkbox('is_cancelled', '1', $is_canceled, [
					'data-url' => base_url('payer/update_value/' . $payer->payer_id . '/' . $payer->tour_id),
				]); ?>
			</p>
			<?php $payment_select = [
				'id' => 'payment_type',
				'attributes' => [
					'required' => TRUE,
					'data-tour-id' => $payer->tour_id,
					'data-target' => 'tour_price_display',
					'data-url' => base_url('payer/update_value/' . $payer->payer_id . '/' . $payer->tour_id),
				],
				'options' => $payment_types,
				'selected' => get_value($payer, 'payment_type'),
				'classes' => ['change_payment_type', 'change-value', 'update-value'],
				'label' => 'Payment Type',
				'wrapper' => 'p',
				'suffix' => '$<span
					id="tour_price_display">' . $payer->price . '</span>',
			];
			$this->load->view('elements/select-field', $payment_select);
			?>
			<p>
				<?php $room_size_select = [
					'id' => 'room_size',
					'attributes' => [
						'required' => TRUE,
						'data-tour-id' => $payer->tour_id,
						'data-target' => 'room_rate_display',
						'data-url' => base_url('payer/update_value/' . $payer->payer_id . '/' . $payer->tour_id),

					],
					'options' => $room_sizes,
					'selected' => get_value($payer, 'room_size'),
					'classes' => ['change_room_size', 'update-value'],
					'label' => 'Room Size',
					'wrapper' => 'p',
					'suffix' => '$<span
					id="room_rate_display">' . $payer->room_rate . '</span>',
				] ?>
				<?php $this->load->view('elements/select-field', $room_size_select); ?>
			</p>
			<p>
				<label for="total_cost">Total Cost: </label> &nbsp;$<span
					class="field"
					id="total_cost"><?php print $payer->ticket_cost; ?></span>
			</p>
			<p>
				<label for="amt_paid">Amount Paid</label> $<input
					type="number"
					name="amt_paid"
					id="amt_paid"
					class="money"
					value="<?php print $payer->amt_paid; ?>"
					readonly/>
			</p>
			<p>
				<label for="discount">Discount: - </label>
				&nbsp;$<input
					type="number"
					min="0"
					class="money update-value"
					name="discount"
					id="discount"
					data-url="<?php print base_url('payer/update_value/' . $payer->payer_id . '/' . $payer->tour_id) ?>"
					value='<?php print get_value($payer, 'discount'); ?>'/>
			</p>
			<p>
				<label for="surcharge">Surcharge: + </label>
				&nbsp;$<input
					type="number"
					class="money update-value"
					min="0"
					name="surcharge"
					id="surcharge"
					data-url="<?php print base_url('payer/update_value/' . $payer->payer_id . '/' . $payer->tour_id) ?>"
					value='<?php print get_value($payer, 'surcharge'); ?>'/>
			</p>
			<p>
				<label for="amt_due">Amount Due:</label> $<span
					class="field"
					id="amt_due"><?php print get_payment_due($payer); ?></span>
			</p>
			<p>
				<button class="update-cost-display button edit small">Re-calculate
					Values
				</button>
			</p>
			<p>
				<label for="note">Note</label><br/>
				<textarea
					id="note"
					name="note"
					data-url="<?php print base_url('payer/update_value/' . $payer->payer_id . '/' . $payer->tour_id) ?>"
					class="update-value"
					style='width: 90%'><?php print get_value($payer, 'note'); ?></textarea>
			</p>

			<p>
				<?php $buttons = []; ?>
				<?php $buttons[] = [
					'text' => 'Save',
					'title' => 'Save and return to tourist list',
					'class' => 'button save-payer-edits edit',
					'href' => base_url('tourist/view_all/' . $payer->tour_id . '#payer-' . $payer->payer_id),
				]; ?>
				<?php if ($action == 'update' && $payer->amount_due === 0 && (empty($payer->amt_paid) || $payer->amt_paid == 0)): ?>
					<?php $buttons[] = [
						'text' => 'Delete Payer',
						'title' => 'Completely delete this payer, payment, rooming, and tourist info for this payer',
						'class' => 'button delete dialog',
						'href' => base_url('payer/delete?tour_id=' . $payer->tour_id . '&payer_id=' . $payer->payer_id),
						'data' => [
							'tour_id' => $payer->tour_id,
							'payer_id' => $payer->payer_id,
						],
					]; ?>

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
		$payment_data['tour_id'] = $payer->tour_id;
		$payment_data['payer_id'] = $payer->payer_id;

		$this->load->view('payment/list', $payment_data);
		$this->load->view('payment/reimbursement', $payment_data);

		?>
	</fieldset>

	<fieldset
		id="payer-tourist-block"
		class="field-box">
		<legend>Tourists</legend>
		<?php $this->load->view('tourist/payer_list', ['tourists' => $payer->tourists]); ?>
		<div id="mini-selector">
			<p>Type the name of a person <i>already in the address book</i> you
				want to add to this
				ticket. If no one is in the list, you will have an option to add
				them.</p>
			<div id="tourist-dropdown-block">
				<?php $field_data = [
					'field_name' => 'search-tourists',
					'data' => [
						'url' => base_url('tourist/find_by_name/' . $payer->tour_id . '/' . $payer->payer_id),
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
<div class="summary">

</div>
