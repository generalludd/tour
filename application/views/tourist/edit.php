<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 28, 2013 7:49:30 PM chrisdart@cerebratorium.com
?>
<input type="hidden" name="person_id" id="person_id" value="<?=get_value($tourist, "person_id");?>"/>
<table class="list">
<thead>
<tr>
<th>
First Name
</th>
<th>
Last Name
</th>
<th>
Shirt Size
</th>
<th>
</th>
</tr>
</thead>
<tbody>
<tr>
<td>
<input type="text" name="first_name" id="first_name" value="<?=get_value($tourist, "first_name");?>"/>
</td>
<td>
<input type="text" name="last_name" id="last_name" value="<?=get_value($tourist, "last_name");?>"/>
</td>
<td>
<input type="text" name="shirt_size" id="shirt_size" value="<?=get_value($tourist, "shirt_size");?>"/>
</td>
<td>
<span class="button new insert-new-tourist"><?=ucfirst($action);?></span>
</td>
</tr>
</tbody>
</table>
