<?php

if (empty($duplicates) || empty($source_id)) {
	return NULL;
}
?>
<table class="list">
	<thead>
		<tr>
			<th>Name</th>
			<th>Status</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($duplicates as $duplicate): ?>
		<?php $buttons = []; ?>
			<tr <?php if($duplicate->status != 1):?> class="highlight"<?php endif;?>>
				<td><?php print $duplicate->first_name . ' ' . $duplicate->last_name; ?></td>
				<td><?php print $duplicate->status?"✅":"🚫"; ?></td>
				<?php if(!empty($duplicate->address)):?>
				<td><?php print format_address($duplicate->address);?></td>
				<?php endif;?>
				<td>
					<?php
					$buttons[] = [
							'text' => 'View',
							'href' => site_url('person/view/' . $duplicate->id),
							'class' => ['button', 'view-person'],
							'title' => 'view this person',

					];

					$buttons[] = [
						'text' => 'Start Merge',
						'href' => site_url('person/start_merge/'.  $source_id  .'/' . $duplicate->id),
						'class' => ['button', 'delete'],
						'title' => 'merge this record with the current person',
					];
					?>
				<?php print create_button_bar($buttons);?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

