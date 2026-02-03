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
if ($current_slug === 'all-books-by-language') :

    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_GET['languageId'])) {
        http_response_code(400);
        echo json_encode([
            'error'   => 'missing_parameters',
            'message' => 'languageId parameter is required'
        ], JSON_PRETTY_PRINT);
        exit; 
    }

    $languageId = absint($_GET['languageId']);

    if ($languageId === 0) {
        http_response_code(400);
        echo json_encode([
            'error'   => 'invalid_parameter',
            'message' => 'languageId must be a valid ID'
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $allBooksByLangArr = l4k_getAllBooksByLanguageForMobile($languageId);

    echo json_encode($allBooksByLangArr, JSON_PRETTY_PRINT);
    exit;

endif;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/book-details/
 * This provides the book details based on $_GET $bookId
 * ----------------------------------------------------------------
 */

if ($current_slug === 'book-details') :

    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_GET['bookId'])) {
        http_response_code(400);
        echo json_encode([
            'error'   => 'missing_parameters',
            'message' => 'bookId parameter is required'
        ], JSON_PRETTY_PRINT);
        exit; 
    }

    $bookId = absint($_GET['bookId']);

    if ($bookId === 0) {
        http_response_code(400);
        echo json_encode([
            'error'   => 'invalid_parameter',
            'message' => 'bookId must be a valid ID'
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $allBooksDetailsArr = l4k_getBookDetails($bookId);

    echo json_encode($allBooksDetailsArr, JSON_PRETTY_PRINT);
    exit;

endif;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/book-comments/
 * This provides the book comments based on $_GET $bookId
 * ----------------------------------------------------------------
 */

if ($current_slug == 'book-comments') :

	header('Content-Type: application/json; charset=utf-8');

endif;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/mobile-notification/
 * This provides the mobile notification that was set in the WP CMS
 * ----------------------------------------------------------------
 */

if (isset($current_slug) && $current_slug === 'mobile-notification') :

    header('Content-Type: application/json; charset=utf-8');

    $required = ['library_name', 'device_id', 'os_type', 'device_type'];

    foreach ($required as $param) {
        if (empty($_GET[$param])) {
            http_response_code(400);
            echo json_encode([
                'error'   => 'missing_parameters',
                'message' => "{$param} parameter is required"
            ], JSON_PRETTY_PRINT);
            exit;
        }
    }

    $library_name = sanitize_text_field($_GET['library_name']);
    $device_id    = sanitize_text_field($_GET['device_id']);
    $os_type      = sanitize_text_field($_GET['os_type']);
    $device_type  = sanitize_text_field($_GET['device_type']);

    $mobileNotificationArr = l4k_getMobileAppNotification(
        $library_name,
        $device_id,
        $os_type,
        $device_type
    );

    echo json_encode($mobileNotificationArr, JSON_PRETTY_PRINT);
    exit;

endif;

/**
 * ----------------------------------------------------------------
 * Output for /endpoints/mobile-app-rating/
 * This provides the mobile app rating that was set in the WP CMS
 * ----------------------------------------------------------------
 */

if ($current_slug == 'mobile-app-rating') :

	header('Content-Type: application/json; charset=utf-8');

      $required = ['library_name', 'device_id', 'os_type', 'device_type'];

    foreach ($required as $param) {
        if (empty($_GET[$param])) {
            http_response_code(400);
            echo json_encode([
                'error'   => 'missing_parameters',
                'message' => "{$param} parameter is required"
            ], JSON_PRETTY_PRINT);
            exit;
        }
    }

    $library_name = sanitize_text_field($_GET['library_name']);
    $device_id    = sanitize_text_field($_GET['device_id']);
    $os_type      = sanitize_text_field($_GET['os_type']);
    $device_type  = sanitize_text_field($_GET['device_type']);

    $mobileAppRatingArr = l4k_getMobileAppRating($library_name, $device_id, $os_type, $device_type);

    echo json_encode($mobileAppRatingArr, JSON_PRETTY_PRINT);

	exit;

endif;
?>