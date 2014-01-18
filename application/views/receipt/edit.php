<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Jan 15, 2014 8:44:41 PM chrisdart@cerebratorium.com
$buttons["submit"] = array("type"=>"pass-through","text"=> sprintf("<input type='submit' name='submit' value='%s'/>",ucfirst($action)));
?>
<form name="receipt-editor" id="receipt-editor" action="<?=site_url("receipt/$action");?>" method="post">
<input type="hidden" name="id" id="id" value="<?=$receipt->id;?>"/>
<input type="hidden" name="message_id" id="message_id" value="<?=$receipt->message_id;?>"/>
<input type="hidden" name="person_id" id="person_id" value="<?=$receipt->person_id;?>"/>
<input type="hidden" name="status" id="status" value="<?=$receipt->status;?>"/>

<p>
<label>Recipient: </label>
<?=sprintf("%s %s <%s>", $receipt->first_name, $receipt->last_name, $receipt->email);?>
</p>
<p>
<label>Date Sent: </label>
<?=$receipt->status ? format_timestamp($receipt->receipt_date) : "Unsent";?>
</p>
<p>
<label>Subject: </label>
<?=$receipt->subject;?>
</p>
<p>
<textarea id="body" style="width:100%; height: 25em;" name="body"><?=$receipt->body;?></textarea>
</p>
<label for="resend">Check to resend on <?=$action;?>: </label><input type="checkbox" name="resend" id="resend" value="1"/>

<div class="alert" id="alert-box" style="display:none">
</div>
<p>
<?=create_button_bar($buttons);?>
</p>
</form>