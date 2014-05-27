<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart May 26, 2014 6:32:43 PM chrisdart@cerebratorium.com

?>
<div
	class="room-row column"
	id="room_<?=$room->room_id;?>">
	<h4>Room# <?=$room->room_id;?></h4>
	<label for="size">Room Size: </label><?=form_dropdown("size",$sizes,get_value($room,"size"),"id='size'");?>

	<div class="roommates-box">
		<table class="list roommates">
			<tbody>
		<? if(!empty($rooms)):?>
<? foreach($rooms as $roommate):?>
<tr>
					<td
						class="roommate-row"
						id="<?=sprintf("roommate_%s_%s", get_value($roommate,"room",$room_id),  $roommate->person_id);?>">
						<a href="<?=site_url("person/view/$roommate->person_id");?>"><?=$roommate->person_name;?></a>
					</td>
					<td><span
						id="<?=sprintf("delete-roommate_%s_%s", get_value($roommate,"room",$room_id),  $roommate->person_id);?>"
						class="delete button delete-roommate"> Delete</span></td>
				</tr>

			<? endforeach;?>

<? endif;?>
</tbody>
		</table>
<? $buttons[] = array("text"=>"Add Roommate","type"=>"span","class"=>"button new small add-roommate","id"=>sprintf("add-roommate_%s", $room_id));?>
<?=create_button_bar($buttons);?>
</div>
</div>