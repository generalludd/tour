<?php defined('BASEPATH') OR exit('No direct script access allowed');

// filter.php Chris Dart Jan 17, 2014 9:47:50 PM chrisdart@cerebratorium.com

?>
<form name="person-filter" id="person-filter" action="<?=site_url("person/view_all");?>" method="get">
<p>
<label for="initial">Filter on Last Name Initial</label>
<?=form_dropdown("initial",$initials,$this->input->get("initial"));?>
</p>
<p>
<label for="veterans_only">Show Veterans Only: </label><input type="checkbox" name="veterans_only" id="veterans_only" value="1"/>
</p>
<p>
<label for="email_only">Show only people with email addresses</label>
<input type="checkbox" name="email_only" id="email_only" value="1"/>
</p>
<p>
<label for="show_disabled">Include people who've been Disabled from the Database:</label>
<input type="checkbox" name="show_disabled" id="show_disabled" value="1"/>
</p>
<p>
<input type="submit" class="button" value="Filter People"/>
</p>
</form>