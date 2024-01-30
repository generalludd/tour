<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Mar 14, 2014 9:40:39 PM chrisdart@cerebratorium.com

?>
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>

<h4>Letter Template</h4>
<p>
For <a href="<?php print site_url("tours/view/$tour->id");?>"><?php print $tour->tour_name;?></a><br/>
<?php print format_date($tour->start_date, "standard");?> to <?php print format_date($tour->end_date);?><br/>
Payment Deadline: <?php print format_date($tour->due_date);?>
</p>

<form id="letter-editor" name="letter-editor" method="post" action="<?php print site_url("letter/$action");?>">
<input type="hidden" name="id" value="<?php print get_value($letter, "id");?>"/>
<input type="hidden" name="tour_id" value="<?php print $tour_id;?>"/>
<p>
<label for="title">Title (i.e. "Welcome Aboard")</label>
<input type="text" name="title" id="title" value="<?php print get_value($letter, "title");?>"/>
</p>
<p>
<label for="creation_date">Date Created</label>
<input type="date" name="creation_date" class="datefield" value="<?php print get_value($letter,"creation_date",date("Y-m-d"));?>"/>
</p>
<p>
<label for="body">Main Letter Text</label><br/>
<textarea id="body" name="body" style="width:100%">
<?php print get_value($letter, "body");?>
</textarea>
</p>
<p>
<label for="cancellation">Cancellation Paragraph</label>
<textarea id="cancellation"  style="width: 100%" name="cancellation">
<?php print get_value($letter, "cancellation");?>
</textarea>
</p>
<input type="submit" name="submit" class="button new" value="<?php print ucfirst($action);?>"/>
</form>
<script>
	ClassicEditor
			.create( document.querySelector( '#body' ) )
			.catch( error => {
				console.error( error );
			} );
	ClassicEditor
			.create( document.querySelector( '#cancellation' ) )
			.catch( error => {
				console.error( error );
			} );
</script>
