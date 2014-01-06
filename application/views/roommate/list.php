<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 31, 2013 5:54:08 PM chrisdart@cerebratorium.com

$buttons["add_room"] = array("text"=>"Add Room", "class" => "button new add-room", "id"=>sprintf("add-room_%s_%s",$tour_id, $stay), "title"=> "Add a room for this tour and stay");
$buttons["add_stay"] = array("text"=>"Add Stay (Hotel)", "class" =>"button new add-hotel","id"=>sprintf("add-stay_%s_%s", $tour_id, $stay), "title" => "Add another stay (day) to the tour by adding a hotel");
?>
<h3><?=sprintf("Roommates for Tour: %s, Stay: %s",$hotel->tour_name, $stay);?></h3>
<div class="block hotel-info info-block" id="hotel-info" style="clear:both">
<label>Hotel:&nbsp;</label><a href="<?=site_url("hotel/view/$hotel->id");?>"><?=$hotel->hotel_name?></a>,&nbsp;
<label>Arrival Date:&nbsp;</label><?=format_date(get_value($hotel,"arrival_date"));?>,&nbsp;
<label>Departure Date:&nbsp;</label><?=format_date(get_value($hotel,"departure_date"));?>
<?=create_button_bar($buttons);?>
</div>
<input type="hidden" id="stay" name="stay" value="<?=$stay;?>"/>
<input type="hidden" id="tour_id" name="tour_id" value="<?=$tour_id;?>"/>
<div class="block" id="roommate-list-block">
<? for($i=1;$i<=count($rooms);$i++): ?>
<div class="room-row column" id="room_<?=$i;?>">
<h4>Room# <?=$i;?></h4>
<? $data["roommates"] = $rooms[$i];
$data["room"] = $i;
 $this->load->view("roommate/room",$data);?>
</div>
<? endfor;?>
</div>