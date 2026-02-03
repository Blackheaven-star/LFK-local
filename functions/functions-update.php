<?php
/**
 * ----------------------------------------------------------------
 * Increment number of views for book
 * ----------------------------------------------------------------
 */

function l4k_updateBookNumViews($bookID, $preventDoubleCount=false) {

    if ($preventDoubleCount) { $cookie = 'book_viewed_' . $book_id; }

    if ($preventDoubleCount) {
	    if (!isset($_COOKIE[$cookie])) {
			l4k_incrementViews($bookID);
	        setcookie($cookie, 1, time() + DAY_IN_SECONDS, '/'); // prevent recount for 24h
	    }
    } else {
    	l4k_incrementViews($bookID); // increment as usual without checking cookie
    }

	return;

}

function l4k_incrementViews($bookID) {

    $total  = (int) get_post_meta($bookID, 'additional_details_views', true);
    $recent = (int) get_post_meta($bookID, 'additional_details_views_last_3_months', true);

    update_post_meta($bookID, 'additional_details_views', $total + 1);  // lifetime views
    update_post_meta($bookID, 'additional_details_views_last_3_months', $recent + 1); // rolling 3-month views

    return $total + 1;

}

/**
 * ----------------------------------------------------------------
 * Record activity - web
 * ----------------------------------------------------------------
 */

function l4k_addWebActivity($alertCode, $dataArr=array()) {

    global $wpdb;

    $wpdb->insert(
        $wpdb->prefix . 'web_activity',
        array(
            'alert_code' 	=> $alertCode,
            'barcode' 		=> strtoupper($_SESSION['library_barcode']),
            'library_name' 	=> $_SESSION['library_group'], // save the group name instead of the library name
            'region_name' 	=> $_SESSION['library_region'],
            'data' 			=> json_encode($dataArr),
            'ip' 			=> l4k_getClientIP(),
        )
    );

    return $wpdb->insert_id;

}

/**
 * ----------------------------------------------------------------
 * Record user login activity (user_login_logs table)
 * ----------------------------------------------------------------
 */

function l4k_addUserLoginActivity($alertCode, $barCode, $libraryGroup, $region, $language, $time, $osType, $deviceType, $status) {

    global $wpdb;
    $table = $wpdb->prefix . 'user_login_logs';

    // check if combination of barCode, osType, and deviceType exists and get its ID
    $existingID = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT id FROM $table WHERE barcode = %s AND os_type = %s AND device_type = %s LIMIT 1",
            $barCode,
            $osType,
            $deviceType
        )
    );

    if ($existingID) { return $existingID; } // record exists, return its ID

    // record does not exist, insert new
    $wpdb->insert(
        $table,
        array(
            'alert_code'    => $alertCode,
            'barcode'       => $barCode,
            'library_group' => $libraryGroup,
            'region'        => $region,
            'language'      => $language,
            'time'          => $time,
            'os_type'       => $osType,
            'device_type'   => $deviceType,
            'status'        => $status
        ),
        array(
            '%s','%s','%s','%s','%s','%s','%s','%s','%s'
        )
    );

    return $wpdb->insert_id; // return the ID of the newly inserted record
}

/**
 * ----------------------------------------------------------------
 * Record user login activity (characters table)
 * ----------------------------------------------------------------
 */

function l4k_addCharacterActivity($ownerID) {

    global $wpdb;
    $table = $wpdb->prefix . 'characters';

    // check if ownerID exists in the characters table
    $existingID = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT id FROM $table WHERE ownerId = %s LIMIT 1",
            $ownerID,
        )
    );

    if ($existingID) { return $existingID; } // record exists, return its ID

    // record does not exist, insert new
    $wpdb->insert(
        $table,
        array(
            'ownerId' => $ownerID,
            'boughtItems' => '[]',
            'gainPointsFrom' => '[]',
            'points' => 1
        ),
        array(
            '%s', '%s', '%s', '%s'
        )
    );

    return $wpdb->insert_id; // return the ID of the newly inserted record
}

/**
 * ----------------------------------------------------------------
 * Capture data from trial form
 * ----------------------------------------------------------------
 */

function l4k_saveFormEntriesToDB($fields, $entry, $formData, $entry_id) {

    global $wpdb;
    
    $formID = $formData['id'];
    $libraryID = get_the_ID();
    
    // do NOT process or save to the database the following forms
    // 164381 - FAQ Contact Form 
    // 164353 - Main Contact Form  
    // 164243 - Sidebar Feedback Form 
    // 164240 - Footer Contact Form 
    $excludeArr = array('164381', '164353', '164243', '164240');
    if (in_array($formID, $excludeArr)) { return; }

	// get barcode prefix (first only) for the library
	$barcode = get_field('library_barcodes', $libraryID);
	if ($barcode && is_array($barcode)) {
	    $firstRow = $barcode[0];
	    $firstFieldValue = $firstRow['barcode_prefix'];
	}

    $barcodePrefix  = $firstFieldValue;
    $barcodeNumber  = l4k_getNextBarcodeNumber($barcodePrefix);
    $barcode 		= $barcodePrefix.$barcodeNumber;
    $name 			= !empty($fields[1]['value']) ? $fields[1]['value'] : ''; 
    $library 		= !empty($fields[2]['value']) ? $fields[2]['value'] : ''; 
    $email 			= !empty($fields[3]['value']) ? $fields[3]['value'] : 'test123@test.com';
    $phone	 		= !empty($fields[4]['value']) ? $fields[4]['value'] : '';
    $jobTitle 		= !empty($fields[5]['value']) ? $fields[5]['value'] : '';
	$currentTime 	= current_time('mysql');
    $expirationDate = date('Y-m-d H:i:s', strtotime($currentTime . ' +14 days'));

    // save to session so on auto redirect, we can login based on the details here
    $_SESSION['trial_library'] = $libraryID;
    $_SESSION['trial_barcode'] = $barcode;

	$result = $wpdb->insert(
	    $wpdb->prefix . 'alternate_barcode',
	    array(
	        'barcode_prefix' 	=> $barcodePrefix,
	        'barcode_number' 	=> $barcodeNumber,
	        'barcode' 			=> $barcode,
	        'name' 				=> $name,
	        'library' 			=> $library,
	        'email' 			=> $email,
	        'phone' 			=> $phone,
	        'job_title' 		=> $jobTitle,
	        'time' 				=> $currentTime,
	        'expiration_date' 	=> $expirationDate
	    ),
	    array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
	);

	if ($result === false) {
	    error_log('Insert failed: ' . $wpdb->last_error);
	}

	if ($result !== false) {
		l4k_send_getting_started_email($email, $name, $barcode);

		// Prepare the $trial array manually so schedule_trial_emails has what it needs
		$trial_data = [
		    'first_name' => $name,
		    'barcode'    => $barcode,
		    'trial_details' => [
		        'barcode' => $barcode,
		        'website' => $library
		    ],
		    'second_email_schedule' => 14 // Static fallback
		];

		schedule_trial_emails($email, $trial_data); 
	}

}
add_action('wpforms_process_complete', 'l4k_saveFormEntriesToDB', 10, 4);

function l4k_getNextBarcodeNumber($barcodePrefix) {

    global $wpdb;
    $tableName = $wpdb->prefix . 'alternate_barcode';
    
    // check if the barcodePrefix exists and get the highest barcode_number
    $result = $wpdb->get_var($wpdb->prepare(
        "SELECT barcode_number FROM $tableName WHERE barcode_prefix = %s ORDER BY barcode_number DESC LIMIT 1",
        $barcodePrefix
    ));
    
    if ($result !== null) { $nextNumber = intval($result) + 1; } // prefix exists, increment by 1
    else { $nextNumber = 23; } // prefix doesn't exist, start from 0023
    
    return str_pad($nextNumber, 4, '0', STR_PAD_LEFT); // pad with zeros to make it 4 digits

}

/**
 * ----------------------------------------------------------------
 * Increment feather count (video_claims table)
 * Feather count + 1 if visited a book
 * Feather count + 1 if clicked an activity in the sidebar
 * Feather count + 1 if visited /activities page
 * ----------------------------------------------------------------
 */

function l4k_addFeatherCount($ownerID, $url) {

    global $wpdb;
    $video_claims_table = $wpdb->prefix . 'video_claims';
    $characters_table = $wpdb->prefix . 'characters';

    // attempt to insert into video_claims first
    $result = $wpdb->insert(
        $video_claims_table,
        array(
            'ownerId'   => $ownerID,
            'videoUrl'  => $url,
            'claimedAt' => current_time('mysql')
        ),
        array('%s', '%s', '%s')
    );

    // if insert fails, stop immediately - log it
    if ($result === false) { error_log('Video claim insert failed: ' . $wpdb->last_error); return false; }

    // only increment characters points if insert succeeded
	$updated = $wpdb->query(
	    $wpdb->prepare(
	        "UPDATE $characters_table
	         SET points = LAST_INSERT_ID(points + 1)
	         WHERE ownerId = %s",
	        $ownerID
	    )
	);

	$newPointsCount = $wpdb->get_var("SELECT LAST_INSERT_ID()");

    // insert succeeded but points update failed — log it
    if ($updated === false) { error_log('Points update failed for ' . $ownerID . ': ' . $wpdb->last_error); return false; }

    return $newPointsCount;

}
?>