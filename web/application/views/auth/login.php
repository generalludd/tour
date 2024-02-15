<?php #login.inc
$error_text = FALSE;
if(!empty($errors)){
	if(is_array($errors)){

		foreach($errors as $msg){

			$error_text =  " -$msg<br/>\n";

		}

	}else{

		$error_text =  $errors;

	}
}

$buttons[] = array("text"=>"<input type='submit' name='submit' class='button' value='Login'/>", "type"=>"pass-through" );
$buttons[] = array("text"=>"Forgot Password?", "href"=>site_url("auth/start_reset"), "class"=>"link");


?>
<div class="login">
	<div class="login-title">Ball Park Tours Tour Management System</div>

	<?php
	if($error_text):
	?>
	<div class="message error">
		<?php print $error_text; ?>
	</div>
	<?php
	endif;
	?>
	<form action="<?php print site_url("auth/login"); ?>" method="post"
		name="login_form" id="login_form">

		<div class='login-inputs'>
			<div>
				<label for="username">Username or Email Address</label><br /> <input type="text"
					name="username" id="username" autofocus="autofocus"  value="<?php print $username; ?>"
					class="login-text" />
			</div>
			<div>
				<label for="password">Password</label> <br /> <input type="password"
					name="password" id="password" class="login-text" />
			</div>
		</div>
		<?php $this->load->view('elements/button-bar', ['data' => get_button_bar_object($buttons)]); ?>
	</form>
</div>

<datalist></datalist>
