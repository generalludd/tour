<?php

defined('BASEPATH') or exit('No direct script access allowed');
// select_list.php Chris Dart Dec 20, 2013 7:43:44 PM
// chrisdart@cerebratorium.com

?>
<div>
	Select the payer below as the host for <?php print $tourist_name; ?> on the
	tour <?php print $tour_name; ?>
	<br/>
	If the payer is not in this list, you must add them first before adding
	tourists
</div>
<table class="list">
	<?php foreach ($payers as $payer): ?>
		<tr>
			<td>
				<?php print sprintf("%s %s", $payer->first_name, $payer->last_name); ?>
			</td>
			<td>
				<?php print create_button([
					'text' => 'Select',
					'class' => 'button mini insert-tourist',
					'href' => base_url('tourist/insert'),
					'data' =>
						[
							'tourist_id' => $tourist_id,
							'payer_id' => $payer->payer_id,
							'tour_id' => $tour_id,
						],
				]); ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
