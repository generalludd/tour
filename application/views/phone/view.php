<?php

defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 6:21:25 PM chrisdart@cerebratorium.com
?>
<table>
<tbody>
<?php foreach ($person->phones as $phone): ?>
    <tr class="phone-row field-set">
    <td class="label"><?php print $phone->phone_type;?></td>
    <td><?php print $phone->phone;?></td>
    <td><a href="<?php print base_url('phone/edit/' . $phone->id); ?>" class="button edit small edit-phone" id="<?php print sprintf("edit-phone_%s",$phone->id);?>">Edit</a></td>
   <td><span class="button delete small delete-phone" data-phone_id="<?php print $phone->id;?>" id="<?php print sprintf("delete-phone_%s", $phone->id);?>">Delete</span></td>

    </tr>
<?php endforeach; ?>
</tbody>
</table>


