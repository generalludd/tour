<?php

defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 14, 2013 3:18:33 PM chrisdart@cerebratorium.com
if (empty($for_tourist)) {
	$buttons[] = [
		'text' => 'Create Tour',
		'href' => base_url('tour/create'),
		'class' => ['button', 'new', 'dialog'],
		'id' => 'tour',
	];
	$buttons['show_tours'] = [
		'href' => base_url('tour/view_all?archived=' . $archived),
		'class' => ['button'],
		'id' => 'tour',
	];
	if (empty($archived)) {
		$buttons['show_tours']['text'] = 'Show Current Tours';
	}
	else {
		$buttons['show_tours']['text'] = 'Show Past Tours';
	}
	print create_button_bar($buttons);
}
?>
<?php if (!empty($tours)) : ?>
	<table class="list">
		<caption><?php print $title; ?></caption>
		<thead>
		<tr>
			<th>Tour</th>
			<th>Start</th>
			<th>End</th>
			<th>Payment Due</th>
			<?php if ($for_tourist) : ?>
				<th>Amt Paid</th>
			<?php endif; ?>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$row_class = "odd";

		foreach ($tours as $tour) {
			?>
		<tr class="<?php print $row_class; ?>">
			<td>

				<a href="<?php print site_url("tour/view/$tour->id"); ?>">
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
			<?php if ($for_tourist) : ?>
				<td>
					<?php if(!empty($tour->start_date) && !empty($tour->amt_due)) : ?>
						<?php print format_money($tour->amt_due); ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if(!empty($tour->end_date) && strtotime($tour->end_date) > strtotime('today')) : ?>
					<a
						href="<?php print site_url("payer/edit/?payer_id=$tour->payer_id&tour_id=$tour->tour_id"); ?>"
						class="button edit">Edit Payment</a>
					<?php endif; ?>
				</td>
				<td><a class="button show-tourists mini"
							 href="<?php print site_url("/tourist/view_all/$tour->id"); ?>">Tourists</a>
				</td>
			<?php else : ?>
				<td><a class="button show-hotels small"
							 href="<?php print site_url("/hotel/view_for_tour/$tour->id"); ?>">Hotels</a>
				</td>
				<td><a class="button show-tourists small"
							 href="<?php print site_url("/tourist/view_all/$tour->id"); ?>">Tourists</a>
				</td>
				<td><a class="button show-letters small dialog"
							 href="<?php print site_url("/tour/letters/$tour->id"); ?>">Letter
						Templates</a></td>
				</tr>
			<?php
			endif;
			if ($row_class == "odd") {
				$row_class = "even";
			}
			else {
				$row_class = "odd";
			}
		}

		?>
		</tbody>
	</table>
<?php elseif ($for_tourist) : ?>
	<p>There are no tours on record for this person. Click on "Join Tour" to add
		this person to a current tour.</p>
<?php endif;
