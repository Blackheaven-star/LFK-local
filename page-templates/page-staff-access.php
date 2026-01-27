<?php 
/* Template Name: Staff Access Page Template */

l4k_checkStaffLoggedIn(); 	// check if staff or admin is logged in. if yes, redirect to library's dashboard
get_header(); 				// display header
?>

<div class='main-mid'>
	<div class='_maxwrap'>

		<div class='blowing-leaves'>
		    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf1.png" class="blowing-leaf" alt="leaf">
		    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf2.png" class="blowing-leaf" alt="leaf">
		    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf3.png" class="blowing-leaf" alt="leaf">
		</div>

		<div class='staff-wrap'>

			<div class='heading'>
				<img src='<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo-lote-only.png' />
				<div class='heading__text'>
					<h2>Promotions & reporting portal</h2>
					<p>Library staff can login here to view usage reports and access promotional materials</p>
				</div>
			</div>

			<?php echo do_shortcode('[l4k_staffLogin]'); ?>
			
		</div>

	</div>
</div>

<?php get_footer(); ?>