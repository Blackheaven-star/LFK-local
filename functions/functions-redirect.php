<?php 
/**
 * ----------------------------------------------------------------
 * Check if member is logged in
 * Otherwise, redirect with message
 * ----------------------------------------------------------------
 */

function l4k_checkMemberLoggedIn() {

	// start session if not already started
	if (session_status() === PHP_SESSION_NONE) { 
		session_start(); 
	}
    
    // if not logged in, redirect to home page
    // do not do this redirect if on home page
    // do not do this redirect if on library login page
    if (empty($_SESSION['library_barcode']) && !is_front_page() && !is_singular('library')) { 
    	wp_safe_redirect(home_url()); 
    	exit; 
    }

	// if on /libraries/au-demo for example and IS logged in
	// the user should be redirected back to /member-home
	if (is_singular('library') && (get_query_var('dashboard', false) === false) && (!empty($_SESSION['library_barcode']))) { 
		wp_safe_redirect(home_url().'/member-home'); 
		exit; 
	}

    // if on /libraries/au-demo/dashboard for example and NOT logged in
    // the user should be redirected back to /libraries/au-demo
    if (is_singular('library') && (get_query_var('dashboard', false) !== false) && (empty($_SESSION['library_barcode']))) { 
    	wp_safe_redirect(get_permalink(get_the_ID())); 
    	exit; 
    }

	// if on /libraries/au-demo/dashboard for example and IS logged in
    // if there's a WP user logged in, allow this
    // otherwise, redirect to member-home
	if (is_singular('library') && (get_query_var('dashboard', false) !== false) && (!is_user_logged_in())) { 
    	wp_safe_redirect(home_url().'/member-home'); 
    	exit; 
    }

    // if going to home page but current logged in, redirect back to member-home
    if (is_front_page() && (!empty($_SESSION['library_barcode']))) { 
    	wp_safe_redirect(home_url().'/member-home'); 
    	exit; 
    }

}

/**
 * ----------------------------------------------------------------
 * Check if previously logged in
 * ----------------------------------------------------------------
 */

function l4k_checkCookieLoggedIn() {

	if (isset($_COOKIE['remember_member'])) 
    {
        $data = json_decode(base64_decode($_COOKIE['remember_member']), true);
    	$_SESSION['library_id'] 			= $data['library_id'];
    	$_SESSION['library_welcome_logo'] 	= $data['library_welcome_logo'];
    	$_SESSION['library_permalink'] 		= $data['library_permalink'];
    	$_SESSION['library_barcode'] 		= $data['library_barcode'];
    	$_SESSION['library_name'] 			= $data['library_name'];
    	$_SESSION['library_region'] 		= $data['library_region'];
    	$_SESSION['library_remember'] 		= $data['library_remember'];
    	$_SESSION['last_viewed_book'] 		= $data['last_viewed_book'];
    	$_SESSION['auth_token'] 			= $data['auth_token'];
    	$_SESSION['auto_login_status'] 		= $data['auto_login_status'];
    	return true;
    }

    return false;

}

/**
 * ----------------------------------------------------------------
 * Check if auto logged in via whitelisted DOMAIN
 * ----------------------------------------------------------------
 */

function l4k_checkDomainAutoLogin() {

	$referrer = l4k_getClientReferrerDomain(); // var_dump($referrer);

    if (have_rows('domain_whitelist', 'option')) {
        while (have_rows('domain_whitelist', 'option')) {

            the_row();
        	if (get_sub_field('referrer') == $referrer) {
        		$libraryID = get_sub_field('library');
				$_SESSION['library_id'] 			= $libraryID;
		    	$_SESSION['library_welcome_logo'] 	= get_field('logo_welcome', $libraryID);
		    	$_SESSION['library_permalink'] 		= get_the_permalink($libraryID);
		    	$_SESSION['library_barcode'] 		= get_the_title($libraryID) . ' Domain Login';
		    	$_SESSION['library_name'] 			= get_the_title($libraryID);
		    	$_SESSION['library_region'] 		= get_field('library_group_region', $libraryID);
		    	$_SESSION['library_remember'] 		= 1;
		    	$_SESSION['last_viewed_book'] 		= '';
		    	$_SESSION['auth_token'] 			= get_field('auto_login_auth_token', $libraryID);
		    	$_SESSION['auto_login_status'] 		= get_field('auto_login_status', $libraryID);

		    	l4k_addWebActivity('900'); // record event 900 for member login
        		return true;
        	}

        }
	}

	return false;

}

/**
 * ----------------------------------------------------------------
 * Check if auto logged in via whitelisted IP
 * ----------------------------------------------------------------
 */

function l4k_checkIpAutoLogin() {

	$visitorIP = l4k_getClientIP();
    if (empty($visitorIP)) { return false; } // unable to get the IP of the visitor
    $visitorIPLong = sprintf('%u', ip2long($visitorIP)); // always compare IPs as integers
    
    // get all libraries
    $libraries = get_posts([
        'post_type'      => 'library',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    if (empty($libraries)) { return false; }

    foreach ($libraries as $libraryID) {
        if (have_rows('library_whitelisted_ips_ranges', $libraryID)) {
            while (have_rows('library_whitelisted_ips_ranges', $libraryID)) {
                the_row();

                $ipFrom = get_sub_field('ip_address_from');
                $ipTo   = get_sub_field('ip_address_to');

                if (empty($ipFrom) || empty($ipTo)) { continue; }

                $fromLong = sprintf('%u', ip2long($ipFrom)); // always compare IPs as integers
                $toLong   = sprintf('%u', ip2long($ipTo)); // always compare IPs as integers

                // once found, create the session
                if ($visitorIPLong >= $fromLong && $visitorIPLong <= $toLong) {
					$_SESSION['library_id'] 			= $libraryID;
			    	$_SESSION['library_welcome_logo'] 	= get_field('logo_welcome', $libraryID);
			    	$_SESSION['library_permalink'] 		= get_the_permalink($libraryID);
			    	$_SESSION['library_barcode'] 		= get_the_title($libraryID) . ' IP Login';
			    	$_SESSION['library_name'] 			= get_the_title($libraryID);
			    	$_SESSION['library_region'] 		= get_field('library_group_region', $libraryID);
			    	$_SESSION['library_remember'] 		= 1;
			    	$_SESSION['last_viewed_book'] 		= '';
			    	$_SESSION['auth_token'] 			= get_field('auto_login_auth_token', $libraryID);
			    	$_SESSION['auto_login_status'] 		= get_field('auto_login_status', $libraryID);

			    	l4k_addWebActivity('900'); // record event 900 for member login
			    	return $libraryID;
                }
            }
        }
    }

    return false; // no match

}

/**
 * ----------------------------------------------------------------
 * Check if auth param is present and determine auto login
 * ----------------------------------------------------------------
 */

function l4k_checkAuthAutoLogin() {

	if (isset($_GET['auth'])) 
    {

		$metaQuery = [
		    'relation' => 'AND',
		    ['key' => 'library_subscription_status', 'value' => 1, 'compare' => '='],
		    ['key' => 'auto_login_auth_token', 'value' => $_GET['auth'], 'compare' => '=']
		];

	    $library = get_posts([
	        'post_type'      => 'library',
	        'post_status'    => 'publish',
	        'posts_per_page' => -1,
	        'meta_query'     => $metaQuery,
	    ]);

	    // check if there's a library that has the auth token
	    // check too if that library has the auto login status set to true
		if ((!empty($library)) && (get_field('auto_login_status', $library[0]->ID))) 
		{
		    $library = $library[0];
			$_SESSION['library_id'] 			= $library->ID;
	    	$_SESSION['library_welcome_logo'] 	= get_field('logo_welcome', $library->ID);
	    	$_SESSION['library_permalink'] 		= get_the_permalink($library->ID);
	    	$_SESSION['library_barcode'] 		= get_the_title($library->ID) . ' Auto Login';
	    	$_SESSION['library_name'] 			= get_the_title($library->ID);
	    	$_SESSION['library_region'] 		= get_field('library_group_region', $library->ID);
	    	$_SESSION['library_remember'] 		= 1;
	    	$_SESSION['last_viewed_book'] 		= '';
	    	$_SESSION['auth_token'] 			= $_GET['auth'];
	    	$_SESSION['auto_login_status'] 		= get_field('auto_login_status', $library->ID);

	    	l4k_addWebActivity('900'); // record event 900 for member login
	    	
	    	return true;
		}

    }

    return false;

}

/**
 * ----------------------------------------------------------------
 * On WP user login, create a session for the member-home too
 * ----------------------------------------------------------------
 */

function l4k_loginMemberAfterWPUserLogin($user_login, $user) {

    if (!session_id()) { session_start(); } // make sure session exists
	$libraryID = get_field('library', 'user_'.$user->ID); // get library ID of the current logged in user

	if (!empty($libraryID)) 
	{
		$_SESSION['library_id'] 			= $libraryID;
    	$_SESSION['library_welcome_logo'] 	= get_field('logo_welcome', $libraryID);
    	$_SESSION['library_permalink'] 		= get_the_permalink($libraryID);
    	$_SESSION['library_barcode'] 		= get_the_title($libraryID) . ' Staff Login';
    	$_SESSION['library_name'] 			= get_the_title($libraryID);
    	$_SESSION['library_region'] 		= get_field('library_group_region', $libraryID);
    	$_SESSION['library_remember'] 		= 1;
    	$_SESSION['last_viewed_book'] 		= '';
    	$_SESSION['auth_token'] 			= get_field('auto_login_auth_token', $libraryID);
    	$_SESSION['auto_login_status'] 		= get_field('auto_login_status', $libraryID);

    	l4k_addWebActivity('900'); // record event 900 for member login
	}

} 
add_action('wp_login', 'l4k_loginMemberAfterWPUserLogin', 10, 2);

/**
 * ----------------------------------------------------------------
 * When visiting Staff Access page, check if staff is logged in
 * If yes, redirect to library's dashboard
 * ----------------------------------------------------------------
 */

function l4k_checkStaffLoggedIn() {

	if (get_query_var('dashboard', false) === false) {
		if (is_user_logged_in()) { 
			$customRedirect = get_the_permalink(get_field('library', 'user_'.get_current_user_id())).'dashboard'; 
			wp_safe_redirect($customRedirect); 
		} 
	}

	return; 

}

/**
 * ----------------------------------------------------------------
 * For staff, block access to wp-admin login and etc.
 * ----------------------------------------------------------------
 */

function l4k_blockSubscriberAdminAccess() {

    if (wp_doing_ajax()) { return; } // allow admin-ajax requests

    if (is_admin() && is_user_logged_in()) 
    {
        $user = wp_get_current_user();
        if (in_array('subscriber', (array) $user->roles)) 
        {
            $redirect = wp_get_referer(); // get previous page
            if (!$redirect) { $redirect = home_url(); } // fallback to home if referrer is empty
            wp_safe_redirect($redirect);
            exit;
        }
    }

}
add_action('init', 'l4k_blockSubscriberAdminAccess');

/**
 * ----------------------------------------------------------------
 * Anyone who tries to access wp-login.php, redirect to wp-admin
 * ----------------------------------------------------------------
 */

add_action('init', function () {

    if (is_user_logged_in() && $GLOBALS['pagenow'] === 'wp-login.php' && !isset($_GET['action'])) {
        wp_safe_redirect(admin_url());
        exit;
    }

});

/**
 * ----------------------------------------------------------------
 * Auto-redirect all books like the below 
 * OLD /aiovg_videos/the-fish-and-the-cat-japanese-romaji-flipbook/	
 * NEW /below/the-fish-and-the-cat-japanese-romaji-flipbook/	
 * ----------------------------------------------------------------
 */

add_action('template_redirect', function() {

    $request_uri = $_SERVER['REQUEST_URI'];
    
    if (strpos($request_uri, 'aiovg_videos') !== false) { // check if the URL contains 'aiovg_videos'
        
        $pattern = '/aiovg_videos\/(.+?)(\/|$)/'; // extract the slug (the part after aiovg_videos/)
        if (preg_match($pattern, $request_uri, $matches)) 
        {
            $slug = $matches[1];
            $new_url = home_url('/books/' . $slug . '/'); // build the new URL
            wp_redirect($new_url, 301); // perform 301 permanent redirect
            exit;
        }
    }

});

/**
 * ----------------------------------------------------------------
 * Generate barcode from trial/competition and auto login
 * ----------------------------------------------------------------
 */ 

function l4k_trialFormAutoLogin() {

	$allowedDomains = array('localhost', 'v2.lote4kids.com', 'lote4kids.com');

	if (in_array(l4k_getClientReferrerDomain(), $allowedDomains)) {
		
		$libraryID = get_the_ID();
		$_SESSION['library_id'] 			= $libraryID;
    	$_SESSION['library_welcome_logo'] 	= get_field('logo_welcome', $libraryID);
    	$_SESSION['library_permalink'] 		= get_the_permalink($libraryID);
    	$_SESSION['library_barcode'] 		= $_SESSION['trial_barcode'];
    	$_SESSION['library_name'] 			= get_the_title($libraryID);
    	$_SESSION['library_region'] 		= get_field('library_group_region', $libraryID);
    	$_SESSION['library_remember'] 		= 1;
    	$_SESSION['last_viewed_book'] 		= '';
    	$_SESSION['auth_token'] 			= get_field('auto_login_auth_token', $libraryID);
    	$_SESSION['auto_login_status'] 		= get_field('auto_login_status', $libraryID);

    	l4k_addWebActivity('900'); // record event 900 for member login

	}

}

?>