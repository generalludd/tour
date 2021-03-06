<?php defined('BASEPATH') OR exit('No direct script access allowed');

// select.php Chris Dart Dec 20, 2013 7:04:26 PM chrisdart@cerebratorium.com

?>
<input type="hidden" name="person_id" id="person_id"
			 value="<?php print $id; ?>"/>
<div id="tourist-selector">

	<?php if (count($tours) >= 1): ?>
		<div style="width:60ex">
			Choose "payer" if the person is paying for the tour.<br/>Choose "tourist"
			if someone else is paying<br/>The payer must already have been added as a
			payer to the selected tour.
		</div>
		<table class="list">

			<?php foreach ($tours as $tour): ?>
				<tr>
					<td>
						<?php print $tour->tour_name; ?>
					</td>
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
		</table>
	<?php else: ?>
		<p>
			This person has already been signed up for all the available tours</p>
	<?php endif; ?>
</div>
