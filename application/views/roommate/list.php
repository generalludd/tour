<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 31, 2013 5:54:08 PM chrisdart@cerebratorium.com
?>
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