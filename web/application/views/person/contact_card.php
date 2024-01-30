<?php if (!empty($person->phones) || !empty($person->email)) : ?>
	<?php if (!empty($person->email)) : ?>
		<?php print format_email($person->email); ?><br />
	<?php endif; ?>
	<?php foreach ($person->phones as $phone) : ?>
	<div>
		<?php print sprintf("%s: %s", $phone->phone_type, $phone->phone); ?>
	</div>
	<?php endforeach; ?>
<?php endif; ?>
