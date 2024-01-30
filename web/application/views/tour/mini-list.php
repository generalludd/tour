<?php
if(empty($tours)){
	return NULL;
}
?>
<table class="list">
	<thead>
	<tr>
		<th>Tour</th>
		<th>Start</th>
		<th>End</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($tours as $tour): ?>
		<tr>
			<td><?php print $tour->tour_name; ?></td>
			<td><?php if(!empty($tour->start_date)):?><?php print format_date($tour->start_date); ?><?php endif;?></td>
			<td><?php if(!empty($tour->end_date)):?><?php print format_date($tour->end_date); ?><?php endif;?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
