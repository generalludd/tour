<?php defined('BASEPATH') OR exit('No direct script access allowed');

// select.php Chris Dart Mar 15, 2014 2:56:20 PM chrisdart@cerebratorium.com

//select available letters for a given tour

if(count($letters)>0):
?>
<h4>Select the letter you want to use from the list below</h4>
<?foreach ($letters as $letter):?>
<p>
<a href="<?=site_url("merge/create/?letter_id=$letter->id&payer_id=$payer_id&tour_id=$tour_id");?>"><?=$letter->title;?></a>
</p>
<? endforeach?>
<? else: ?>
<h4>There are no letters prepared for this tour.</h4>
<p> You must create one before you can send one to a tour participant</p>
<p>
<a href="<?=site_url("letter/create/$tour_id");?>" class="button new">Create One Now</a>
</p>
<? endif; ?>