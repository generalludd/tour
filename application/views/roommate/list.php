<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 31, 2013 5:54:08 PM chrisdart@cerebratorium.com

$button = array("text"=>"Details","href"=>site_url("hotel/view/$hotel->id"),"class"=>"button","title"=>"View details about this hotel");
?>
<h3><?=sprintf("Roommates for Tour: %s, Stay: %s",$hotel->tour_name, $stay);?></h3>
<div class="block hotel-info info-block" id="hotel-info" style="clear:both">
<label>Hotel:&nbsp;</label><?=$hotel->hotel_name?>,&nbsp;
<label>Arrival Date:&nbsp;</label><?=format_date(get_value($hotel,"arrival_date"));?>,&nbsp;
<label>Departure Date:&nbsp;</label><?=format_date(get_value($hotel,"departure_date"));?>
<?=create_button($button);?>
</div>
<input type="hidden" id="stay" name="stay" value="<?=$stay;?>"/>
<input type="hidden" id="tour_id" name="tour_id" value="<?=$tour_id;?>"/>
<? for($i=1;$i<=count($rooms);$i++): ?>
<div class="room-row column" id="room_<?=$i;?>">
<h4>Room# <?=$i;?></h4>
<? $data["roommates"] = $rooms[$i];
$data["room"] = $i;
 $this->load->view("roommate/room",$data);?>
</div>
<? endfor;