<?php

if (empty($letter) || empty($tour) || empty($payer)) {
	return;
}
?>


<div class="remittance-slip">
	<div class="return-policy  remittance-slip--details">
		<?php if (get_value($letter, "cancellation", FALSE)): ?>
			<div class="label"><?php print $tour->tour_name; ?> Cancellation Policy
			</div>
			<div class="cancellation">
				<?php print $letter->cancellation; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="tourist-details remittance-slip--details">
		<div class="tourists list-wrapper">
			<div class="label">Tourists:</div>
			<?php if (!empty($payer->tourists)): ?>
				<ul>
					<?php foreach ($payer->tourists as $tourist): ?>
							<li><?php print $tourist->first_name; ?> <?php print $tourist->last_name; ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<a title="Edit payment" class="no-print"
				 href="/payer/edit?payer_id=<?php print $payer->payer_id; ?>&tour_id=<?php print $payer->tour_id; ?>">Edit
				payment
				for <?php print $payer->first_name . ' ' . $payer->last_name; ?> </a>
		</div>
		<div class="payments ">
			<div class="amt-paid">
				<div class="label">Amount Paid:
				</div><?php print format_money($payer->amount_paid); ?>
			</div>
			<div class="amt-due">
				<div class="label">Amount Due:
				</div><?php print format_money($payer->amount_due); ?>
			</div>
			<div class="due-date">
				<div class="label">Due Date:
				</div><?php print format_date($tour->due_date); ?>
			</div>
		</div>
	</div>
	<?php if (!empty($payer->roommates)): ?>
	<div class="roommates remittance-slip--details">
		<div class="label">Roommates</div>
	</div>
	<?php print $payer->roommates; ?>
</div>
<?php endif; ?>
<div class="additional-details remittance-slip--details">
	<div class="special-request">
		<label>Special Requests: </label>
	</div>
	<div class="shirt-sizes list-wrapper">
		<div class="label">Shirt Sizes</div>
		<ul>
			<?php foreach ($payer->tourists as $tourist): ?>
				<?php if (!empty($tourist->shirt_size)): ?>
					<li><?php print sprintf("%s: %s", $tourist->first_name, $tourist->shirt_size); ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<div class="payer-details">


</div>
