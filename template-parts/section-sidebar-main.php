<div class='sidebar main'>

	<!-- sidebar library welcome logo -->
	<?php if ($_SESSION['library_welcome_logo']) : ?>
		<div class='side-image'>
			<img src='<?php echo $_SESSION['library_welcome_logo']; ?>' />
		</div>
	<?php endif; ?>

	<!-- sidebar image -->
	<?php if (get_field('sidebar_settings_image', 'option')) : ?>
		<div class='side-image'>
			<img src='<?php echo get_field('sidebar_settings_image', 'option'); ?>' />
		</div>
	<?php endif; ?>

	<!-- sidebar paragraph 1 -->
	<?php if (get_field('sidebar_settings_content_1', 'option')) : ?>
		<div class='side-content'>
			<?php echo apply_filters('the_content', get_field('sidebar_settings_content_1', 'option')); ?>
		</div>
	<?php endif; ?>

	<!-- sidebar button 1 -->
	<?php if (get_field('sidebar_settings_content_1_button_text', 'option')) : ?>
		<a class='_btn' href='<?php echo get_field('sidebar_settings_content_1_button_link', 'option') ?>'>
			<?php echo get_field('sidebar_settings_content_1_button_text', 'option') ?>
		</a>
	<?php endif; ?>

	<!-- sidebar paragraph 2 -->
	<?php if (get_field('sidebar_settings_content_2', 'option')) : ?>
		<div class='side-divider'></div>
		<div class='side-content'>
			<?php echo apply_filters('the_content', get_field('sidebar_settings_content_2', 'option')); ?>
		</div>
	<?php endif; ?>

	<!-- sidebar languages -->
	<?php if (get_field('sidebar_settings_content_2_languages', 'option')) : ?>
		<div class='side-content'>
			<?php $sideLanguages = get_field('sidebar_settings_content_2_languages', 'option'); ?>
			<?php if ($sideLanguages) : ?>
				<div class='side-langs'>
					<?php foreach ($sideLanguages as $langID) : ?>
						<?php $l = l4k_getLanguageDetails($langID);  ?>
						<a 	class='lang-item' 
							href='<?php echo $l['lang_permalink']; ?>'
							data-published='<?php echo $l['date_published']; ?>'
							data-english-name='<?php echo $l['title']; ?>'>

							<img src='<?php echo $l['flag_url']; ?>' />
							<!-- <h4><?php echo $l['native_label']; ?></h4> -->
							<h5><?php echo $l['title']; ?></h5>

						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<!-- sidebar button 2 -->
	<?php if (get_field('sidebar_settings_content_2_button_text', 'option')) : ?>
		<div class='side-divider'></div>
		<a class='_btn' href='<?php echo get_field('sidebar_settings_content_2_button_link', 'option') ?>'>
			<?php echo get_field('sidebar_settings_content_2_button_text', 'option') ?>
		</a>
		<div class='side-divider'></div>
	<?php endif; ?>

	<div class='side-content'>
		<h4>Feedback</h4>
		<?php echo do_shortcode('[wpforms id="'.get_field('sidebar_settings_form', 'option').'" title="false"]'); ?>
	</div>

</div>