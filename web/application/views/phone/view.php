<?php

if(empty($person)){
	return NULL;
}
// view.php Chris Dart Dec 13, 2013 6:21:25 PM chrisdart@cerebratorium.com
?>

<?php foreach ($person->phones as $phone): ?>
    <div class="field-set">
    <label><?php print $phone->phone_type;?></label>
    <div><?php print $phone->phone;?></div>
    <div><a href="<?php print base_url('phone/edit/' . $phone->id); ?>" class="button edit dialog small">Edit</a></div>
   <div><a class="button delete small dialog" href="<?php print site_url("phone/delete?phone_id=$phone->id");?>">Delete</a></div>

    </div>
<?php endforeach; ?>


