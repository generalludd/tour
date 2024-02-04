<?php defined('BASEPATH') OR exit('No direct script access allowed');

// mini_list.php Chris Dart Dec 16, 2013 7:44:01 PM chrisdart@cerebratorium.com

?>
<table class="list">
	<?php foreach ($people as $person): ?>
		<tr>
			<td><?php print sprintf('%s %s', $person->first_name, $person->last_name); ?></td>
			<td>
				<a href="<?php print site_url('person/view/' . $person->id); ?>"
					 class="button small">Show</a>
			</td>
			<td>
				<?php print create_button([
					'text' => 'Join Tour',
					'class' => 'button small select-tour add',
					'href' => base_url('tours/show_current/' . $person->id),
				]); ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>

