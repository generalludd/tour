<?php
defined('BASEPATH') or exit ('No direct script access allowed');

// edit.php Chris Dart May 26, 2014 6:32:43 PM chrisdart@cerebratorium.com

?>
<div class="room-row" id="room_<?php print $room->id; ?>">
	<?php print edit_field("size", get_value($room, "size"), "Room Size", "room", $room->id, [
		"envelope" => "span",
		"class" => "dropdown",
		"attributes" => "menu='room_type'",
	]); ?>
	<div class="roommates-box">
		<table class="list roommates" data-room_id="<?php print $room->id;?>">
			<tbody>
			<?php if (get_value($room, "roommates", FALSE)): ?>
				<?php foreach ($room->roommates as $roommate): ?>
					<?php $row_class = "roommate-row"; ?>
					<?php if ($roommate->person_id < 0): ?>
						<?php $row_class .= " placeholder"; ?>
					<?php endif; ?>
					<tr>
						<td class="<?php print $row_class; ?>"
								id="<?php print sprintf("roommate_%s_%s", get_value($roommate, "room_id", $room->room_id), $roommate->person_id); ?>">
							<?php if ($roommate->person_id > 0): ?>
								<a
									href="<?php print site_url("payer/edit/?payer_id=$roommate->payer_id&tour_id=$roommate->tour_id"); ?>">
									<?php print $roommate->person_name; ?>
								</a>
							<?php else: ?>
								<?php print $roommate->placeholder; ?>
							<?php endif; ?>
						</td>
						<td>
						<span
							id="<?php print sprintf("delete-roommate_%s_%s", get_value($roommate, "room_id", $room->room_id), $roommate->person_id); ?>"
							class="delete button delete-roommate no-float">Delete</span>
						</td>
					</tr>

				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
		<?php print create_button_bar([
			[
				"text" => "Add Roommate",
				'data' => [
					'tour_id' => $tour_id,
					'stay' => $stay,
					'room_id' =>$room->id,
]	,
				"href" => base_url('roommate/get_roomless_menu/' .$tour_id . '/' . $stay . '/'. $room->id),
				"class" => "button new small add-roommate",
			],
			[
				"text" => "Delete Room",
				"type" => "span",
				"class" => "button delete small no-float delete-room",
				"id" => sprintf("delete-room_%s", $room->id),
				"title" => "Delete room and all roommates",
			],
		]); ?>


	</div>
</div>
