<?php defined('BASEPATH') OR exit('No direct script access allowed');

// mini_list.php Chris Dart Dec 16, 2013 7:44:01 PM chrisdart@cerebratorium.com

?>
<table>
	<tbody>
	<?php foreach ($people as $person): ?>
		<tr>
			<td><?php print sprintf("%s %s", $person->first_name, $person->last_name); ?></td>
			<td><span class="button mini select_for_tour"
								data-person_id="<?php print $person->id;?>"
								data-payer_id="<?php print $payer_id; ?>"
								data-tour_id="<?php print $tour_id;?>">Select</span>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<td>No One Found</td>
		<td>
			<span class="button new small create-new-tourist">Add a New Person</span>
		</td>
</table>


