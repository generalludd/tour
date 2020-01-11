<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 31, 2013 5:54:08 PM chrisdart@cerebratorium.com

$buttons["add_room"] = array(
        "text" => "Add Room",
        "class" => "button new add-room",
        "id" => sprintf("add-room_%s_%s", $tour_id, $stay),
        "title" => "Add a room for this tour and stay"
);
$buttons["add_stay"] = array(
        "text" => "Add Stay (Hotel)",
        "class" => "button new add-hotel",
        "id" => sprintf("add-stay_%s_%s", $tour_id, $stay),
        "title" => "Add another stay (day) to the tour by adding a hotel"
);

if ($stay > 1) {


    $previous_stay = $stay - 1;

if(count($rooms)== 0){
    $buttons["duplicate_stay"] = array(
            "text"=>"Duplcate Previous Stay",
            "class"=>"button new duplicate-previous-stay",
            "id"=>sprintf("duplicate-stay_%s_%s",$tour_id, $stay),
            "title"=>"Duplicate all the room assignments from the previous stay",

    );
}
    $buttons["previous_stay"] = array(
            "text" => "Previous Stay",
            "class" => "button previous-stay",
            "href" => site_url("roommate/view_for_tour/?tour_id=$tour_id&stay=$previous_stay")
    );


}
if ($stay < $last_stay) {
    $next_stay = $stay + 1;
    $buttons["next_stay"] = array(
            "text" => "Next Stay",
            "class" => "button next-stay",
            "href" => site_url("roommate/view_for_tour/?tour_id=$tour_id&stay=$next_stay")
    );
}
?>
<h3><?php print sprintf("Roommates for Tour: <a href='%s'>%s</a>, Stay: %s",site_url("tour/view/$tour_id"), $hotel->tour_name, $stay);?></h3>
<div
	class="block hotel-info info-block"
	id="hotel-info"
	style="clear: both">
	<label>Hotel:&nbsp;</label><a
		href="<?php print site_url("hotel/view/$hotel->id");?>"><?php print $hotel->hotel_name?></a><br/>
	<label>Arrival Date:&nbsp;</label><?php print format_date(get_value($hotel,"arrival_date"));?>,&nbsp;<?php print get_value($hotel,"arrival_time");?><br/>
<label>Departure Date:&nbsp;</label><?php print format_date(get_value($hotel,"departure_date"));?>,&nbsp;<?php print get_value($hotel,"departure_time");?><br />
<?php if(get_value($hotel, "contact",FALSE)): ?>
    <label>Contact Info: </label><?php print get_value($hotel, "contact");?>,&nbsp;
<?php endif;?>
<?php if(get_value($hotel, "phone",FALSE)): ?>
<label>Phone: </label><?php print get_value($hotel, "phone");?>&nbsp;
<?php endif;?>
<?php if(get_value($hotel, "fax",FALSE)): ?>
<label>Fax: </label><?php print get_value($hotel,"fax");?>&nbsp;
<?php endif;?>

<p><strong>Room Type Count:</strong>
<?php $room_output = array();?>
<?php foreach($room_count as $count):?>
<?php $room_output[] = sprintf("%ss: %s", format_field_name($count->size), $count->room_count);?>
<?php endforeach;?>
<?php print implode(", ", $room_output);?>
</p>
<?php print create_button_bar($buttons, array("class"=>"float"));?>
</div>
<input
	type="hidden"
	id="stay"
	name="stay"
	value="<?php print $stay;?>" />
<input
	type="hidden"
	id="tour_id"
	name="tour_id"
	value="<?php print $tour_id;?>" />
<div
	class="block"
	id="roommate-list-block">
	<?php
$room_size = "";?>

<?php foreach ($rooms as $room): ?>
   <?php if($room_size != $room->size):?>
       <h4 class='room-size-label'><?php print $room->size;?></h4>
       <?php $room_size = $room->size;?>
    <?php endif;?>
<div class="roommate-block" id="roommate-block_<?php print $room->id;?>">

   <?php $data["room"] = $room;
     $data["sizes"] = $sizes;
    $this->load->view("room/edit", $data); ?>
</div>
<?php endforeach;?>

</div>
<a id="end-of-list"></a>

