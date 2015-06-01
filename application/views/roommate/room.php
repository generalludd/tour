<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

// room.php Chris Dart Dec 31, 2013 7:17:23 PM chrisdart@cerebratorium.com

?>
<div class="room-row column" id="room_<?=$room_number;?>">
	<h4>Room# <?=$room_number;?></h4>
	<div class="roommates-box">
		<table class="list roommates">
			<tbody>
		<? if(!empty($roommates)):?>
<? foreach($roommates as $roommate):?>
<tr>
	<td class="roommate-row"
		id="<?=sprintf("roommate_%s_%s", $room_number,  $roommate->person_id);?>">
		<a href="<?=site_url("person/view/$roommate->person_id");?>"><?=$roommate->placeholder?$roommate->placeholder:$roommate->person_name;?></a>
	</td>
	<td>
		<span
			id="<?=sprintf("delete-roommate_%s_%s", $room_number,  $roommate->person_id);?>"
			class="delete button delete-roommate"> Delete</span>
	</td>
</tr>

			<? endforeach;?>

<? endif;?>
</tbody>
		</table>
<? $buttons[] = array("text"=>"Add Roommate","type"=>"span","class"=>"button new small add-roommate","id"=>sprintf("add-roommate_%s", $room_number));?>
<?=create_button_bar($buttons);?>
</div>
</div>