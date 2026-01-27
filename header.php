<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/favicon.png">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> style='background-color: <?php echo l4k_getBGColor(); ?>' data-custom-bg='<?php echo l4k_getBGColor(); ?>'>

<?php if (have_rows('sticky_bar_announcement', 'option')) : ?>
    <div class='announcement-bar'>
        <div class='close'><i class="lni lni-xmark"></i></div>
        <div class='elements'>
	        <?php while (have_rows('sticky_bar_announcement', 'option')) : the_row(); ?>
	        	<span>
	        		<?php if (get_sub_field('type') == 'text') : ?>
	        			<?php if (get_sub_field('link')) : ?>
	        				<a href='<?php echo get_sub_field('link'); ?>'><?php echo get_sub_field('value'); ?></a>
        				<?php else : ?>
        					<?php echo get_sub_field('value'); ?>
        				<?php endif; ?>	
	        		<?php else : ?>
	        			<?php if (get_sub_field('link')) : ?>
	        				<a href='<?php echo get_sub_field('link'); ?>'><img src='<?php echo get_sub_field('value'); ?>' /></a>
        				<?php else : ?>
    						<img src='<?php echo get_sub_field('value'); ?>' />
	        			<?php endif; ?>
	        		<?php endif; ?>
				</span>
	        <?php endwhile; ?>
	    </div>

		<?php if (get_field('sticky_bar_second_row', 'options') == 'show') : ?>
			<?php if (have_rows('sticky_bar_announcement_row_2', 'option')) : ?>
			    <div class='elements row-2'>
			        <?php while (have_rows('sticky_bar_announcement_row_2', 'option')) : the_row(); ?>
			        	<span>
			        		<?php if (get_sub_field('type') == 'text') : ?>
			        			<?php if (get_sub_field('link')) : ?>
			        				<a href='<?php echo get_sub_field('link'); ?>'><?php echo get_sub_field('value'); ?></a>
		        				<?php else : ?>
		        					<?php echo get_sub_field('value'); ?>
		        				<?php endif; ?>	
			        		<?php else : ?>
			        			<?php if (get_sub_field('link')) : ?>
			        				<a href='<?php echo get_sub_field('link'); ?>'><img src='<?php echo get_sub_field('value'); ?>' /></a>
		        				<?php else : ?>
		    						<img src='<?php echo get_sub_field('value'); ?>' />
			        			<?php endif; ?>
			        		<?php endif; ?>
						</span>
			        <?php endwhile; ?>
			    </div>
		   	<?php endif; ?>
	   	<?php endif; ?>
    </div>
<?php endif; ?>

<pre style='display: none;'><?php print_r($_SESSION); ?></pre>

<header class="site-header">
    <div class='_maxwrap'>

    	<div class='logo-menu-wrap'>	
	        <h1 class="site-logo">
	            <a href="<?php echo esc_url(home_url('/')); ?>">
	                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo-main.svg" alt="">
	            </a>
	        </h1>

	        <nav class="main-nav">
	            <?php
	            wp_nav_menu([
	                'menu'       => 'Main Menu',
	                'container'  => false,
	                'menu_class' => 'menu'
	            ]);
	            ?>
	        </nav>
    	</div>

        <div class='mid-header'>
	        <?php $links = l4k_breadcrumbs(); ?>
	        <?php if ($links): ?>
	            <ul class='breadcrumb'>
	                <?php foreach ($links as $link => $l): ?>
	                    <li>
	                        <a title='Logout' href='<?php echo $l['permalink']; ?>'><?php echo $l['label']; ?></a> 
	                        <?php if ($l !== end($links)): ?> <span>></span><?php endif; ?>
	                    </li>
	                <?php endforeach; ?>        
	            </ul>
	        <?php endif; ?>
        </div>

    </div>
</header>

<main class="site-content">