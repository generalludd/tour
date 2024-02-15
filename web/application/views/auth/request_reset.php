<?php #login.inc ?>
<?php
if (!empty($errors)) {
	if (!is_array($errors)) {
		$errors = [$errors];
	}
}
?>
<div class="login">
	<div class="login-title">Password Reset</div>
	<form action="<?php print site_url("auth/send_reset"); ?>" method="post"
				name="reset_request" id="reset_request">
		<?php if (!empty($errors)): ?>
			<div class="message error">
				<ul>
					<?php foreach ($errors as $msg): ?>
						<li><?php print $msg; ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>


		<div class='login-inputs'>
			<div><label for="email">Enter Your Email Address to Reset Your
					Password</label><br/>
				<input type="text" name="email" id="email"
							 class="login-text"/></div>
			<div><input type="submit" name="submit" class="button" value="Send"/></div>
		</div>
	</form>
</div>

