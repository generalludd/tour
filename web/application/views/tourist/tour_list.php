<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (empty($title)) {
	return NULL;
}

// list.php Chris Dart Dec 14, 2013 3:18:33 PM chrisdart@cerebratorium.com
?>
<?php if (!empty($tours)) : ?>
	<table class="list">
		<thead>
		<tr>
			<th>Tour</th>
			<th>Start</th>
			<th>End</th>
			<th>Payment Due</th>
			<th>Amount Paid</th>
			<th>Amount Due</th>
			<th colspan="2">Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$row_class = "odd";

		foreach ($tours

		as $tour) : ?>
		<tr class="<?php print $row_class; ?>">
			<td>

				<a href="<?php print site_url("tours/view/$tour->tour_id"); ?>">
					<?php print $tour->tour_name; ?>
				</a>
			</td>
			<td><?php if (!empty($tour->start_date)): ?>

					<?php print format_date($tour->start_date); ?>
				<?php endif; ?>
			</td>
			<td>
				<?php if (!empty($tour->end_date)): ?>
					<?php print format_date($tour->end_date); ?>
				<?php endif; ?>
			</td>
			<td>
				<?php if (!empty($tour->due_date)): ?>
					<?php print format_date($tour->due_date); ?>
				<?php endif; ?>
			</td>
			<td>
				<?php print format_money($tour->amount_paid); ?>
			</td>
			<td>
				<?php print format_money($tour->amount_due); ?>
			</td>
			<td>
				<a
					href="<?php print site_url("payer/edit/?payer_id=$tour->payer_id&tour_id=$tour->tour_id"); ?>"
					class="button edit">View Ticket</a>
			</td>


			<?php
			if ($row_class == "odd") {
				$row_class = "even";
			}
			else {
				$row_class = "odd";
			}
			endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
<p>There are no tours on record for this person. Click on "Join Tour" to add
	this person to a current tour.</p>
<?php endif; ?>
