<?php 
// this path /libraries/au-demo is PUBLIC 
// this path /libraries/au-demo/trial is PUBLIC 
// this path /libraries/au-demo/competition is PUBLIC 
// this path /libraries/au-demo/dashboard is PRIVATE
if ((get_query_var('trial', false) === false) && (get_query_var('competition', false) === false)) { 
	if (isset($_GET['trial-login'])) { l4k_trialFormAutoLogin(); } // if coming from trial or competition, auto generate barcode and login
	l4k_checkMemberLoggedIn(); // check if member is logged in
} 

get_header(); // display header 
?>

<div class='main-mid'>
	<div class='_maxwrap medium'>

		<?php if (get_query_var('dashboard', false) !== false) : ?>

			<div class='dash-wrap'>
				<div class='mainbar'>
					<div class='report__wrap'>
						<iframe src='<?php echo get_field('dashboard_iframe_source'); ?>'></iframe>
					</div>
				</div>
				<?php get_template_part('template-parts/section', 'sidebar-dashboard'); ?>
			</div>

		<?php elseif (get_query_var('trial', false) !== false) : ?>

			<div class='trial-wrap'>
				
				<div class='intro-section'>
					<div class='main-text'><?php echo nl2br(get_field('trial_main_text')); ?></div>
					<div class='main-image'><img src='<?php echo get_field('trial_main_image'); ?>' /></div>
				</div>

				<div class='form-video-section'>
					<div class='form-wrap'>
						<div class='form-label'><?php echo get_field('trial_form_label'); ?></div>
						<div class='form'><?php echo do_shortcode('[wpforms id="'.get_field('trial_form').'" title="false"]'); ?></div>
					</div>
					<div class='video-wrap'>
						<div class='video-label'><?php echo get_field('trial_video_label'); ?></div>
						<div class='video'>
							<?php $videoURL = l4k_parseVimeoUrl(get_field('trial_video')); ?>
							<iframe 
								src="https://player.vimeo.com/video/<?php echo $videoURL[0]; ?>?title=0&amp;byline=0&amp;portrait=0&amp;h=<?php echo $videoURL[1]; ?>&amp;app_id=122963" 
								frameborder="0" 
								allow="autoplay; fullscreen; picture-in-picture" 
								allowfullscreen>
							</iframe>
						</div>
						<div class='video-buttons'>
							<div class='video-buttons-label'><?php echo get_field('trial_buttons_text'); ?></div>
							<?php if (have_rows('trial_buttons')) : ?>	
								<?php while (have_rows('trial_buttons')) : the_row(); ?>
									<a href="<?php echo get_sub_field('url'); ?>" class='_btn' target='_blank'>
										<?php echo get_sub_field('text'); ?>
									</a>
								<?php endwhile; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>

			</div>

		<?php elseif (get_query_var('competition', false) !== false) : ?>

			<div class='competition-wrap'>

				<div class='left-image'>
					<img src='<?php echo get_field('competition_left_image'); ?>' />
				</div>

				<div class='main-content'>
					<div class='text'><?php echo nl2br(get_field('competition_main_text')); ?></div>
					<div class='form'><?php echo do_shortcode('[wpforms id="'.get_field('competition_form').'" title="false"]'); ?></div>
				</div>

				<div class='right-image'>
					<img src='<?php echo get_field('competition_right_image'); ?>' />
				</div>

			</div>

		<?php else : ?>

			<div class='lib-wrap'>
				<div class='lib-wrap__inner'>

					<div class='lib__logo'><img src='<?php echo get_field('logo_dashboard'); ?>' /></div>

					<div class='lib__header'>Storytime, in your own language!</div>

					<?php if ($_SESSION['library_barcode']) : ?>

						<div class='lib__loggedin-message'>
							You are currently logged in as <span><?php echo $_SESSION['library_barcode']; ?></span>
						</div>

						<div class='lib__submit'>
							<a href='<?php echo home_url('/member-home'); ?>' class='_btn' >Go to Member Home</a>
							<a href='<?php echo home_url('/?member-logout'); ?>' class='_btn' >Logout</a>
						</div>

					<?php else : ?>

						<div class='lib__barcode'>
							<input 	id="barcode" 
									type='text' 
									name='barcode'
									autocomplete='off' 
									placeholder="Enter your Library Barcode (including all characters)" />
							<input 	id="library_id" 
									type='hidden' 
									name='library_id' 
									value='<?php echo get_the_id(); ?>' />
							<div class='lib__error'>Barcode does not match</div>
						</div>

						<div class='lib__remember'>
							<input 	id="remember_me" 
									type="checkbox"
									checked="checked"> 
									Remember me
						</div>

						<div class='lib__submit'>
							<button id="library_submit_btn" class='_btn'>
								<i class="lni lni-locked-2"></i> Secure Login
							</button>
						</div>
						
						<div class='lib__text'>
							By logging in, you agree to our <div><a href='/terms-of-use/' target='_blank'>Terms of Use</a></div>
						</div>

					<?php endif; ?>

				</div>
			</div>

		<?php endif; ?>

	</div>
</div>

<div class='ajax-response__wrapper'></div>

<?php /* <pre><?php print_r(get_post_meta(get_the_ID())); ?></pre> */ ?>

<?php get_footer(); ?>