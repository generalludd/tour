<?php defined('BASEPATH') or exit('No direct script access allowed');

// select.php Chris Dart Dec 20, 2013 7:04:26 PM chrisdart@cerebratorium.com

?>
<input type="hidden" name="person_id" id="person_id"
	   value="<?php print $id; ?>"/>
<div id="tourist-selector">
	<?php if (count($tours) >= 1): ?>
		<div style="width:60ex">
			Choose "payer" if the person is paying for the tour.<br/>Choose
			"tourist"
			if someone else is paying<br/>The payer must already have been added
			as a
			payer to the selected tour.
		</div>
		<table class="list">
			<thead>
			<tr>
				<th>Tour</th>
				<th>Start</th>
				<th>End</th>
				<th></th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($tours as $tour): ?>
				<tr>
					<td>
						<?php print $tour->tour_name; ?>
					</td>
					<td><?php print date('m/d/Y', strtotime($tour->start_date)); ?></td>
					<td>
						<?php print date('m/d/Y', strtotime($tour->end_date)); ?></td>
					<td>
						<?php print create_button([
								'text' => 'Select as Tourist',
								'class' => 'button select-as-tourist',
								'href' => base_url('payer/select_payer/' . $tour->id . '/' . $id),
						]); ?>
					</td>
					<td>
						<?php print create_button([
								'text' => 'Select as Payer',
								'class' => 'button select-as-payer',
								'href' => base_url('payer/insert'),
								'data' => [
										'tour_id' => $tour->id,
										'person_id' => $id,
								],
						]); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>
			This person has already been signed up for all the available
			tours</p>
	<?php endif; ?>
	<?php $buttons[] = [
			'text' => 'Join Past Tour',
			'href' => base_url('tours/show_missed_tours/' . $id),
			'class' => 'button new mini  select-tour',
			'title' => 'Add to a tour that happened in the past',
	];
	print create_button_bar($buttons);
	?>
</div>
