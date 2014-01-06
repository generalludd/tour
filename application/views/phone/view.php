<?php

defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 6:21:25 PM chrisdart@cerebratorium.com
?>
<table>
<tbody>
<? foreach ($person->phones as $phone): ?>
    <tr class="phone-row field-set">
    <td class="label"><?=$phone->phone_type;?></td>
    <td><?=$phone->phone;?></td>
    <td><span class="button edit small edit-phone" id="<?=sprintf("edit-phone_%s",$phone->id);?>">Edit</span></td>
   <td><span class="button delete small delete-phone" id="<?=sprintf("delete-phone_%s", $phone->id);?>">Delete</span></td>

    </tr>
<? endforeach; ?>
</tbody>
</table>


