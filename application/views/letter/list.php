<?php defined('BASEPATH') OR exit('No direct script access allowed');

// list.php Chris Dart Mar 15, 2014 12:27:43 PM chrisdart@cerebratorium.com
$buttons["add_letter"] = array("text"=>"Add Letter", "class"=>"button add-letter new", "href" => site_url("letter/create/$tour_id"));
?>
<h4>List of Letter Templates</h4>

<div id="letter-list-box">
<table id="letter-list">
<thead>
<tr>
<th>
Date
</th>
<th>
Title
</th>
<th>
</th>
</tr>
</thead>
<tbody>
<? foreach($letters as $letter): ?>
<tr id="<? printf("letter-row_%s",$letter->id);?>">
<td>
<?=format_date($letter->creation_date,"standard");?>
</td>
<td>
<?=$letter->title;?>
</td>
<td>
<a href="<?=site_url("letter/edit/$letter->id");?>" class="edit-letter edit button small">Edit</a>
</td>
</tr>
<? endforeach; ?>
</tbody>
</table>
<div style="padding-top: 1em;">
<?=create_button_bar($buttons);?>
</div>
<div style="padding-top: 1em;">
<? //=create_button_bar($done);?>
</div>
</div>