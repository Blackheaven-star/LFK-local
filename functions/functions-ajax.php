<?php 
/**
 * ----------------------------------------------------------------
 * Login from the library barcode
 * ----------------------------------------------------------------
 */

add_action('wp_ajax_l4k_loginToLibrary', 'l4k_loginToLibrary');
add_action('wp_ajax_nopriv_l4k_loginToLibrary', 'l4k_loginToLibrary');

function l4k_loginToLibrary() {

    if (!session_id()) { session_start(); }

    if ($_POST['library_id']) 
    {
    	$libraryDetails = l4k_getLibraryDetails($_POST['library_id']);
    }

    $overallMatched = false;
	$matchedExpiredMsg = '';

    if ($libraryDetails['barcodes']) {
		foreach ($libraryDetails['barcodes'] as $barcode => $b) {

			$matchedBarcode = true;
			$matchedErrorMsg = '';

			// check barcode prefix
			if (stripos($_POST['barcode'], $b['barcode_prefix']) !== 0) 
			{ 
				$matchedBarcode = false; 
				$matchedErrorMsg = 'Invalid library barcode or card number. Please try again.';
			} 

			// check barcode length
			if (strlen($_POST['barcode']) != $b['barcode_length']) 
			{ 
				$matchedBarcode = false; 
				$matchedErrorMsg = 'Invalid library barcode or card number. Please try again.';
			} 

			// check barcode validity only if start/end date has values
			if ($b['barcode_start_date'] && $b['barcode_end_date'] && $matchedBarcode)
			{
				$start = strtotime($b['barcode_start_date']);
				$end   = strtotime($b['barcode_end_date']);
				$now   = time();

				if ($now < $start || $now > $end) {
					$matchedBarcode = false; 
					$matchedExpiredMsg = 'Barcode expired. Please contact us at <a href="/contact-us" target="_blank">here</a>.';
				}
			}

			if ($matchedBarcode) { $overallMatched = true; }

			$rawData .= 'barcode_prefix : ' . $b['barcode_prefix'] . '<br/>';
			$rawData .= 'barcode_length : ' . $b['barcode_length'] . '<br/>';
			$rawData .= 'barcode_start_date : ' . $b['barcode_start_date'] . '<br/>';
			$rawData .= 'barcode_end_date : ' . $b['barcode_end_date'] . '<br/>';
			$rawData .= 'matchedBarcode : ' . $matchedBarcode . '<br/>';
			$rawData .= 'matchedErrorMsg : ' . $matchedErrorMsg . '<br/>';
			$rawData .= 'matchedExpiredMsg : ' . $matchedExpiredMsg . '<br/>';
			$rawData .= "<hr/>";

		}
    }

    if ($overallMatched) 
    {
    	$_SESSION['library_id'] 			= $_POST['library_id'];
    	$_SESSION['library_welcome_logo'] 	= get_field('logo_welcome', $_POST['library_id']);
    	$_SESSION['library_permalink'] 		= get_the_permalink($_POST['library_id']);
    	$_SESSION['library_barcode'] 		= sanitize_text_field($_POST['barcode']);
    	$_SESSION['library_name']	 		= get_the_title($_POST['library_id']);
    	$_SESSION['library_region'] 		= get_field('library_group_region', $_POST['library_id']);
    	$_SESSION['library_remember'] 		= intval($_POST['remember']);  
    	$_SESSION['last_viewed_book'] 		= '';  
    	$_SESSION['auth_token'] 			= get_field('auto_login_auth_token', $_POST['library_id']);
    	$_SESSION['auto_login_status'] 		= get_field('auto_login_status', $_POST['library_id']);

		if ($_POST['remember']) 
		{
		    // create persistent cookie storing session data
		    $cookie_data = json_encode([
		        'library_id' 			=> $_SESSION['library_id'],
		        'library_welcome_logo' 	=> $_SESSION['library_welcome_logo'],
		        'library_permalink' 	=> $_SESSION['library_permalink'],
		        'library_barcode' 		=> $_SESSION['library_barcode'],
		        'library_name' 			=> $_SESSION['library_name'],
		        'library_region' 		=> $_SESSION['library_region'],
		        'library_remember' 		=> $_SESSION['library_remember'],
		        'last_viewed_book' 		=> $_SESSION['last_viewed_book'],
		        'auth_token' 			=> $_SESSION['auth_token'],
		        'auto_login_status' 	=> $_SESSION['auto_login_status']
		    ]);

		    // cookie expires in 30 days
		    setcookie(
		        'remember_member',
		        base64_encode($cookie_data),
		        time() + 60*60*24*30, // 30 days
		        '/',
		        '',
		        false,
		        true
		    );
		}

		l4k_addWebActivity('900'); // record event 900 for member login

	    $resultsArr = array('status' 	=> 1,
		    				'message' 	=> 'Session started and saved!',
		    				'raw_data' 	=> $rawData);  	
    }
   	else
   	{
	    $resultsArr = array('status' 	=> 0,
		    				'message' 	=> ($matchedExpiredMsg) ? $matchedExpiredMsg : $matchedErrorMsg,
		    				'raw_data' 	=> $rawData);
   	}

   	wp_send_json($resultsArr);

}

/**
 * ----------------------------------------------------------------
 * Get dashboard content
 * ----------------------------------------------------------------
 */

add_action('wp_ajax_l4k_getLearningDashboardContent', 'l4k_getLearningDashboardContent');
add_action('wp_ajax_nopriv_l4k_getLearningDashboardContent', 'l4k_getLearningDashboardContent');

/**
 * ----------------------------------------------------------------
 * Log web activity via ajax
 * Reuse the function l4k_addWebActivity
 * Just pass the parameters to it
 * ----------------------------------------------------------------
 */

add_action('wp_ajax_l4k_addWebActivityViaAjax', 'l4k_addWebActivityViaAjax');
add_action('wp_ajax_nopriv_l4k_addWebActivityViaAjax', 'l4k_addWebActivityViaAjax');

function l4k_addWebActivityViaAjax() {

    if ($_POST) 
    { 
    	$alertCode = $_POST['alert_code'];

    	if ($alertCode == '1060') 
    	{
	    	$dataArr = array(	'Activity Name' => $_POST['activity_name'],
	    						'Activity Title' => $_POST['activity_title'],
	    						'Activity Type'	=> $_POST['activity_type']);    		
    	}
    	if ($alertCode == '1062') 
    	{
	    	$dataArr = array(	'Story ID' => $_POST['story_id'],
	    						'Story Title' => $_POST['story_title'],
	    						'Language' => $_POST['language'],
	    						'Type' => $_POST['type']);    		
    	}

    }

    $insertID = l4k_addWebActivity($alertCode, $dataArr);

    if ($insertID) 
    {
	    $resultsArr = array('status' 	=> 1,
		    				'insert_id' => $insertID,
		    				'message' 	=> 'Sucessfully logged '.$alertCode.'!');
	} 
	else 
	{ 
		$resultsArr = array('status' 	=> 0,
			    			'message' 	=> 'Unable to save to database!');
	}
   
   	wp_send_json($resultsArr);

}

/**
 * ----------------------------------------------------------------
 * Increment book view
 * ----------------------------------------------------------------
 */

add_action('wp_ajax_l4k_incrementViewsViaAjax', 'l4k_incrementViewsViaAjax');
add_action('wp_ajax_nopriv_l4k_incrementViewsViaAjax', 'l4k_incrementViewsViaAjax');

function l4k_incrementViewsViaAjax() {

    if ($_POST['book_id']) 
    { 
		$viewCount 	= l4k_incrementViews($_POST['book_id']);
	    $resultsArr = array('status' 		=> 1,
	    					'view_count' 	=> $viewCount,
		    				'message' 		=> 'Sucessfully incremented book view!');
    }
   
   	wp_send_json($resultsArr);

}

/**
 * Handle AJAX request to load video player for Book post type
 */

add_action('wp_ajax_aiovg_load_video', 'handle_book_playlist_load_video');
add_action('wp_ajax_nopriv_aiovg_load_video', 'handle_book_playlist_load_video');

function handle_book_playlist_load_video() {
    $video_id = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;
    if (!$video_id) wp_send_json_error(['message' => 'Invalid ID']);

    
    $video_url = get_post_meta($video_id, 'video_source', true); 
    $vimeo_id = '';
    $vimeo_hash = '';

    if (preg_match('/(?:vimeo\.com\/(?:video\/)?|player\.vimeo\.com\/video\/)(\d+)(?:\/([a-f0-9]+))?/i', $video_url, $matches)) {
        $vimeo_id = $matches[1];
        $vimeo_hash = isset($matches[2]) ? $matches[2] : '';
    }

    $iframe_src = "https://player.vimeo.com/video/{$vimeo_id}";
    $iframe_src .= (!empty($vimeo_hash)) ? "?h={$vimeo_hash}&autoplay=1" : "?autoplay=1";

    $player_html = sprintf(
        '<div class="aiovg-player">
            <div class="aiovg-responsive-container" style="padding-bottom: 56.25%%;">
                <iframe src="%s" class="aiovg-responsive-element" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
            </div>
        </div>',
        esc_url($iframe_src)
    );

    wp_send_json_success(['player_html' => $player_html]);
}
?>