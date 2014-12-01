<?php defined('BASEPATH') OR exit('No direct script access allowed');

// select.php Chris Dart Dec 20, 2013 7:04:26 PM chrisdart@cerebratorium.com

?>
<input type="hidden" name="person_id" id="person_id" value="<?=$id;?>"/>
<div id="tourist-selector">

<?if(count($tours)>=1):?>
<div style="width:60ex">
Choose "payer" if the person is paying for the tour.<br/>Choose "tourist" if someone else is paying<br/>The payer must already have been added as a payer to the selected tour.
</div>
<table class="list">

<? foreach($tours as $tour):?>
<tr>
<td>
<?=$tour->tour_name;?>
</td>
<td>
<?=create_button(array("text"=>"Select as Tourist","id"=>sprintf("select-as-tourist_%s",$tour->id), "class"=>"button select-as-tourist","type"=>"span"));?>
</td>
<td>
<?=create_button(array("text"=>"Select as Payer","id"=>sprintf("select-as-payer_%s",$tour->id), "class"=>"button select-as-payer","type"=>"span"));?>
</td>
</tr>
<? endforeach;?>
</table>
<? else: ?>
<p>
This person has already been signed up for all the available tours</p>
<? endif;?>
</div>