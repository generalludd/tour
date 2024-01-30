<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 31, 2013 5:54:50 PM chrisdart@cerebratorium.com
?>
<form name="roommate-editor" id="roommate-editor" action="<?php print site_url("roomate/$action");?>" method="post">
<input type="hidden" id="tour_id" name="tour_id" value="<?php print $tour_id;?>"/>
<input type="hidden" id="stay" name="stay" value="<?php print $stay;?>"/>
<div>
<label for="room">Room Number</label>
<input type="number" id="room" name="room" value="<?php print get_value($roommate, "room", $room);?>"/>
</div>
<div>
<label for="person_id">Person</label>
<?php print form_dropdown("person_id", $roommates, get_value($roommate, "person_id"), "id='person_id'");?>
</div>

</form>