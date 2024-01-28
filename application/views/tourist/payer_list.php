<?php
defined('BASEPATH') or exit('No direct script access allowed');

// mini_list.php Chris Dart Dec 14, 2013 9:29:34 PM chrisdart@cerebratorium.com

?>
<ul class="list tourist-list">
	<?php
if(!empty($tourists) && is_array($tourists)):
	foreach ($tourists as $tourist) :
		$button = "";
		if ($tourist->payer_id != $tourist->person_id) :
			$button = create_button(
					[
							"text" => "Delete",
							"type" => "button",
							"class" => "button delete delete-tourist small",
							'data' => [
									'person_id' => $tourist->person_id,
									'tour_id' => $tourist->tour_id,
									'payer_id' => $tourist->payer_id,
								'target' => '#payer-tourist-block ul.tourist-list',
							],
					]);

		endif;
		?>

		<li class="diptych">
			<div>
				<?php print person_link($tourist, 'person_id'); ?>
			</div>
			<div>
				<?php print $button; ?>
			</div>
		</li>
	<?php endforeach; ?>
	<?php endif; ?>
</ul>
