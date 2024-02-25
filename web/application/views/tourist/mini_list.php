<?php

if (empty($people) || empty($payer_id) || empty($tour_id) || empty($name)) {
	return FALSE;
}

?>
<table>
	<tbody>
	<?php foreach ($people as $person): ?>
		<tr>
			<td><?php print $person->first_name . ' ' . $person->last_name; ?></td>
			<td>
				<form action="/tourist/insert" method="post">
					<input type="hidden" name="tourist_id"
								 value="<?php print $person->id; ?>"/>
					<input type="hidden" name="payer_id"
								 value="<?php print $payer_id; ?>"/>
					<input type="hidden" name="tour_id" value="<?php print $tour_id; ?>"/>
					<input type="submit" class="button mini" value="Select"/>
				</form>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<td>Not found...</td>
		<td>
			<span class="button new small create-new-tourist"
						data-payer_id="<?php print $payer_id; ?>"
						data-tour_id="<?php print $tour_id; ?>"
						data-name="<?php print str_ireplace('%', ' ', $name); ?>">Add <?php print str_ireplace('%', ' ', $name);; ?></span>

		</td>
</table>

