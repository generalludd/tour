<?php #login_changepass.inc ?><div id="password_form"><form id="password_editor" name="password_editor" action="<?php print site_url("user/change_password")?>" method="post" ><input type="hidden" name="valid_password" id="valid_password" value=false/><input type="hidden" name="id" id="id" value="<?php print $id;?>"/><p><label for="current_password">Current Password: </label><br/><input type="password" id="current_password" name="current_password" value=""/></p><p><label for="new_password">New Password: </label><br/><input type="password" id="new_password" name="new_password" value=""/></p><p><label for="check_password">Re-enter New Password: </label><br/><input type="password" id="check_password" name="check_password" value=""/></p><p><span id='password_note' class='notice' style="display:none"></span></p><p><input type="submit" class='button change_password' id='change-password' value='Change Password'/></p></form></div>