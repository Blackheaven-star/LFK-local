<?php 
global $post;
$current_slug = $post->post_name;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/activity-log/
 * This provides all the activity log that was recorded
 * ----------------------------------------------------------------
 */

if ($current_slug == 'activity-log') :

	header('Content-Type: application/json; charset=utf-8');

	$webActivityArr = l4k_getActivityLog();
	echo json_encode($webActivityArr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	
	exit;

endif;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/all-libraries/
 * This provides all the libraries
 * ----------------------------------------------------------------
 */

if ($current_slug == 'all-libraries') :

	header('Content-Type: application/json; charset=utf-8');

	$libraryArr = l4k_getLibraries(true);
	echo json_encode($libraryArr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

	exit;

endif;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/mobile-app-rating/
 * This provides the mobile app rating that was set in the WP CMS
 * ----------------------------------------------------------------
 */

if ($current_slug == 'mobile-app-rating') :

	// code here

endif;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/mobile-app-version/
 * This provides the mobile app rating that was set in the WP CMS
 * ----------------------------------------------------------------
 */

if ($current_slug == 'mobile-app-version') :

	header('Content-Type: application/json; charset=utf-8');

	$androidArr = array('current_play_store_version_code' => get_field('android_version_code', 'option'), 
						'current_play_store_version_name' => get_field('android_version_name', 'option'));
	$iosArr 	= array('current_app_store_version' => get_field('ios_store_version', 'option'), 
						'current_app_store_build' 	=> get_field('ios_store_build', 'option'));
	$mobileArr 	= array('android' => $androidArr, 'ios' => $iosArr);						

	echo json_encode($mobileArr, JSON_PRETTY_PRINT);
	exit;

endif;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/all-books-by-language/
 * This provides all the list of books based on $_GET $languageId
 * ----------------------------------------------------------------
 */

if ($current_slug == 'all-books-by-language') :

	// code here

endif;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/book-details/
 * This provides the book details based on $_GET $bookId
 * ----------------------------------------------------------------
 */

if ($current_slug == 'book-details') :

	// code here

endif;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/book-comments/
 * This provides the book comments based on $_GET $bookId
 * ----------------------------------------------------------------
 */

if ($current_slug == 'book-comments') :

	// code here

endif;
?>