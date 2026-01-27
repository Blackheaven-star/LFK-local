<?php
/**
 * ----------------------------------------------------------------
 * Retrieve all libraries
 * ----------------------------------------------------------------
 */

function l4k_getLibraries($forEndpoint=false) {

    $libraries = get_posts([
        'post_type'      => 'library',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => [
            [
                'key'     => 'library_subscription_status',
                'value'   => 1,
                'compare' => '=',
            ],
        ],
    ]);

    $libArr = [];

    if ($libraries) :
        foreach ($libraries as $id) :

        	if ($forEndpoint) :

        		// do this to ouput the data on /endpoints/all-libraries/ (used by mobile app)

        		$barcodeArr = array();
				if (have_rows('library_barcodes', $id)) :
					while (have_rows('library_barcodes', $id)) : the_row();
						$barcodeArr[] = array(	'barcode_prefix' 	=> get_sub_field('barcode_prefix'),
												'barcode_length' 	=> get_sub_field('barcode_length'), 
												'start_date' 		=> get_sub_field('barcode_start_date'), 
												'end_date' 			=> get_sub_field('barcode_end_date'));
					endwhile;
				endif;

	            $libArr[] = [
	                'id'				=> $id,
	                'title'				=> get_the_title($id),
	                'link'				=> get_post_field('post_name', $id),
	                'logo'				=> get_field('logo_dashboard', $id),
	                'banner'			=> get_field('logo_welcome', $id),
	                'description'		=> get_field('library_description', $id),
	                'group_name'		=> get_field('library_group_name', $id),
	                'group_region'		=> get_field('library_group_region', $id),
	                'barcode'			=> $barcodeArr
	            ];

	        else :

	        	// do this just to get all the libraries to be used within the website

	            $libArr[] = [
	                'title'         => get_the_title($id),
	                'lib_permalink' => get_permalink($id)
	            ];

	        endif;

        endforeach;
    endif;

    return $libArr;

}

/**
 * ----------------------------------------------------------------
 * Retrieve single library details
 * ----------------------------------------------------------------
 */

function l4k_getLibraryDetails($libraryID) {

    $library = get_post($libraryID);

    $libraryDetails = [
        'library_id'	=> $library->ID,
        'title'			=> $library->post_title,
        'barcodes'		=> get_field('library_barcodes', $library->ID)
    ];

    return $libraryDetails;

}

/**
 * ----------------------------------------------------------------
 * Retrieve all languages
 * ----------------------------------------------------------------
 */

function l4k_getLanguages($getComingSoon=false, $exclude=true) {

    $metaQuery = array();

    if ($exclude) { $excludeIDs = array('127596', '127598', '127600'); } // eng-au, eng-us, eng-uk

    if ($getComingSoon) 
    {
		$metaQuery = [
		    ['key' => 'lang_coming_soon','value' => 1,'compare' => '=']
		];
    }
    else
    {
		$metaQuery = [
		    'relation' => 'OR',
		    ['key' => 'lang_coming_soon', 'value' => 0],
		    ['key' => 'lang_coming_soon', 'compare' => 'NOT EXISTS']
		];
    }

    $languages = get_posts([
        'post_type'      => 'language',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => $metaQuery,
        'post__not_in'   => $excludeIDs,
    ]);

    $langArr = array();

    if ($languages) {
        foreach ($languages as $id) {
        	if (!$getComingSoon) { $latestBookReleased = l4k_getLatestBookReleaseByLanguage($id); }
            $langArr[] = [
                'lang_id'    		=> $id,
                'date_published'    => get_the_date('YmdHis', $id),
                'title'             => get_the_title($id),
                'native_label'      => get_field('lang_native_label', $id),
                'flag_url'          => get_field('lang_flag_image', $id),
                'book_latest'		=> ($latestBookReleased) ? $latestBookReleased->post_title : '',
                'book_latest_date'	=> date("YmdHis", strtotime($latestBookReleased->post_date)),
                'total_views'		=> (get_field('total_views', $id)) ? get_field('total_views', $id) : 0,
                'marketing'			=> get_field('marketing_collateral', $id),
                'lang_permalink'    => get_permalink($id)
            ];
        }
    }

    return $langArr;

}

/**
 * ----------------------------------------------------------------
 * Get latest book released for the language
 * ----------------------------------------------------------------
 */

function l4k_getLatestBookReleaseByLanguage($langID) {

    $book = get_posts([
        'post_type'      	=> 'book',
        'post_status'    	=> 'publish',
        'numberposts' 		=> 1,
		'meta_query'     	=> [
            [
                'key'     	=> 'language',
                'value'   	=> $langID,
                'compare' 	=> '=',
            ],
        ],
        'orderby'       	=> 'date',
        'order'          	=> 'DESC',
    ]);

    if ($book) { return $book[0]; }
    else { return; }

}

/**
 * ----------------------------------------------------------------
 * Retrieve single language details
 * ----------------------------------------------------------------
 */

function l4k_getLanguageDetails($langID) {

    $langDetails = array();

    $langDetails = [
        'lang_id'			=> get_the_ID(),
        'date_published'    => get_the_date('Ymd', $langID),
        'title'             => get_the_title($langID),
        'native_label'      => get_field('lang_native_label', $langID),
        'flag_url'          => get_field('lang_flag_image', $langID),
        'variant_label'		=> get_field('variant_label', $langID),
        'marketing'			=> get_field('marketing_collateral', $langID),
        'lang_permalink'    => get_permalink($langID)
    ];

    return $langDetails;

}

/**
 * ----------------------------------------------------------------
 * Retrieve all books based on language
 * ----------------------------------------------------------------
 */

function l4k_getBooks($langID, $isFeatured=true, $isReadingPack=false) {

	$metaQuery = ['relation' => 'AND'];

	// if featured, add featured and language filters
	if ($isFeatured) 
	{
	    $metaQuery[] = ['key' => 'featured', 'value' => 1];
	    $metaQuery[] = ['key' => 'language', 'value' => $langID];
	}

    $books = [];

    if($isReadingPack) {

        $MAX_READING_PACK = get_field('reading_pack_story_count', 'option');

        // get all Tier 1 books
        $tier1 = get_posts([
            'post_type'      => 'book',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => [
                'relation' => 'AND',
                ['key' => 'featured', 'value' => 1],
                ['key' => 'language', 'value' => $langID],
                ['key' => 'levels_tier','value' => 1, 'compare' => '=', 'type' => 'NUMERIC'],
            ],
        ]);

		$tier1 = array_slice($tier1, 0, $MAX_READING_PACK);
		$books = $tier1;

        // if tier1 is less than MAX_READING_PACK, get tier 2 books to fill up
        $remaining = $MAX_READING_PACK - count($books);

        if ($remaining > 0) {

            $tier2 = get_posts([
                'post_type' => 'book',
                'post_status' => 'publish',
                'posts_per_page' => $remaining,
                'fields' => 'ids',
                'meta_query' => [
                    'relation' => 'AND',
                    ['key' => 'featured', 'value' => 1],
                    ['key' => 'language', 'value' => $langID],
                    ['key' => 'levels_tier', 'value' => 2, 'type' => 'NUMERIC', 'compare' => '=']
                ],
                'meta_key' => 'additional_details_views_last_3_months',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
            ]);

            $books = array_merge($books, $tier2);

        }

    } 
    else 
    {

        // default all books behaviour
        $books = get_posts([
            'post_type' => 'book',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => $metaQuery,
            'meta_key' => 'levels_tier',
            'orderby' => [
                'meta_value_num' => 'ASC',
                'rand' => 'ASC'
            ],
        ]);

    }

    $bookArr = array();

    $langDetails = l4k_getLanguageDetails($langID);

    if ($books) {
        foreach ($books as $id) {

        	$bookButtons = array();
        	$linkedBooks = l4k_getLinkedBooks($langDetails['lang_id'], $id, get_field('native_story', $id), false);

        	if ($linkedBooks) {
        		foreach ($linkedBooks as $lb) {
        			$bookButtons[] = array(	'button_label' 		=> $langDetails['variant_label'][$lb['book_type']], 
        									'book_type' 		=> $lb['book_type'], 
        									'book_permalink' 	=> $lb['book_permalink']);
        		}
        	}

            $bookArr[] = [
                'book_id'    		=> $id,
                'date_published'    => get_the_date('YmdHis', $id),
                'image_url'			=> get_field('book_image_url', $id),
                'story_id'			=> get_field('native_story', $id),
                'english_title'		=> get_the_title(get_field('native_story', $id)),
                'native_title'		=> get_field('native_title', $id),
                'level'				=> get_field('levels_level', $id),
                'level_nicename'	=> l4k_getLevelNicename(get_field('levels_level', $id)),
                'tier'				=> get_field('levels_tier', $id),
                'views'				=> get_field('additional_details_views', $id),
                'has_quiz'			=> l4k_hasQuiz($id),
                'is_non_fiction'	=> get_field('filter_tags_non_fiction', $id),
                'book_buttons'		=> $bookButtons,
                'book_type'			=> get_field('book_type', $id),
                'book_permalink'    => get_permalink($id)
            ];
        }
    }

    return $bookArr;

}

/**
 * ----------------------------------------------------------------
 * Retrieve similar books based on reading level
 * ----------------------------------------------------------------
 */

function l4k_getSimilarBooksByLevel($langID, $bookID, $readingLevel, $bookType, $numBooks=4, $isReadingPack=false) {

    $metaQuery = ['relation' => 'AND'];
     
	$metaQuery = [
		['key' => 'language', 'value' => $langID],
	    ['key' => 'levels_level', 'value' => $readingLevel],
	    ['key' => 'book_type', 'value' => $bookType]
	];

     // Reading Pack mode - get books from the same reading pack first
	if ($isReadingPack) 
	{
	    $metaQuery[] = [
	        'key'     => 'levels_tier',
	        'value'   => [1, 2],
	        'compare' => 'IN',
	        'type'    => 'NUMERIC'
	    ];
	}
 
    $books = get_posts([
        'post_type'      => 'book',
        'post_status'    => 'publish',
        'posts_per_page' => $numBooks,
        'fields'         => 'ids',
        'orderby'        => 'rand',
        'post__not_in'   => array($bookID),
        'meta_query'     => $metaQuery
    ]);

    $bookArr = array();

    if ($books) {
        foreach ($books as $id) {

            $bookArr[] = [
                'date_published'    => get_the_date('Ymd', $id),
                'image_url'			=> get_field('book_image_url', $id),
                'book_type'			=> get_field('book_type', $id),
                'english_title'		=> get_the_title(get_field('native_story', $id)),
                'native_title'		=> get_field('native_title', $id),
                'level'				=> get_field('levels_level', $id),
                'level_nicename'	=> l4k_getLevelNicename(get_field('levels_level', $id)),
                'book_permalink'    => get_permalink($id)
            ];
        }
    }

    // if similar books are less than 4, get filler books
    if (count($bookArr) < $numBooks) {
    	$difference = $numBooks - count($bookArr);
    	$fillerBooksArr = l4k_getFillerBooks($langID, $bookID, $bookType, $difference);
    	$bookArr = array_merge($bookArr, $fillerBooksArr);
    }

    return $bookArr;

}

/**
 * ----------------------------------------------------------------
 * Retrieve filler books if similar books above is less than 4
 * ----------------------------------------------------------------
 */

function l4k_getFillerBooks($langID, $bookID, $bookType, $numBooks) {

    $metaQuery = array();

	$metaQuery = [
	    'relation' => 'AND',

	    ['key' => 'language', 'value' => $langID],
	    ['key' => 'book_type', 'value' => $bookType]
	];

    $fillerBooks = get_posts([
        'post_type'      => 'book',
        'post_status'    => 'publish',
        'posts_per_page' => $numBooks,
        'fields'         => 'ids',
        'orderby'        => 'rand',
        'post__not_in'   => array($bookID),
        'meta_query'     => $metaQuery
    ]);

    $fillerBooksArr = array();

    if ($fillerBooks) {
        foreach ($fillerBooks as $id) {

            $fillerBooksArr[] = [
                'date_published'    => get_the_date('Ymd', $id),
                'image_url'			=> get_field('book_image_url', $id),
                'book_type'			=> get_field('book_type', $id),
                'english_title'		=> get_the_title(get_field('native_story', $id)),
                'native_title'		=> get_field('native_title', $id),
                'level'				=> get_field('levels_level', $id),
                'level_nicename'	=> l4k_getLevelNicename(get_field('levels_level', $id)),
                'book_permalink'    => get_permalink($id)
            ];
            
        }
    }

    return $fillerBooksArr;

}

/**
 * ----------------------------------------------------------------
 * Get levels nicename
 * ----------------------------------------------------------------
 */

function l4k_getLevelNicename($level) {

	switch($level) {
		case "P" 	: $level_nicename = "Picture Cards"; 	break;
		case "1" 	: $level_nicename = "Level 1"; 			break; 
		case "2" 	: $level_nicename = "Level 2"; 			break; 
		case "3" 	: $level_nicename = "Level 3"; 			break;
		case "4+" 	: $level_nicename = "Level 4+"; 		break;
	}

	return $level_nicename;

}

/**
 * ----------------------------------------------------------------
 * Retrieve linked books for read it your way
 * ----------------------------------------------------------------
 */

function l4k_getLinkedBooks($langID, $bookID, $storyID, $exemptCurrent=true) {

    // check first if the query results has been cached
    $cache_key = 'l4k_books_' . $bookID . '_linked';
    $cached = get_transient($cache_key);
    if (!isset($_GET['purge-cache'])) { if ($cached !== false) { return $cached; } }

	$metaQuery = [
	    'relation' => 'AND',

	    ['key' => 'language', 'value' => $langID],
	    ['key' => 'native_story', 'value' => $storyID]
	];

	$exemptArr = ($exemptCurrent) ? array($bookID) : array();

    $books = get_posts([
        'post_type'      	=> 'book',
        'post_status'    	=> 'publish',
        'posts_per_page' 	=> -1,
        'fields'         	=> 'ids',
        'post__not_in'   	=> $exemptArr,
		'meta_key'       	=> 'book_type',
		'orderby'        	=> 'meta_value',
		'order'				=> 'DESC',
        'meta_query'     	=> $metaQuery
    ]);

    $bookArr = array();

    if ($books) {
        foreach ($books as $id) {

       	 	$bookArr[] = [
                'book_id'			=> $id,
                'image_url'			=> get_field('book_image_url', $id),
                'book_type'			=> get_field('book_type', $id),
                'book_permalink'    => get_permalink($id)
            ];

        }
    }

    set_transient($cache_key, $bookArr, 168 * HOUR_IN_SECONDS); // cache the results for 168 hours (7 days)

    return $bookArr;

}

/**
 * ----------------------------------------------------------------
 * Retrieve all activities
 * ----------------------------------------------------------------
 */

function l4k_getActivities($getComingSoon=false, $type='online') {

    $metaQuery = array();

    if ($getComingSoon) 
    {
		$metaQuery = [
		    ['key' => 'coming_soon', 'value' => 1, 'compare' => '=' ]
		];
    }
    else
    {
		$metaQuery = [
		    'relation' => 'AND',
		    [
		        'relation' => 'OR',
		        ['key' => 'coming_soon', 'value' => 0, 'compare' => '='],
		        ['key' => 'coming_soon', 'compare' => 'NOT EXISTS']
		    ],

		    ['key' => 'type', 'value' => $type]
		];
    }

    $activities = get_posts([
        'post_type'      => 'activity',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_key'       => 'order',
	    'orderby'        => 'meta_value_num',
	    'order'          => 'ASC',
        'meta_query'     => $metaQuery
    ]);

    $activityArr = array();

    if ($activities) {
        foreach ($activities as $id) {

            $activityArr[] = [
                'date_published'    => get_the_date('Ymd', $id),
                'title'				=> get_the_title($id),
                'type'				=> get_field('type', $id),
                'activity_image'	=> get_field('activity_image', $id),
                'iframe_source'		=> get_field('iframe_source', $id),
                'collections'		=> get_field('collections', $id),
                'permalink' 	  	=> get_permalink($id)
            ];
        }
    }

    return $activityArr;

}

/**
 * ----------------------------------------------------------------
 * Retrieve the book's related activities and quizzes
 * Separate into 2 arrays, one for activities and one for quizzes
 * ----------------------------------------------------------------
 */

function l4k_getActivitiesAndQuizzes($bookID) {

	$arr = array();

	if (have_rows('download_links', $bookID)) :
		while (have_rows('download_links', $bookID)) : the_row();

			if (str_contains(get_sub_field('pdf'), '<iframe')) 
			{ 
				$arr['quizzes'][] = array(	'activity' 	=> get_sub_field('activity'),
											'embed' 	=> get_sub_field('pdf'),
											'title' 	=> get_sub_field('title'),
											'url_path' 	=> get_sub_field('url_path'));
			}
			else
			{
				$arr['activities'][] = array(	'activity' 	=> get_sub_field('activity'),
												'pdf' 		=> get_sub_field('pdf'),
												'title' 	=> get_sub_field('title'),
												'url_path' 	=> get_sub_field('url_path'));
			}

		endwhile;
	endif;

	return $arr;

}

/**
 * ----------------------------------------------------------------
 * Retrieve items to be shown on the top of the member home page
 * Recently viewed
 * Similar Books
 * ----------------------------------------------------------------
 */

function l4k_getMemberHomeMeta() {

	$metaArr = array();

	if ($_SESSION['last_viewed_book']) 
	{
		// recently viewed language

		$languageID = get_field('language', $_SESSION['last_viewed_book']);
		$metaArr['last_viewed_language'] 	= array(
			'permalink' 	=> get_the_permalink($languageID),
			'img_url' 		=> get_field('lang_flag_image', $languageID),
			'label' 		=> get_field('lang_native_label', $languageID),
			'label_english' => get_the_title($languageID),
			'type' 			=> 'flag');

		// recently viewed book
		
		$bookID 	= $_SESSION['last_viewed_book'];
		$metaArr['last_viewed_book'] = array(
			'permalink' => get_the_permalink($bookID),
			'img_url' 	=> get_field('book_image_url', $bookID),
			'label' 	=> get_field('native_title', $bookID),
			'type' 		=> 'book');

		// video playlist

		$quickPlaylist = l4k_getPlaylistsByLanguage($languageID, get_field('levels_level', $_SESSION['last_viewed_book']));
		if ($quickPlaylist) 
		{
			$metaArr['playlist'] = array(
				'permalink' => $quickPlaylist[0]['playlist_permalink'],
				'img_url' 	=> $quickPlaylist[0]['book_image_url'],
				'label' 	=> $quickPlaylist[0]['display_title'],
				'type' 		=> 'book');
		}

		// similar books

		$numBooks = ($quickPlaylist) ? 3 : 4; // if no playlist, show 4 similar books
		$similarBooks = l4k_getSimilarBooksByLevel($languageID, $bookID, get_field('levels_level', $bookID), get_field('book_type', $bookID), $numBooks);
		if ($similarBooks) {
			foreach ($similarBooks as $book => $b) {
				$metaArr[] 	= array('permalink' => $b['book_permalink'],
									'img_url' 	=> $b['image_url'],
									'label' 	=> ($b['native_title']) ? $b['native_title'] : $b['english_title'],
									'type' 		=> 'book');
			}
		}
	}

	return $metaArr; 

}

/**
 * ----------------------------------------------------------------
 * Retrieve activity log
 * ----------------------------------------------------------------
 */

function l4k_getActivityLog() {

    global $wpdb;
    $table = $wpdb->prefix . 'web_activity';
    $query = "	SELECT * 
    			FROM $table 
    			ORDER BY id DESC 
    			LIMIT 100";

    $webActivity = $wpdb->get_results($query);
	$webActivityArr = array();

    foreach ($webActivity as $key => $value) {
		$webActivityArr[] = array(	'id' 			=> $value->id,
									'alert_code' 	=> $value->alert_code,
									'barcode' 		=> $value->barcode,
									'library_name' 	=> $value->library_name,
									'region_name' 	=> $value->region_name,
									'ip' 			=> $value->ip,
									'time' 			=> $value->time,
									'message' 		=> json_decode($value->data));
    }

    return $webActivityArr;

}

/**
 * ----------------------------------------------------------------
 * Retrieve activity log for a single barcode
 * ----------------------------------------------------------------
 */

function l4k_getLearningDashboardContent() {

    global $wpdb;
    $table = $wpdb->prefix . 'web_activity';
    $query = "	SELECT * 
    			FROM $table 
    			WHERE barcode = '".$_POST['barcode']."' 
    			AND time >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
    			ORDER BY id DESC";

    $webActivity = $wpdb->get_results($query);
	$webActivityArr = array();

	if ($webActivity) {
	    foreach ($webActivity as $key => $value) {
			$webActivityArr[] = array(	'id' 			=> $value->id,
										'alert_code' 	=> $value->alert_code,
										'barcode' 		=> $value->barcode,
										'library_name' 	=> $value->library_name,
										'region_name' 	=> $value->region_name,
										'ip' 			=> $value->ip,
										'time' 			=> $value->time,
										'message' 		=> json_decode($value->data, true));
	    }
	}

	$countBooks = $countQuizzes = $countActivities = $countFeathers = $countStreaks = 0;
	$countBooks_types = array();
	$countActivity_types = array();

	if ($webActivityArr) {
	    foreach ($webActivityArr as $key => $value) {

	    	// count books
	    	if ($value['alert_code'] == 1062) { 
	    		$countBooks++; 

	    		// count individual book types
    			switch($value['message']['Type']) {
					case "flipbook": 
						$countBooks_types['Flipbook']++;
						break;
					case "video_english": 
						$countBooks_types['English Video']++;
						break;
					case "video_monolingual": 
						$countBooks_types['Monolingual Video']++;
						break;
					case "video_bilingual": 
						$countBooks_types['Bilingual Video']++;
						break;
				}
	    	}

	    	// count quiz / activity
	    	if ($value['alert_code'] == 1060) { 
	    		if ($value['message']['Activity Type'] == 'Embedded') { $countQuizzes++; }

	    		if ($value['message']['Activity Type'] == 'Pdf') { 
	    			$countActivities++; 

	    			if (str_starts_with($value['message']['Activity Name'], 'Color-Colouring')) { $activityName = 'Color-Colouring'; } 
	    			else if (str_starts_with($value['message']['Activity Name'], 'Spot the Difference')) { $activityName = 'Spot the Difference'; } 
	    			else { $activityName = $value['message']['Activity Name']; }
	    			$countActivity_types[$activityName]++;
	    		}
			}

	    }
	}

	$dashboardContentArr = 
		array(	'countBooks' 			=> $countBooks,
				'countQuizzes' 			=> $countQuizzes,
				'countActivities' 		=> $countActivities,
				'countFeathers' 		=> $countFeathers,
				'countStreaks' 			=> $countStreaks,
				'countBooks_types' 		=> $countBooks_types,
				'countActivity_types' 	=> $countActivity_types );

	$resultsArr = array('status' => 1,
    					'dashboardContentArr' => $dashboardContentArr);

   	wp_send_json($resultsArr);

}

/**
 * ----------------------------------------------------------------
 * Retrieve summary count of all titles per language
 * ----------------------------------------------------------------
 */

function l4k_getBookCountPerLanguage() {

	// check first if the query results has been cached
	$cache_key = 'l4k_lang_count';
	$cached = get_transient($cache_key);
	if (!isset($_GET['purge-cache'])) { if ($cached !== false) { return l4k_splitIntoThree($cached); } }

	// get all languages first
	// no need to join this to the query anymore
	$languages = l4k_getLanguages($getComingSoon=false, $exclude=false); 

	// count all books per language
	// get the count only to optimize the query
	global $wpdb;

	// count only monolingual and bilingual books
	// count all books for cook-islands, niue, and tokelauan
	$results = $wpdb->get_results("
	    SELECT 
	        pm.meta_value AS language_id, -- language ID from post meta
	        COUNT(*) AS total -- total number of books in this language
	    FROM {$wpdb->posts} p
	    INNER JOIN {$wpdb->postmeta} pm 
	        ON pm.post_id = p.ID 
	        AND pm.meta_key = 'language' -- only consider 'language' meta
	    LEFT JOIN {$wpdb->postmeta} bt 
	        ON bt.post_id = p.ID 
	        AND bt.meta_key = 'book_type' -- only consider 'book_type' meta
	    WHERE 
	        p.post_type = 'book'
	        AND p.post_status = 'publish'
	        AND (
	            pm.meta_value IN ('92', '135', '163') -- we count ALL books for cook-islands, niue, and tokelauan
	            OR bt.meta_value IN ('video_monolingual', 'video_bilingual') -- only these book types for all other languages
	        )
	    GROUP BY pm.meta_value
	");

	if ($results) {
		foreach ($results as $row) {
			$resultsArr[$row->language_id] = $row->total;
		}		
	}

	if ($languages) {
		foreach ($languages as $key => $lang) {
			$languages[$key]['book_count'] = ($resultsArr[$lang['lang_id']]) ? $resultsArr[$lang['lang_id']] : '0';
		}
	}

	set_transient($cache_key, $languages, 168 * HOUR_IN_SECONDS); // cache the results for 168 hours (7 days)

	return l4k_splitIntoThree($languages);

}

/**
 * ----------------------------------------------------------------
 * Retrieve all playlists for a particular language
 * ----------------------------------------------------------------
 */

function l4k_getPlaylistsByLanguage($langID, $byLevel='') {

	$metaQuery = ['relation' => 'AND'];

	if ($byLevel) // get one playlist which is the same level of the recently viewed book
	{
	    $metaQuery[] = ['key' => 'level', 'value' => $byLevel, 'compare' => '='];
	    $metaQuery[] = ['key' => 'language', 'value' => $langID, 'compare' => '='];
	}
	else
	{
		$metaQuery[] = ['key' => 'language', 'value' => $langID, 'compare' => '='];
	}

    $playlists = get_posts([
        'post_type'      	=> 'playlist',
        'post_status'    	=> 'publish',
        'numberposts' 		=> -1,
        'fields'			=> 'ids',
		'meta_query'     	=> $metaQuery,
        'orderby'       	=> 'date',
        'order'          	=> 'DESC',
    ]);

    $langDetails = l4k_getLanguageDetails($langID);

    if ($playlists) {
    	foreach ($playlists as $id) {

			$bookDetails = l4k_getFirstBookOfPlaylist(
				get_field('language', $id),
				get_field('level', $id),
				get_field('level', $id) === 'P' ? 'video_bilingual' : 'video_monolingual'
			);

            $playlistArr[] = [
                'playlist_id'			=> $id,
                'language'				=> get_field('language', $id),
                'level'					=> get_field('level', $id),
                'book_image_url'		=> $bookDetails['image_url'],
                'book_type'				=> $bookDetails['book_type'],
                'playlist_permalink'	=> get_permalink($id),
                'button_label'			=> $langDetails['variant_label'][$bookDetails['book_type']],
                'display_title'			=> get_field('display_title', $id),
            ];

    	}
    }

    // re-order the $playlistArr
    if ($playlistArr) 
    {
		$levelOrder = [ 'P' => 0, '1' => 1, '2' => 2, '3' => 3, '4+'=> 4 ];
		usort($playlistArr, function ($a, $b) use ($levelOrder) {
		    return ($levelOrder[$a['level']] ?? 999) <=> ($levelOrder[$b['level']] ?? 999);
		});
	}

    return $playlistArr;

}

/**
 * ----------------------------------------------------------------
 * Get first book (randomized) of a particular playlist
 * ----------------------------------------------------------------
 */

function l4k_getFirstBookOfPlaylist($langID, $level, $bookType) {

	$bookID = get_posts([
	    'post_type'      	=> 'book',
	    'post_status'    	=> 'publish',
	    'numberposts'    	=> 1,
	    'fields'         	=> 'ids',
	    'meta_query'     	=> [
	        'relation' 		=> 'AND',
	        ['key' => 'language', 'value' => $langID, 'compare' => '='],
	        ['key' => 'levels_level', 'value' => $level, 'compare' => '='],
	        ['key' => 'book_type', 'value' => $bookType, 'compare' => '='],
	    ],
	    'orderby'        => 'rand',
	]);

	$bookDetails['image_url'] 	= get_field('book_image_url', $bookID[0]);
	$bookDetails['book_type'] 	= get_field('book_type', $bookID[0]);	

    return $bookDetails;

}

/**
 * ----------------------------------------------------------------
 * Get reading packs for the library
 * ----------------------------------------------------------------
 */

function l4k_getReadingPacks() {

	$readingPackArr = [];
	
	if (have_rows('language_packs', $_SESSION['library_id'])) :
	    while (have_rows('language_packs', $_SESSION['library_id'])) : the_row();
	        $readingPackArr[] = l4k_getLanguageDetails(get_sub_field('language'));
	    endwhile;
	endif;

	return $readingPackArr;

}

/**
 * ----------------------------------------------------------------
 * Get videos of a particular playlist
 * ----------------------------------------------------------------
 */

function l4k_getVideosByPlaylistID($playlistID) {

	$metaQuery = array();
	$books = array();
	$bookArr = array();

	if (get_field('level', $playlistID) == 'P') {
		$metaQuery = [
	        'relation' => 'AND',
		        ['key' => 'levels_level', 'value' => get_field('level', $playlistID), 'compare' => '='],
		        ['key' => 'language', 'value' => get_field('language', $playlistID), 'compare' => '='],
		        ['key' => 'book_type', 'value' => ['video_monolingual', 'video_bilingual'], 'compare' => 'IN'],
		    ];
	} else {
		$metaQuery = [
	        'relation' => 'AND',
		        ['key' => 'levels_level', 'value' => get_field('level', $playlistID), 'compare' => '='],
		        ['key' => 'language', 'value' => get_field('language', $playlistID), 'compare' => '='],
		        ['key' => 'book_type', 'value' => ['video_monolingual'], 'compare' => 'IN'],
		    ];
	}

	$books = get_posts([
	    'post_type'      => 'book', 
	    'posts_per_page' => get_field('playlist_size', $playlistID), 
	    'post_status'    => 'publish',
	    'orderby'        => 'rand',
	    'fields'         => 'ids',
	    'meta_query'     => $metaQuery,
	]);

	if ($books) {
      	foreach ($books as $id) {

		    $vimeoURL = get_field('video_source', $id); 
		    $vimeoData = l4k_parseVimeoUrl($vimeoURL); 

		    if ($vimeoData) {
		        $bookArr[] = [
		            'id'            => $id,
		            'vimeo_id'      => $vimeoData[0],    
		            'vimeo_hash'    => $vimeoData[1] ?? '',  
		            'title'         => get_the_title($id),
		            'native_title'  => get_field('native_title', $id), 
		            'english_title'	=> get_the_title(get_field('native_story', $id)),
		            'description'   => nl2br(get_field('details_description', $id)), 
		            'language'  	=> get_field('language', $id), 
		            'book_type'  	=> get_field('book_type', $id), 
		            'views'         => get_field('additional_details_views', $id), 
		            'date'          => get_the_date('M j, Y', $id),
		            'author'        => get_field('details_author', $id), 
		            'image'         => get_field('book_image_url', $id) 
		        ];
			}

    	}
	}

	return $bookArr;

}

function l4k_getVideoIndexMap($playlistVideos) {

	$videoIndexMap = array();
    $counter = 0;

	if ($playlistVideos) {
      	foreach ($playlistVideos as $video) {
      		$videoID = $video['id'];
			$videoIndexMap[$videoID] = $counter;
			$counter++;
      	}
	}

	return $videoIndexMap;

}
?>