<?php

defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 6:21:25 PM chrisdart@cerebratorium.com
?>

<?php foreach ($person->phones as $phone): ?>
    <div class="field-set">
    <label><?php print $phone->phone_type;?></label>
    <div><?php print $phone->phone;?></div>
    <div><a href="<?php print base_url('phone/edit/' . $phone->id); ?>" class="button edit dialog small">Edit</a></div>
   <div><a class="button delete small delete-phone" data-phone_id="<?php print $phone->id;?>">Delete</a></div>

    </div>
<?php endforeach; ?>


