<?php defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart May 23, 2014 7:48:34 PM chrisdart@cerebratorium.com

$roommate_count = count($roommates);

$max_roommates = get_room_size($payer->room_size);
if ($roommate_count == 0) {
    $buttons["add_room"] = array(
            "text" => "Add Room",
            "class" => "button new small add-room",
            "id" => sprintf("add-room_%s_%s", $payer->payer_id, $payer->tour_id),
    );
    print create_button_bar($buttons);
}elseif($max_roommates - $roommate_count > 0){
    $buttons["add_roommate"] = array(
            "text" => "Add Roommate",
            "class" => "button new small add-roommate",
            "id" => sprintf("add-roommate_%s_%s", $payer->payer_id, $roommates[0]->room_id),
    );
    print create_button_bar($buttons);

} ?>
<table class="roommate-list list">


<? foreach($roommates as $roommate):?>
<tr>
<td><?=sprintf("%s %s",$roommate->first_name,$roommate->last_name);?></td>
<td><?=create_button(array("text"=>"Delete","class"=>"button delete small roommate-delete","id"=>sprintf("roommate-delete_%s_%s",$roommate->room_id, $roommate->person_id), "selection"=>"roommates" )); ?>
</td>
</tr>


<? endforeach; ?>
</table>