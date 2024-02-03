<?php if ($this->session->flashdata('notice')): ?>
	<div class="message notice">
		<div class="content"><?php print $this->session->flashdata('notice');
			?></div>
		<button class="close">✖</button>
	</div>

<?php endif; ?>
<?php if ($this->session->flashdata('alert')): ?>
	<div class="message alert">
		<div class="content"><?php print $this->session->flashdata('alert');
			?></div>
		<button class="close">✖</button>

	</div>
<?php endif; ?>
<?php if (BACKUP_STATUS > BACKUP_THRESHOLD): ?>
	<div class="message warning">
		<div class="content">
			It has been over <?php echo round(BACKUP_STATUS / 60 / 60 / 24, 0); ?>
			days
			since the last backup. You should <a href="<?php echo site_url('backup'); ?>"
																					 class="do-backup">click here to back up now</a>.
		</div>
		<button class="close">✖</button>
	</div>
<?php endif; ?>
