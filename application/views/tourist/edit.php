<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 28, 2013 7:49:30 PM chrisdart@cerebratorium.com
?>
<p><strong>This will add a new person to the address book as well as to this tour!</strong></p>
<input type="hidden" name="person_id" id="person_id" value="<?php print get_value($tourist, "person_id");?>"/>
<label for="first_name">First Name</label>
<input type="text" name="first_name" id="first_name" value="<?php print get_value($tourist, "first_name");?>"/>
<br/>
<label for="last_name">Last Name</label>
<input type="text" name="last_name" id="last_name" value="<?php print get_value($tourist, "last_name");?>"/>
<br/>
<label for="shirt_size">Shirt Size</label>
<?php print form_dropdown("shirt_size",$shirt_sizes,get_value($tourist, "shirt_size"),'id="shirt_size"');?>
<br/>
<?php $buttons[] = array("text"=>ucfirst($action),"type"=>"span","class"=>"button new insert-new-tourist");?>
<?php print create_button_bar($buttons);

