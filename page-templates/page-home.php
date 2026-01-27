<?php 
/* Template Name: Home Page Template */ 

l4k_checkMemberLoggedIn(); // check if member is logged in
get_header(); // display header
?>

<div class='main-mid'>

	<div class='home-row-1'>

		<div class="home-hero-wrap">
			<div class="home-hero _resizable">
				<div>
					<h1><?php echo get_field('main_hero_heading'); ?></h1>
					<div><?php echo get_field('main_hero_content'); ?></div>
				</div>

				<div>
					<img class='home-hero-img' src='<?php echo get_field('main_hero_image'); ?>' />
				</div>
			</div>
		</div>

		<div class="home-stats-wrap">

			<div class="home-stats-numbers">
				<div class='home-stats-number'>
					<h4>Books Published</h4>
					<span class='count'><?php echo get_field('main_hero_count_books_published'); ?>+</span>
				</div>
				<div class='home-stats-number'>
					<h4>Languages Available</h4>
					<span class='count'><?php echo get_field('main_hero_count_languages_available'); ?>+</span>
				</div>
				<div class='home-stats-number'>
					<h4>Community Reach</h4>
					<span class='count'><?php echo get_field('main_hero_count_community_reach'); ?>+</span>
				</div>
			</div>

			<div class='home-stats-heading'>
				Find Your Library or School
			</div>

			<div class='home-stats-search'>
				<div class='home-stats-search-wrap'>
					<input 	autocomplete='off'
							id='lib-search' 
							class='search-txt' 
							type='text' 
							placeholder='Start typing here' />
					<ul id="suggestions" translate="no" class="notranslate"></ul>
				</div>
				<a class='search-btn' href=''>GO</a>
			</div>

			<div class='home-stats-content'>
				Can’t find your library or school? Tap the bubble in the bottom right.
			</div>

		</div>

		<div class='home-lang-slider'>
			<div class="home-lang-slider__track"> 
			
				<?php $langArr = l4k_getLanguages(); ?>
				<?php if ($langArr): ?>
					<?php foreach ($langArr as $lang => $l): ?>

						<a class='home-lang-slide' href='<?php echo $l['lang_permalink']; ?>'>
							<img src='<?php echo $l['flag_url']; ?>' />
							<h5><?php echo $l['title']; ?></h5>
						</a>

					<?php endforeach; ?>
				<?php endif; ?>

			</div>
		</div>

		<div class='home-lang-btn-wrap'>
			<a href='<?php echo get_field('main_hero_cta_link'); ?>' class='_btn'><?php echo get_field('main_hero_cta_label'); ?></a>
		</div>

	</div>

	<img class='home-row-2__before home-img-spacer' src='<?php echo get_stylesheet_directory_uri(); ?>/assets/img/branch-left.png' />

	<div class='home-row-2'>

		<div class="home-hero-wrap">
			<div class="home-hero">
				<div>
					<h1><?php echo get_field('slider_section_heading'); ?></h1>
					<div><?php echo get_field('slider_section_content'); ?></div>
				</div>

				<div>
					<img class='home-hero-img' src='<?php echo get_field('slider_section_image'); ?>' />
				</div>
			</div>
		</div>

		<div class='home-story-slider'>
			<div class="home-story-slider__track"> 

				<?php if(have_rows('slider_section_story_slider')): ?>
				    <?php while(have_rows('slider_section_story_slider')): the_row(); ?>

						<a class='home-story-slide' href='#'>
							<img src='<?php the_sub_field('image'); ?>' />
						</a>

				    <?php endwhile; ?>
				<?php endif; ?>

			</div>
		</div>

	</div>

	<img class='home-row-3__before home-img-spacer' src='<?php echo get_stylesheet_directory_uri(); ?>/assets/img/branch-right.png' />

	<div class='home-row-3'>

		<div class='home-video-wrap'>
			<h3><?php echo get_field('video_section_heading'); ?></h3>
			<div><?php echo get_field('video_section_content'); ?></div>
			<div class="video">
				<iframe 
					loading="eager"
					src="<?php echo get_field('video_section_source'); ?>"
					allow="autoplay; fullscreen; picture-in-picture"
					allowfullscreen
					title="LOTE4Kids Promotional Video"
					data-nitro-exclude></iframe>
			</div>		
		</div>

		<div class='home-video-btns'>
			<?php if(have_rows('video_section_buttons')): ?>
			    <?php while(have_rows('video_section_buttons')): the_row(); ?>
			    	<a href='<?php echo get_sub_field('link'); ?>' class='_btn'><?php echo get_sub_field('text'); ?></a>
			    <?php endwhile; ?>
			<?php endif; ?>
		</div>

	</div>

	<img class='home-row-4__before home-img-spacer' src='<?php echo get_stylesheet_directory_uri(); ?>/assets/img/branch-left.png' />

	<div class='home-row-4'>

		<div class='home-reviews-wrap'>
			<h3>Reviews</h3>
		</div>

		<div class='home-reviews-slider'>
			<div class="home-reviews-slider__track">

				<?php if(have_rows('reviews_section_slider')): ?>
				    <?php while(have_rows('reviews_section_slider')): the_row(); ?>

						<div class='review-item'>
							<div class='review-item-wrap'>
								<p>
									<?php the_sub_field('review'); ?>
									<span>- <?php the_sub_field('source'); ?></span>
								</p>
								<img src='<?php the_sub_field('image'); ?>' />
							</div>						
						</div>

				    <?php endwhile; ?>
				<?php endif; ?>

			</div>
		</div>

	</div>

</div>

<ul id='lib-list' class='library-list notranslate' translate="no">
	
	<?php $libArr = l4k_getLibraries(); ?>
	<?php if ($libArr): ?>
		<?php foreach ($libArr as $lib => $l): ?>

			<li data-url='<?php echo $l['lib_permalink']; ?>'><?php echo $l['title']; ?></li>

		<?php endforeach; ?>
	<?php endif; ?>

</ul>

<?php get_footer(); ?>