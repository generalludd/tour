<?php defined('BASEPATH') OR exit('No direct script access allowed');

// view.php Chris Dart Mar 1, 2013 2:00:56 PM chrisdart@cerebratorium.com

?>
<div class='info-box'>
	<input type="hidden" id="id" name="id" value='<?php print $id;?>'>
	<h3>
		Info for
		<?php print "$user->first_name $user->last_name"; ?>
	</h3>
	<div class='content'>
		<fieldset id="user">
			<?php $user_id = $this->session->userdata("user_id");?>
			<?php if($user->id == $user_id || $user_id == 1): ?>
			<?php $edit_buttons[] = array("selection"=>"auth",
					"type"=>"span",
					"class"=>array("button","password_edit","edit"),
					"text"=>"Change Password");
			?>
			<?php endif;?>
			<?php if($user_id == 1 && $user->id != $user_id):?>
			<?php $edit_buttons[] = array("selection" => "edit",
					"class" => "masquerade button",
					"href" => site_url("/admin/masquerade/$user->id"),
					"text" => "Masquerade" );
			?>
			<?php endif;?>
			<?php if($this->session->userdata("role") == "admin"):?>
			<?php $edit_buttons[] = array("selection"=>"auth",
					"class"=>"button new user-create",
					"href"=>site_url("user/create"),
					"text"=>"Add User");
			?>
			<?php endif;?>
			<?php print create_button_bar($edit_buttons);?>
			<?php print create_field("first",$user->first_name,"First Name");?>
			<?php print create_field("last",$user->last_name,"Last Name");?>
			<?php print create_field("email",$user->email,"Email Address");?>
			<?php if($this->session->userdata("role") == "admin" && $this->session->userdata("user_id") != 1):?>
			<?php print create_field("is_active",$user->is_active,"Status", array("class"=>"dropdown","attributes"=>"menu='user_status'"));?>
			<?php print create_field("role",$user->role, "Database Role",
			array("class"=>"dropdown","attributes"=>"menu='role'"));?>
			<?php endif; ?>
		</fieldset>
	</div>

</div>



