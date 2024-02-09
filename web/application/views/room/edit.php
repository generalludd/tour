<?php
defined('BASEPATH') or exit ('No direct script access allowed');
if(empty($room)){
	return FALSE;
}

if(empty($stay)){
	$stay = $room->stay;
}

// edit.php Chris Dart May 26, 2014 6:32:43 PM chrisdart@cerebratorium.com

?>
<div class="room-row" id="room_<?php print $room->id; ?>"  data-room_id="<?php print $room->id;?>">
	<?php print edit_field("size", get_value($room, "size"), "Room Size", "room", $room->id, [
		"envelope" => "span",
		"class" => "dropdown",
		"attributes" => "menu='room_type'",
	]); ?>
	<div class="roommates-box">
		<table class="list roommates">
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
						<button
							data-room_id="<?php print $room->id; ?>"
							data-person_id="<?php print $roommate->person_id; ?>"
							data-tour_id="<?php print $room->tour_id; ?>"
							id="<?php print sprintf("delete-roommate_%s_%s", get_value($roommate, "room_id", $room->room_id), $roommate->person_id); ?>"
							class="delete button delete-roommate no-float">Delete</button>
						</td>
					</tr>

				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (!empty($placeholder_row)): ?>
				<tr class="new-row">
					<td colspan="2">
						<?php if(!empty($placeholder_row->roomless)):?>
						<label>
							<select class="roomless-tourists" data-stay="<?php print $room->stay; ?>"
											data-tour_id="<?php print $room->tour_id; ?>"
											data-room_id="<?php print $room->id; ?>"
											data-href="<?php print site_url('roommate/insert_row'); ?>">
								<?php foreach ($placeholders as $key => $placeholder): ?>
									<option value="<?php print $key; ?>"><?php print $placeholder; ?></option>
								<?php endforeach; ?>
						</label>
						<?php endif;?>

						<a href="<?php print site_url('/roommate/add_placeholder?tour_id=' . $room->tour_id . '&stay=' . $stay . '&room_id=' . $room->id); ?>"
							 class="add-placeholder link add">Add Placeholder</a></td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
		<?php print create_button_bar([
			[
				"text" => "Add Roommate",
				'data' => [
					'tour_id' => $room->tour_id,
					'stay' => $stay,
					'room_id' =>$room->id,
]	,
				"href" => base_url('roommate/get_roomless_menu?tour_id=' .$room->tour_id . '&stay=' . $stay . '&room_id='. $room->id. '&ajax=1'),
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
