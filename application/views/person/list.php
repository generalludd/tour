<?php defined('BASEPATH') OR exit('No direct script access allowed');

// list.php Chris Dart Dec 16, 2013 10:32:15 PM chrisdart@cerebratorium.com
?>
<table class="list" id="person-list">
<? foreach($people as $person): ?>
<tr>
<td>
<?=$person->first_name;?>
</td>
<td>
<?=$person->last_name;?>
</td>
<td>
<a href="<?=site_url("person/view/$person->id");?>" class="button mini">View</a>
</td>
</tr>
<? endforeach; ?>
</table>