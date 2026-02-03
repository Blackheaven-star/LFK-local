<?php 
$staffPage 	= get_page_by_path('staff-access');
$user 		= wp_get_current_user(); 
$bookCount 	= l4k_getBookCountPerLanguage();
$summary 	= l4k_countTotalBookCountAndLatestRelease($bookCount);
?>

<div class='sidebar dashboard'>	

	<div class='logged-in-details'>
		<div class='label'>Staff Details</div>
		<div class='details'>
			<div class='item'>
				<i class="lni lni-user-4"></i> 
				<?php echo $user->user_email; ?>
			</div>
			<div class='item'>
				<i class="lni lni-buildings-1"></i>
				<?php if (current_user_can('administrator')): ?>
					Administrator (all libraries)
				<?php else: ?>
					<?php echo get_the_title(get_field('library', 'user_'.$user->ID)); ?>
				<?php endif; ?>
			</div>
			<div class='item'>
				<a href='<?php echo home_url('/?member-logout'); ?>'>
					<i class="lni lni-power-button"></i> 
					Logout
				</a>
			</div>
		</div>
	</div>

	<h3>Key Links</h3>

	<ul class='links'>
		
		<li>
			<a class='_btn' href='javascript:void(0);' onclick='printDashboardReport()'>
				Download PDF
			</a>
		</li>

		<li>
			<a class='_btn' target='_blank' href='<?php echo get_field('promotional_collateral_link', $staffPage->ID); ?>'>
				<?php echo get_field('promotional_collateral_label', $staffPage->ID); ?>
			</a>
		</li>

		<li>
			<a class='_btn btn-marc' href='javascript: void(0);'>
				<?php echo get_field('marc_records_label', $staffPage->ID); ?>
			</a>
			<div class='embed__overlay marc'>
				<div class='embed__wrap'>
					<div class='embed__wrap__inner'>
						<div class='embed__decoration'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf1.png" alt="leaf"></div>
						<div class='embed__decoration-2'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf3.png" alt="leaf"></div>
						<a class='embed__close'><i class="lni lni-xmark"></i></a>
						<div class='embed__title'>
							<?php echo get_field('marc_records_popup_header', $staffPage->ID); ?>
						</div>
						<div class='embed__content'>
							<?php if(have_rows('marc_records_records', $staffPage->ID)): ?>
								<table class='marc-records-table'>
									<tr>
										<th>Release Title</th>
										<th>Release Date</th>
										<th></th>
									</tr>
								    <?php while(have_rows('marc_records_records', $staffPage->ID)): the_row(); ?>
										<tr>
											<td><?php the_sub_field('release_title'); ?></td>
											<td><?php the_sub_field('release_date'); ?></td>
											<td><a href='<?php the_sub_field('download_link'); ?>'>Download</a></td>
										</tr>
								    <?php endwhile; ?>
								</table>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</li>
		
		<li>
			<a class='_btn' target='_blank'href='<?php echo get_field('portal_user_guide_link', $staffPage->ID); ?>'>
				<?php echo get_field('portal_user_guide_label', $staffPage->ID); ?>
			</a>
		</li>
		
		<li>
			<a class='_btn' target='_blank'href='<?php echo get_field('firewall_whitelisting_guide_link', $staffPage->ID); ?>'>
				<?php echo get_field('firewall_whitelisting_guide_label', $staffPage->ID); ?>
			</a>
		</li>
		
		<li>
			<a class='_btn' target='_blank'href='<?php echo get_field('content_catalogue_link', $staffPage->ID); ?>'>
				<?php echo get_field('content_catalogue_label', $staffPage->ID); ?>
			</a>
		</li>
		
		<li>
			<a class='_btn btn-breakdown' href='javascript: void(0);'>
				<?php echo get_field('language_breakdown_label', $staffPage->ID); ?>
			</a>
			<div class='embed__overlay breakdown'>
				<div class='embed__wrap'>
					<div class='embed__wrap__inner'>
						<div class='embed__decoration'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf1.png" alt="leaf"></div>
						<div class='embed__decoration-2'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf3.png" alt="leaf"></div>
						<a class='embed__close'><i class="lni lni-xmark"></i></a>
						<div class='embed__title'>
							Languages - Title Breakdown
						</div>
						<div class='embed__content'>
							<div class='breakdown-wrap'>
								<div class='summary'>
									<div class='total-titles'>Total Titles: <span><?php echo $summary['total_count']; ?></span></div>
									<div class='most-recent'>Latest Release: <span><?php echo $summary['most_recent']; ?></span></div>
									<div class='_clear'></div>
								</div>

								<table class='breakdown-table'>
									<tr>
										<th>Language</th><th class='book-count'>Titles</th>
										<th>Language</th><th class='book-count'>Titles</th>
										<th>Language</th><th class='book-count'>Titles</th>
									</tr>
									<?php $counter = 0; ?>
									<?php while($counter < count($bookCount[0])) : ?>
										<tr>
											<td class='title'>
												<img src='<?php echo $bookCount[0][$counter]['flag_url']; ?>' />
												<a href='<?php echo get_permalink($bookCount[0][$counter]['lang_id']); ?>'>
													<?php echo $bookCount[0][$counter]['title']; ?>
												</a>
											</td>
											<td class='book-count'><?php echo $bookCount[0][$counter]['book_count']; ?></td>

											<td class='title'>
												<img src='<?php echo $bookCount[1][$counter]['flag_url']; ?>' />
												<a href='<?php echo get_permalink($bookCount[1][$counter]['lang_id']); ?>'>
													<?php echo $bookCount[1][$counter]['title']; ?>
												</a>
											</td>
											<td class='book-count'><?php echo $bookCount[1][$counter]['book_count']; ?></td>

											<td class='title'>
												<img src='<?php echo $bookCount[2][$counter]['flag_url']; ?>' />
												<a href='<?php echo get_permalink($bookCount[2][$counter]['lang_id']); ?>'>
													<?php echo $bookCount[2][$counter]['title']; ?>
												</a>
											</td>
											<td class='book-count'><?php echo $bookCount[2][$counter]['book_count']; ?></td>
										</tr>	
										<?php $counter++; ?>
									<?php endwhile; ?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

		</li>
		
		<li>
			<a class='_btn' target='_blank'href='<?php echo get_field('subscription_terms_link', $staffPage->ID); ?>'>
				<?php echo get_field('subscription_terms_label', $staffPage->ID); ?>
			</a>
		</li> 

	</ul>

</div>