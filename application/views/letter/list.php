<?php defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Mar 15, 2014 12:27:43 PM chrisdart@cerebratorium.com
$buttons['add_letter'] = [
		'text' => 'Add Letter',
		'class' => 'button add-letter new',
		'href' => site_url('letter/create/' . $tour_id),
];
?>
<h4>List of Letter Templates</h4>

<?php if ($letters): ?>

<p id="letter-list-box">
	<table id="letter-list">
		<thead>
		<tr>
			<th>
				Date
			</th>
			<th>
				Title
			</th>
			<th>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($letters as $letter): ?>
			<tr>
				<td>
					<?php print format_date($letter->creation_date); ?>
				</td>
				<td>
					<?php print $letter->title; ?>
				</td>
				<td>
					<a href="<?php print site_url('letter/edit/' . $letter->id); ?>"
					   class="edit-letter edit button small">Edit</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
	<p>
		<?php print create_button_bar($buttons); ?>
	</p>
</div>
