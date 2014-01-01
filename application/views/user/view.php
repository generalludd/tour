<?php defined('BASEPATH') OR exit('No direct script access allowed');

// view.php Chris Dart Mar 1, 2013 2:00:56 PM chrisdart@cerebratorium.com

?>
<div class='info-box'>
	<input type="hidden" id="id" name="id" value='<?=$id;?>'>
	<h3>
		Info for
		<?="$user->first $user->last"; ?>
	</h3>
	<div class='content'>
		<fieldset id="user">
			<? $user_id = $this->session->userdata("user_id");?>
			<? if($user->id == $user_id || $user_id == 1): ?>
			<? $edit_buttons[] = array("selection"=>"auth",
					"type"=>"span",
					"class"=>array("button","password_edit","edit"),
					"text"=>"Change Password");
			?>
			<?endif;?>
			<? if($user_id == 1 && $user->id != $user_id):?>
			<? $edit_buttons[] = array("selection" => "edit",
					"class" => "masquerade button",
					"href" => site_url("/admin/masquerade/$user->id"),
					"text" => "Masquerade" );
			?>
			<? endif;?>
			<? if($this->session->userdata("db_role") == "admin"):?>
			<? $edit_buttons[] = array("selection"=>"auth",
					"class"=>"button new user-create",
					"href"=>site_url("user/create"),
					"text"=>"Add User");
			?>
			<? endif;?>
			<?=create_button_bar($edit_buttons);?>
			<?=create_edit_field("first",$user->first,"First Name");?>
			<?=create_edit_field("last",$user->last,"Last Name");?>
			<?=create_edit_field("email",$user->email,"Email Address");?>
			<? if($this->session->userdata("db_role") == "admin" && $this->session->userdata("user_id") != 1):?>
			<?=create_edit_field("is_active",$user->is_active,"Status", array("class"=>"dropdown","attributes"=>"menu='user_status'"));?>
			<?=create_edit_field("db_role",$user->db_role, "Database Role",
			array("class"=>"dropdown","attributes"=>"menu='db_role'"));?>
			<?endif; ?>
		</fieldset>
	</div>

</div>



