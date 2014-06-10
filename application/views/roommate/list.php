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
<h3><?=sprintf("Roommates for Tour: <a href='%s'>%s</a>, Stay: %s",site_url("tour/view/$tour_id"), $hotel->tour_name, $stay);?></h3>
<div
	class="block hotel-info info-block"
	id="hotel-info"
	style="clear: both">
	<label>Hotel:&nbsp;</label><a
		href="<?=site_url("hotel/view/$hotel->id");?>"><?=$hotel->hotel_name?></a><br/>
	<label>Arrival Date:&nbsp;</label><?=format_date(get_value($hotel,"arrival_date"));?>,&nbsp;<?=get_value($hotel,"arrival_time");?><br/>
<label>Departure Date:&nbsp;</label><?=format_date(get_value($hotel,"departure_date"));?>,&nbsp;<?=get_value($hotel,"departure_time");?><br />
<? if(get_value($hotel, "contact",FALSE)): ?>
    <label>Contact Info: </label><?=get_value($hotel, "contact");?>,&nbsp;
<? endif;?>
<? if(get_value($hotel, "phone",FALSE)): ?>
<label>Phone: </label><?=get_value($hotel, "phone");?>&nbsp;
<? endif;?>
<? if(get_value($hotel, "fax",FALSE)): ?>
<label>Fax: </label><?=get_value($hotel,"fax");?>&nbsp;
<? endif;?>

<p><strong>Room Type Count:</strong>
<? $room_output[] = "";?>
<? foreach($room_count as $count):?>
<? $room_output[] = sprintf("%ss: %s", format_field_name($count->size), $count->room_count);?>
<? endforeach;?>
<?=implode(", ", $room_output);?>
</p>
<?=create_button_bar($buttons, array("class"=>"float"));?>
</div>
<input
	type="hidden"
	id="stay"
	name="stay"
	value="<?=$stay;?>" />
<input
	type="hidden"
	id="tour_id"
	name="tour_id"
	value="<?=$tour_id;?>" />
<div
	class="block"
	id="roommate-list-block">
	<?
$room_size = "";?>

<? foreach ($rooms as $room): ?>
   <? if($room_size != $room->size):?>
       <h4 class='room-size-label'><?=$room->size;?></h4>
       <? $room_size = $room->size;?>
    <? endif;?>
<div class="roommate-block">

   <? $data["room"] = $room;
     $data["sizes"] = $sizes;
    $this->load->view("room/edit", $data); ?>
</div>
<? endforeach;?>

</div>


