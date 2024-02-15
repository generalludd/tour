<?php $flash = $this->session->flashdata(); ?>
<?php if (!empty($flash)): ?>
	<?php foreach ($flash as $key => $message): ?>
		<div class="message <?php print $key; ?>">
			<div class="content"><?php print $message; ?></div>
			<button class="close">✖</button>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
<?php if (BACKUP_STATUS > BACKUP_THRESHOLD): ?>
	<div class="message warning">
		<div class="content">
			It has been over <?php echo round(BACKUP_STATUS / 60 / 60 / 24, 0); ?>
			days
			since the last backup. You should <a
				href="<?php echo site_url('backup'); ?>"
				class="do-backup">click here to back up now</a>.
		</div>
		<button class="close">✖</button>
	</div>
<?php endif; ?>
