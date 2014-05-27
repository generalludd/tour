<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart May 26, 2014 6:32:43 PM chrisdart@cerebratorium.com

?>
<div
	class="room-row column"
	id="room_<?=$room->id;?>">
	<h4>Room# <?=$room->room_id;?></h4>
	<?=edit_field("size",get_value($room,"size"),"Room Size","room",$room->id,array("envelope"=>"span","class"=>"dropdown","attributes"=>"menu='room_type'"));?>
	<div class="roommates-box">
		<table class="list roommates">
			<tbody>
		<? if(get_value($room,"roommates",FALSE)):?>
<? foreach($room->roommates as $roommate):?>
<tr>
					<td
						class="roommate-row"
						id="<?=sprintf("roommate_%s_%s", get_value($roommate,"room",$room->room_id),  $roommate->person_id);?>">
						<a href="<?=site_url("person/view/$roommate->person_id");?>"><?=$roommate->person_name;?></a>
					</td>
					<td><span
						id="<?=sprintf("delete-roommate_%s_%s", get_value($roommate,"room",$room->room_id),  $roommate->person_id);?>"
						class="delete button delete-roommate"> Delete</span></td>
				</tr>

			<? endforeach;?>

<? endif;?>
</tbody>
		</table>
<? $buttons[] = array("text"=>"Add Roommate","type"=>"span","class"=>"button new small add-roommate","id"=>sprintf("add-roommate_%s", $room->id));?>
<?=create_button_bar($buttons);?>
</div>
</div>