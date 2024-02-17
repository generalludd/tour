<?php defined('BASEPATH') or exit('No direct script access allowed');

?>
<table>
	<caption>
This only shows people who already have an address in the database.
	</caption>
	<?php foreach ($people as $person): ?>
		<tr>
			<td><?php print person_link($person); ?></td>
			<td><?php $button = [
						'text' => 'Select Housemate',
					'type' => 'button',
						'class' => 'button new small select-housemate',
						'data' => [
								'address_id' => $person->address_id,
								'person_id' => $person_id,
							'href' => base_url('person/update_value'),
						],
				]; ?>
				<?php print create_button($button); ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>

