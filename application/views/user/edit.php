<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Mar 1, 2013 2:59:14 PM chrisdart@cerebratorium.com

?>

<form id="user-editor" name="user-editor" action="<?php print site_url("user/$action");?>" method="POST">
<input type="hidden" name="id" id="id" value="<?php print get_value($user, "id");?>"/>
<p>
<label for="first">First Name:</label><span class="field"><input type="text" name="first_name" id="first_name" value="<?php print get_value($user, "first");?>"/></span>
</p>
<p>
<label for="last">Last Name:</label><span class="field"><input type="text" name="last_name" id="last_name" value="<?php print get_value($user, "last");?>"/></span>
</p>
<p>
<label for="username">User Name:</label><span class="field"><input type="text" name="username" id="username" value="<?php print get_value($user, "username");?>"/></span>
</p>
<p>
<label for="email">Email Address:</label><span class="field"><input type="text" name="email" id="email" value="<?php print get_value($user, "email");?>"/></span>
</p>
<p>
<label for="user_status">User Status:</label><span class="field"><?php print form_dropdown("is_active",$user_status);?></span>
</p>
<p>
<label for="role">Database Role:</label><span class="field"><?php print form_dropdown("role",$roles,"User");?></span>
</p>
<p>
<input type="submit" value="<?php print ucfirst($action);?>"/>
</p>
</form>