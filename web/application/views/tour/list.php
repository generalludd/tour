<?php

defined('BASEPATH') or exit('No direct script access allowed');
if(empty($title)){
	return NULL;
}
$row_class = "odd";


// list.php Chris Dart Dec 14, 2013 3:18:33 PM chrisdart@cerebratorium.com
	$buttons[] = [
		'text' => 'Create Tour',
		'href' => base_url('tours/create'),
		'class' => ['button', 'new', 'dialog'],
		'id' => 'tour',
	];
	$buttons['show_tours'] = [
		'href' => base_url('tours/view_all?archived=' . $archived),
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

?>
<?php if (!empty($tours)) : ?>
	<table class="list">
		<thead>
		<tr>
			<th>Tour</th>
			<th>Start</th>
			<th>End</th>
			<th>Payment Due</th>
				<th colspan="3">Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($tours as $tour):?>
		<tr class="<?php print $row_class; ?>">
			<td>

				<a href="<?php print site_url("tours/view/$tour->id"); ?>">
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

				<td><a class="button show-hotels small"
							 href="<?php print site_url("/hotel/view_for_tour/$tour->id"); ?>">Hotels</a>
				</td>
				<td><a class="button show-tourists small"
							 href="<?php print site_url("/tourist/view_all/$tour->id"); ?>">Tourists</a>
				</td>
				<td><a class="button show-letters small dialog"
							 href="<?php print site_url("/tours/letters/$tour->id"); ?>">Letter
						Templates</a></td>
				</tr>
			<?php
			if ($row_class == "odd") {
				$row_class = "even";
			}
			else {
				$row_class = "odd";
			}
endforeach;
		?>
		</tbody>
	</table>
<?php endif; ?>
