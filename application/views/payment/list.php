<?php defined('BASEPATH') OR exit('No direct script access allowed');

// list.php Chris Dart Mar 9, 2014 7:27:03 PM chrisdart@cerebratorium.com
$total_paid = 0;
$buttons['add_payment'] = [
	'text' => 'Add Payment',
	'class' => 'button add-payment new',
	'href' => base_url('payment/create/?tour_id=' . $tour_id . '&payer_id=' . $payer_id . '&type=payment'),
];
//$done[] = array("text"=>"Done", "class"=>"button", "href"=>site_url("payer/edit?payer_id=$payer_id&tour_id=$tour_id"));
?>
<div id="payments">
	<table id="payment-list">
		<thead>
		<tr>
			<th>
				Date
			</th>
			<th>
				Amount
			</th>
			<th>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($payments as $payment): ?>
			<?php if ($payment->amount > 0): ?>
				<tr id="<?php printf("payment-row_%s", $payment->id); ?>">
					<td>
						<?php print format_date($payment->receipt_date, "standard"); ?>
					</td>
					<td>
						<?php print format_money($payment->amount, "standard"); ?>
						<?php $total_paid += $payment->amount; ?>
					</td>
					<td>
						<?php print create_button_bar([
							[
								"text" => "Delete",
								"class" => "delete-payment delete button small",
								"id" => sprintf("delete-payment_%s", $payment->id),
								"type" => "span",
							],
						]); ?>
					</td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		</tbody>
		<tfoot>
		<tr>
			<td>
				Total Paid:
			</td>
			<td>
				<?php print format_money($total_paid, "standard"); ?>
			</td>
		</tr>
		</tfoot>
	</table>
	<input type="hidden" id="total-paid" value="<?php print $total_paid; ?>"/>
	<div style="padding-top: 1em;">
		<?php print create_button_bar($buttons); ?>
	</div>
</div>
