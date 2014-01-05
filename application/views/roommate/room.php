<?php
defined('BASEPATH') or exit('No direct script access allowed');

// room.php Chris Dart Dec 31, 2013 7:17:23 PM chrisdart@cerebratorium.com
$open_div = "";
$close_div = "";
if(empty($roommates)){
    $open_div = sprintf("<div class='room-row column' id='room_%s'>\r<h4>Room# %s</h4>",$room, $room);
    $close_div = "</div>";
}
?>
<?=$open_div;?>
<div class="roommates-box">
	<table class="list roommates">
		<tbody>
		<? if(!empty($roommates)):?>
<? foreach($roommates as $roommate):?>
<tr>
				<td
					class="roommate-row"
					id="<?=sprintf("roommate_%s_%s", $roommate->room,  $roommate->person_id);?>">
<?=$roommate->person_name;?>
</td>
				<td><span
					id="<?=sprintf("delete-roommate_%s_%s", $roommate->room,  $roommate->person_id);?>"
					class="delete button delete-roommate"> Delete</span></td>
			</tr>

			<? endforeach;?>

<? endif;?>
</tbody>
	</table>
<? $buttons[] = array("text"=>"Add Roommate","type"=>"span","class"=>"button new add-roommate","id"=>sprintf("add-roommate_%s", $room));?>
<?=create_button_bar($buttons);?>
</div>
<?=$close_div;