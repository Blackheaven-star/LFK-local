<?php 
l4k_checkMemberLoggedIn(); // check if member is logged in

if (get_query_var('reading-pack', false) !== false) { $bookArr = l4k_getBooks(get_the_ID(), true, true); }
else { $bookArr = l4k_getBooks(get_the_ID()); }

$playlistArr = l4k_getPlaylistsByLanguage(get_the_ID());
$isReadingPack = l4k_isReadingPackContext();

get_header();
global $post;
?>

<div class='main-mid language'>
	<div class='_maxwrap'>

		<div class='lang-wrap'>

			<div class='mainbar'>

				<div class='heading'>

					<?php if (l4k_isLanguagePartOfReadingPacks(get_the_ID())) : ?>
						<div class='heading-folder'>
							<a href="<?php echo get_permalink(); ?>reading-pack" class='<?php echo ($isReadingPack) ? 'active' : ''; ?>'>Reading Pack</a>
							<a href="<?php echo get_permalink(); ?>" class='<?php echo (!$isReadingPack) ? 'active' : ''; ?>'>All Books</a>
						</div>
					<?php endif; ?>

					<div class='fun-facts'>
						<div class='fun-facts__inner'>
							<?php if (get_field('fun_facts_enabled')) : ?>
								<?php if (get_field('fun_facts_position') == 'top-left') : ?>
									<a 	href='javascript: void(0);' 
										id='fun-facts-btn'
										data-html-src='<?php echo get_field('fun_facts_media_html'); ?>'>
										<div class='dialog'>
											<?php echo get_field('fun_facts_cta_text'); ?>
											<?php if (get_field('fun_facts_cta_text_sub')) : ?>
												<span><?php echo get_field('fun_facts_cta_text_sub'); ?></span>
											<?php endif; ?>
										</div>
										<img src='<?php echo get_field('fun_facts_media_lekti'); ?>' />
									</a>
								<?php endif; ?>
							<?php else : ?>
								<a 	href='javascript: void(0);' class='disabled' aria-disabled="true">
									<div class='dialog'>Language Fun Facts<span>COMING SOON!</span></div>
									<img src='<?php echo get_stylesheet_directory_uri(); ?>/assets/img/fun-facts-coming-soon.png' />
								</a>
							<?php endif; ?>
						</div>

						<div class='embed__overlay'>
							<div class='embed__wrap'>
								<div class='embed__wrap__inner'>
									<div class='embed__decoration'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf1.png" alt="leaf"></div>
									<div class='embed__decoration-2'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf3.png" alt="leaf"></div>
									<a class='embed__close'><i class="lni lni-xmark"></i></a>
									<div class='embed__title'><?php echo get_field('fun_facts_cta_text'); ?></div>
									<div class='embed__content'>
										<iframe id='fun-facts-iframe'></iframe>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class='title__wrap'>
						<h1>
							<img src='<?php echo get_field('lang_flag_image'); ?>' />
							<div class='title'>
								<?php echo get_field('lang_description'); ?>
								<span><?php echo get_the_title(); ?></span>
							</div>
						</h1>

						<div class='filter'>

							<?php if (get_field('filter_section')) : ?>
								<div class='filter__item type'>
									<div class='filter__item-inner'>
										<strong>Filter</strong>
										<a href="javascript: void(0);" class='filter-all active'>All</a>
										<a href="javascript: void(0);" class='filter-P' data-tooltip="Less than 40 words">Picture Cards</a>
										<a href="javascript: void(0);" class='filter-1' data-tooltip="40-90 words">Level 1</a>
										<a href="javascript: void(0);" class='filter-2' data-tooltip="91-220 words">Level 2</a>
										<a href="javascript: void(0);" class='filter-3' data-tooltip="221-540 words">Level 3</a>
										<a href="javascript: void(0);" class='filter-4+' data-tooltip="541-1200+ words">Level 4+</a>
										<a href="javascript: void(0);" class='filter-nf'>Non-fiction</a>
										<a href="javascript: void(0);" class='filter-q'>Quiz</a>
									</div>		
								</div>	
							<?php endif; ?>

							<div class='filter__item sort'>
								<div class='filter__item-inner'>
									<strong>Sort</strong>
									<a href="#" class='sort-latest'>Latest Release</a>
									<a href="#" class='sort-views'>Most Popular</a>
									<a href="#" class='sort-az'>Sort A-Z</a>
									<a href="#" class='sort-za'>Sort Z-A</a> 
								</div>
							</div>

							<div class='filter__item search'>
								<input type='text' placeholder="Search" />
							</div>

							<?php if ((get_field('fun_facts_enabled') && get_field('fun_facts_position') == 'below-search')) : ?>
								<div class='fun-facts-below-search'>
									<a 	href='javascript: void(0);' 
										id='fun-facts-btn'
										class='_btn'
										data-html-src='<?php echo get_field('fun_facts_media_html'); ?>'>
										<?php echo get_field('fun_facts_cta_text'); ?>
									</a>
								</div>
							<?php endif; ?>
						
						</div>
					</div>
					<div><!-- extra element for grid --></div>
				</div>

				<?php if ($playlistArr && !$isReadingPack): ?>
					<div class='playlist-wrap col-<?php echo count($playlistArr ?? []); ?>'>
						<?php foreach ($playlistArr as $playlist => $p): ?>

							<div class='playlist-item'>
								<div class='title'>
									<h4><?php echo $p['display_title'];?></h4>
								</div>

								<div class='book'>
									<a href='<?php echo $p['playlist_permalink']; ?>'>
										<img src='<?php echo $p['book_image_url']; ?>' />
									</a>
								</div>

								<div class='featured-buttons'>
									<a href='<?php echo $p['playlist_permalink']; ?>' class='_btn'>
										<?php echo $p['button_label']; ?>
										<?php if ($btn['book_type'] == 'flipbook') : ?>
											<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icon-book.png?t=3" alt="">
										<?php else : ?>
											<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icon-play.png?t=3" alt="">
										<?php endif; ?>
									</a>
								</div>
							</div>

						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class='books-wrap <?php echo (get_query_var('reading-pack', false) !== false) ? 'reading-pack' : ''; ?> col-<?php echo get_field('layout'); ?> <?php echo get_post_field('post_name', get_the_ID()); ?>'>

					<?php if ($bookArr): ?>
						<?php foreach ($bookArr as $book => $b): ?>

							<div	class='book-item' 
									data-level='<?php echo $b['level']; ?>'
									data-published='<?php echo $b['date_published']; ?>'
									data-views='<?php echo $b['views']; ?>'
									data-tier='<?php echo $b['tier']; ?>'
									data-quiz='<?php echo $b['has_quiz']; ?>'
									data-non-fiction='<?php echo $b['is_non_fiction']; ?>'
									data-english-title='<?php echo $b['english_title']; ?>'
									data-native-title='<?php echo $b['native_title']; ?>'>

								<div class='title'> 
									<?php /*<h4><?php echo $b['native_title']; ?> (<?php echo $b['tier']; ?>)</h4>*/ ?>
									<?php /*<h5><?php echo $b['views']; ?></h5>*/ ?>
									<h4><?php echo $b['native_title']; ?></h4>
									<?php if (!l4k_compareStringsNoSpecial($b['native_title'], $b['english_title'])) : ?>
										<h5><span><?php echo $b['english_title']; ?></span></h5>
									<?php endif; ?> 
								</div>

								<div class='book'>
									<!-- <a href='<?php //echo $b['book_permalink']; ?>'> -->
									<?php
									$link = $b['book_permalink'];
									if ($isReadingPack) {
										$link = trailingslashit($link) . 'reading-pack/';
									}
									?>
									<a href='<?php echo esc_url($link); ?>'>	
										<img src='<?php echo $b['image_url']; ?>' />
										<div class='book-level'><?php echo $b['level_nicename']; ?></div>
									</a>
								</div>

								<div class='featured-buttons'>
									<?php if ($b['book_buttons']) : ?>
										<?php foreach ($b['book_buttons'] as $button => $btn) : ?>
											<!-- <a href='<?php //echo $btn['book_permalink']; ?>' class='_btn'> -->
												<?php
												$btnLink = $btn['book_permalink'];
												if ($isReadingPack) {
													$btnLink = trailingslashit($btnLink) . 'reading-pack/';
												}
												?>
												<a href='<?php echo esc_url($btnLink); ?>' class='_btn'>
												<?php echo $btn['button_label']; ?>
												<?php if ($btn['book_type'] == 'flipbook') : ?>
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icon-book.png?t=3" alt="">
												<?php else : ?>
													<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icon-play.png?t=3" alt="">
												<?php endif; ?>
											</a>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>

							</div>

						<?php endforeach; ?>
					<?php endif; ?>

				</div>

			</div>

			<?php get_template_part('template-parts/section', 'sidebar-main'); ?>

		</div>
		
	</div>
</div>

<?php /* <pre><?php print_r($bookArr); ?></pre> */ ?>

<?php get_footer(); ?>