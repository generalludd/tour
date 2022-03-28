<?php
defined('BASEPATH') or exit('No direct script access allowed');

// mini_list.php Chris Dart Dec 14, 2013 9:29:34 PM chrisdart@cerebratorium.com

?>
<table class="list" id="payer-tourist-list">
	<tbody>
	<?php
if(!empty($tourists) && is_array($tourists)):
	foreach ($tourists as $tourist) :
		$button = "";
		if ($tourist->payer_id != $tourist->person_id) :
			$button = create_button(
					[
							"text" => "Delete",
							"type" => "span",
							"class" => "button delete delete-tourist",
							'data' => [
									'person_id' => $tourist->person_id,
									'tour_id' => $tourist->tour_id,
									'payer_id' => $tourist->payer_id,
							],
					]);

		endif;
		?>
		<tr>
			<td><?php print person_link($tourist, 'person_id'); ?>
			</td>
			<td><?php print $button; ?></td>
		</tr>
	<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
</table>
