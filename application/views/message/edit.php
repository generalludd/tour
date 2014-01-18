<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Jan 13, 2014 8:45:32 PM chrisdart@cerebratorium.com
$merge_fields = array("AMOUNT_PAID", "AMOUNT_DUE", "NAMES");

?>


<?=$this->load->view("tour/summary");?>
<div class="merge-fields block">
<p>To include values from the current tour for each user in the message use the following merge fields exactly as shown:</p>
<ul class="merge-field-list">
<? foreach($merge_fields as $field): ?>
<li><?=$field;?></li>
<? endforeach;?>
</ul>
</div>

<form name="message-editor" id="message-editor" method="post"  action="<?=site_url("message/send");?>">
<input type="hidden" name="tour_id" id="tour_id" value="<?=$tour_id;?>"/>
<p>
<label for="subject">Subject:</label><br/>
<input type="text" name="subject" id="subject" value="<?=get_value($message, "subject"); ?>"/>
</p>
<p>
<textarea name="body" class="editor" id="body"><?=get_value($message,"body" );?></textarea>
</p>
<p>
<input type="submit" name="submit" class="button" value="<?=ucfirst($action);?>"/>
</p>
</form>