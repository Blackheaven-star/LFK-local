<?php get_header(); ?>

	<?php global $post; ?>

	<div class='main-mid'>
		<div class='_maxwrap'>

			<?php if (get_field('type') == 'collection') : ?>

				<div class='single-activity-collection-wrap'>

					<div class='mainbar'>
						<h1>
							<img src='<?php echo get_field('activity_image'); ?>' />
							<?php echo get_the_title(); ?>
						</h1>

						<div class='activities'>
							<?php if (have_rows('activities')) : ?>
							    <?php while (have_rows('activities')) : the_row(); ?>

							    	<div class='activity-item'>
							    		<div class='title'>
											<h4><?php echo get_sub_field('title'); ?></h4>
										</div>

										<div class='activity'>
											<img src='<?php echo get_sub_field('image'); ?>' />
										</div>

										<div class='featured-buttons'>
											<?php if (get_sub_field('download_link')) : ?>
												<a href='<?php echo get_sub_field('download_link'); ?>' class='_btn' target='_blank'>Download</a>
											<?php endif; ?>
											<?php if (get_sub_field('watch_link')) : ?>
												<a href='<?php echo get_permalink(get_sub_field('watch_link')); ?>' class='_btn' target='_blank'>Watch</a>
											<?php endif; ?>
										</div>
									</div>

							    <?php endwhile; ?>
							<?php endif; ?>
						</div>
					</div>

					<?php get_template_part('template-parts/section', 'sidebar-main'); ?>
				</div>

			<?php else : ?>

				<div class='single-activity-wrap'>
					<div class='mainbar'>
						<iframe src='<?php echo get_field('iframe_source'); ?>'></iframe>
					</div>
					<?php get_template_part('template-parts/section', 'sidebar-main'); ?>
				</div>

			<?php endif; ?>

		</div>
	</div>

	<?php /* <pre><?php print_r(get_post_meta(get_the_ID())); ?></pre> */ ?>

<?php get_footer(); ?>