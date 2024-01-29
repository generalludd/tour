<?php if (empty($source) || empty($duplicate)) {
	return NULL;
} ?>
<h2>Merge</h2>
<h3>This is a BETA feature. Please <a
		href="<?php print site_url('/backup'); ?>">back up the database</a> before
	continuing. </h3>
<fieldset>
	<legend><h3>
			<?php print $source->name; ?>
		</h3></legend>
	<form class="merge-person" method="post"
				action="<?php print site_url('person/do_merge/'); ?>">
		<input type="hidden" name="source_id" value="<?php print $source->id; ?>"/>
		<input type="hidden" name="duplicate_id"
					 value="<?php print $duplicate->id; ?>"/>
		<p>When you merge two people, the information from the duplicate will be
			merged into the source person. The duplicate will be deleted.</p>
		<p>Check the values you want to keep.</p>

		<p>
			<?php if ($source->status != $duplicate->status): ?>
				<label for="active">Active</label>
				<input type="radio" id="active" name="status[]"
							 value="1" checked="checked"/>
				<label for="inactive">Inactive</label>
				<input type="radio" id="inactive" name="status[]"
							 value="0"/>
			<?php else: ?>
				<input type="hidden" name="status[]"
							 value="<?php print $source->status; ?>"/>
			<?php endif; ?>
		</p>
		<p>
			<?php if (!empty($source->email)): ?>
				<label for="source_email"><?php print $source->email; ?>
				</label>
				<input type="radio" id="source_email" name="email[]"
							 value="<?php print $source->email; ?>" checked="checked"/>
			<?php else: ?>
				<input type="hidden" name="email[]" value=""/>
			<?php endif; ?>
			<?php if (!empty($duplicate->email)): ?>
				<label for="duplicate_email"><?php print $duplicate->email; ?>
				</label>
				<input type="radio" id="duplicate_email" name="email[]"
							 value="<?php print $duplicate->email; ?>"/>
			<?php else: ?>
				<input type="hidden" name="email[]" value=""/>
			<?php endif; ?>
		</p>
		<p>
			<label for="source_shirt_size">Shirt
				Size: <?php print $source->shirt_size; ?>  </label>
			<input type="radio" id="source_shirt_size" name="shirt_size[]"
						 value="<?php print $source->shirt_size; ?>" checked="checked"/>
			<label
				for="duplicate_shirt_size"><?php print $duplicate->shirt_size; ?>  </label>
			<input type="radio" id="duplicate_shirt_size" name="shirt_size[]"
						 value="<?php print $duplicate->shirt_size; ?>"/>
		</p>
		<p>
			<?php if ($source->is_veteran != $duplicate->is_veteran): ?>
				<input type="checkbox" id="is_veteran" name="is_veteran"
							 value="1" checked="checked"/>
				<label for="is_veteran">Is
					Veteran:
				</label>
			<?php endif; ?>
		</p>
		<?php if (!empty($source->phones) || !empty($duplicate->phones)): ?>
			<p>
			<ul>
				<caption>Phones</caption>

				<?php foreach ($source->phones as $phone): ?>
					<li><input type="checkbox"
										 id="source_phone_<?php print $phone->id; ?>"
										 name="phone[]" value="<?php print $phone->id; ?>"
										 checked="checked"/>
						<label
							for="source_phone_<?php print $phone->id; ?>"><?php print $phone->phone; ?>
						</label>
					</li>
				<?php endforeach; ?>
			</ul>
			</p>
		<?php endif; ?>
		<div>
			<div class="diptych">
				<div>
					<?php if (!empty($source->address)): ?>
						<input type="radio" id="source_address" name="address[]"
									 value="<?php print $source->address_id; ?>"
									 checked="checked"/>
						<label
							for="source_address"><?php print format_address($source->address); ?></label>
					<?php else: ?>
						<input type="hidden" name="address[]" value=""/>
					<?php endif; ?>
				</div>
				<div>
					<?php if (!empty($duplicate->address)): ?>
						<input type="radio" id="source_address" name="address[]"
									 value="<?php print $duplicate->address_id; ?>"/>
						<label
							for="source_address"><?php print format_address($duplicate->address); ?></label>
					<?php else: ?>
						<input type="hidden" name="address[]" value=""/>
					<?php endif; ?>
				</div>
			</div>
			<?php $this->load->view('tour/mini-list', ['tours' => $source->tours]); ?>

			<?php $this->load->view('tour/mini-list', ['tours' => $duplicate->tours]); ?>
		</div>
		<p>Are you sure you want to merge these two records?</p>
		<?php print create_button_bar([
			[
				'type' => 'pass-through',
				'text' => '<input type="submit" name="submit" value="Merge" class="button delete">',
			],
			[
				'text' => 'Cancel',
				'href' => site_url('person/view/' . $source->id),
				'class' => ['button', 'cancel'],
				'title' => 'cancel this merge',
			],
		]); ?>
	</form>
</fieldset>
