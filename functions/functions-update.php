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
            'library_name' 	=> $_SESSION['library_name'],
            'region_name' 	=> $_SESSION['library_region'],
            'data' 			=> json_encode($dataArr),
            'ip' 			=> l4k_getClientIP(),
        )
    );

    return $wpdb->insert_id;

}

/**
 * ----------------------------------------------------------------
 * Capture data from trial form
 * ----------------------------------------------------------------
 */

function l4k_saveFormToDB($fields, $entry, $formData, $entry_id) {

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
    $email 			= !empty($fields[3]['value']) ? $fields[3]['value'] : '';
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
}
add_action('wpforms_process_complete', 'l4k_saveFormToDB', 10, 4);

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
?>