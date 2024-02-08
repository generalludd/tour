<?php
defined('BASEPATH') or exit ('No direct script access allowed');

// room.php Chris Dart Dec 31, 2013 7:17:23 PM chrisdart@cerebratorium.com

?>
<div class="room-row" id="room_<?php print $room_number; ?>">
	<h4>Room# <?php print $room_number; ?></h4>
	<div class="roommates-box">
		<table class="list roommates" data-room_id="<?php print $room_number;?>">
			<tbody>
			<?php if (!empty($roommates)): ?>
				<?php foreach ($roommates as $roommate): ?>
					<?php $roommate_class = "roomate-row"; ?>
					<?php if ($roommate->person_id < 1): ?>
						<?php $roomate_class .= " placeholder" ?>
					<?php endif; ?>
					<tr>
						<td class="<?php print $roomate_class; ?>"
								id="<?php print sprintf("roommate_%s_%s", $room_number, $roommate->person_id); ?>">
							<a
								href="<?php print site_url("person/view/$roommate->person_id"); ?>"><?php print $roommate->placeholder ? $roommate->placeholder : $roommate->person_name; ?></a>
						</td>
						<td>
		<span
			id="<?php print sprintf("delete-roommate_%s_%s", $room_number, $roommate->person_id); ?>"
			class="delete button delete-roommate"> Delete</span>
						</td>
					</tr>

				<?php endforeach; ?>

			<?php endif; ?>
			</tbody>
		</table>
		<?php $buttons[] = [
			"text" => "Add Roommate",
			"href" => base_url('roommate/get_roomless_menu/' .$tour_id . '/' . $stay . '/'. $room_number),
			"class" => "button new small add-roommate",
			'data' => [
				'tour_id' => $tour_id,
				'stay' => $stay,
				'room_id' =>$room_number,
			]	,
			"id" => sprintf("add-roommate_%s", $room_number),
		]; ?>
		<?php print create_button_bar($buttons); ?>
	</div>
</div>
