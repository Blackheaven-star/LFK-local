<?php 
/**
 * Send Getting Started email immediately after successful insert
 */
function l4k_send_getting_started_email($email, $name, $barcode) {

    if (empty($email) || empty($barcode)) {
        error_log('Getting Started email skipped: missing email or barcode');
        return;
    }

    $subject = 'Welcome to your LOTE4Kids Trial';

    $headers = [
        'From: LOTE4Kids <sales@storytimepods.com>',
        'Content-Type: text/html; charset=UTF-8',
    ];

    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; color:#333; line-height:1.6;'>

        <p>Dear {$name},</p>

        <p>Thank you for registering to trial <strong>LOTE4Kids</strong>!</p>

        <p>
            LOTE4Kids is the leading digital library of children’s audio-picture books,
            designed for libraries and schools to support English and bilingual language
            learning through storytelling.
        </p>

        <p>
            It offers over <strong>8,000 titles</strong> in more than <strong>100 world languages</strong>,
            all narrated by native speakers (not AI) to ensure an authentic experience.
            Each book features English translations, leveled texts, multiple reading formats,
            and built-in comprehension quizzes.
        </p>

        <p>
            With more than <strong>250 new books added each month</strong> and regular feature
            updates to our user-friendly web platform and mobile apps,
            <strong>LOTE4Kids</strong> is the fastest-growing children’s language learning
            database available today.
        </p>

        <p>
            You can learn more in the brochure
            <a href='https://storytimepods.com/brochure/' target='_blank'>here</a>.
        </p>

        <p><strong>Here are your trial details, which will remain active for 14 days:</strong></p>

        <ul>
            <li><strong>Website:</strong>
                <a href='https://www.lote4kids.com/au-demo'>www.lote4kids.com/au-demo</a>
            </li>
            <li><strong>Mobile App:</strong> iOS and Android (select <em>Demo Library</em>)</li>
            <li><strong>Access code:</strong> {$barcode}</li>
        </ul>

        <p>
            If you would like more information or pricing, please feel free to email me directly.
        </p>

        <p>
            I hope you enjoy your trial; happy exploring!
        </p>

        <p>
            Kind regards,<br>
            <strong>Sunny</strong>
        </p>

    </body>
    </html>
    ";

    if (wp_mail($email, $subject, $message, $headers)) {
        l4k_mark_getting_started_sent($barcode);
    } else {
        error_log('Getting Started email failed for barcode: ' . $barcode);
    }
}

/**
 * Mark email as sent (optional safety flag)
 */
function l4k_mark_getting_started_sent($barcode) {
    global $wpdb;

    $wpdb->update(
        $wpdb->prefix . 'alternate_barcode',
        ['is_getting_started' => 1],
        ['barcode' => $barcode],
        ['%d'],
        ['%s']
    );
}
?>
<?php
/////////////////INSERT TO CRON PART///////////////////////////////


function schedule_trial_emails($email, $trial) {


global $wpdb;
    $table_name = $wpdb->prefix . 'alternate_barcode';

$db_entry = $wpdb->get_row($wpdb->prepare(
        "SELECT barcode, name, library FROM {$table_name} WHERE email = %s ORDER BY time DESC LIMIT 1",
        $email
    ), ARRAY_A);


    $fallback_barcode = 'TRIAL' . time(); 
    $fallback_name    = 'Trial User';
    $fallback_library = 'LOTE4Kids Library';

  
    $barcode    = !empty($db_entry['barcode']) ? $db_entry['barcode'] : ($trial['trial_details']['barcode'] ?? $fallback_barcode);
    $first_name = !empty($db_entry['name'])    ? $db_entry['name']    : ($trial['first_name'] ?? $fallback_name);
    $library    = !empty($db_entry['library']) ? $db_entry['library'] : ($trial['trial_details']['website'] ?? $fallback_library);

	$trial['first_name'] = $first_name;
    $trial['barcode']    = $barcode;
    if(!isset($trial['trial_details'])) $trial['trial_details'] = [];
    $trial['trial_details']['barcode'] = $barcode;
    $trial['trial_details']['website'] = $library;

// ---- CRON HANDLING ----
$test_mode = defined('LOTE4KIDS_TEST_CRON') && LOTE4KIDS_TEST_CRON;
$interim_delay = 1 * HOUR_IN_SECONDS;  // 1 hour
$summary_delay = 2 * HOUR_IN_SECONDS;  // 2 hours

// Remove any previously scheduled events for this email (ignore old $trial data)
foreach (['interim_summary_report_event', 'email_after_15_days_event'] as $hook) {
    $crons = _get_cron_array();
    if (is_array($crons)) {
        foreach ($crons as $timestamp => $cronhooks) {
            if (isset($cronhooks[$hook])) {
                foreach ($cronhooks[$hook] as $key => $args) {
                    if (!empty($args['args'][0]) && $args['args'][0] === $email) {
                        wp_unschedule_event($timestamp, $hook, $args['args']);
                    }
                }
            }
        }
    }
}

// Now schedule new ones

$whitelisted_emails = [
    'cpgemc@gmail.com',
    'pete@storytimepods.com.au',
    'jeri.gonzales.ilao@gmail.com',
    'jeribikomethod@gmail.com',
    
    
];


if (in_array($email, $whitelisted_emails)) {

wp_schedule_single_event(time() + $interim_delay, 'interim_summary_report_event', [$email, $trial]);
wp_schedule_single_event(time() + $summary_delay, 'email_after_15_days_event', [$email, $trial]);

}  else {

      $headers = [
        "From: LOTE4Kids <sales@storytimepods.com>",
        "Content-Type: text/html; charset=UTF-8",
        'Bcc: pete@storytimepods.com.au, sunny@storytimepods.com.au, storytimepods@pipedrivemail.com',
    ];

//wp_schedule_single_event(time() + $interim_delay, 'interim_summary_report_event_orig', [$email, $trial]);

//wp_schedule_single_event(time() + $summary_delay, 'email_after_15_days_event_orig', [$email, $trial]);  

$second_email_schedule = isset($trial['second_email_schedule']) ? $trial['second_email_schedule'] : 7;

wp_schedule_single_event(time() + $interim_delay, 'interim_summary_report_event_orig', [
        $email,
        $trial,
        $final_barcode,  // Uses DB barcode or static fallback
        $final_headers,  // Uses headers or static fallback
        $final_schedule  // Uses form value or static 7
    ]
);

wp_schedule_single_event(time() + $summary_delay, 'email_after_15_days_event_orig', [
        $email,
        $trial,
        $final_barcode,  // Uses DB barcode or static fallback
        $final_headers,  // Uses headers or static fallback
        $final_schedule  // Uses form value or static 7
    ]
);

}




}

/**
 * Named handler for interim (7-day) — accepts one argument ($email)
 */


function send_interim_summary_report($email, $trial = []) {
    global $wpdb;

    if (is_array($email)) $email = reset($email);

   $seven_days_ago_timestamp = date('Y-m-d H:i:s', strtotime('-7 days'));
    $table_name = $wpdb->prefix . 'alternate_barcode';
   $sql_query = $wpdb->prepare(
    "SELECT DISTINCT barcode, time, is_interim 
     FROM {$table_name} 
     WHERE email = %s 
       AND time >= %s
       AND is_interim = 0", 
    $email,
    $seven_days_ago_timestamp
);
    $barcodes = $wpdb->get_col($sql_query); 

    if (empty($barcodes)) return; 

 
    $all_trials = get_option('trial_signups_by_email', []);
    $trials = $all_trials[$email] ?? [];
    
   
    if (empty($trials)) {
        $latest_trial = [];
    } else {
        $latest_trial = !empty($trials['trials']) && is_array($trials['trials']) ? end($trials['trials']) : [];
    }

    $trial = array_merge($latest_trial, $trial);

    // Include new barcode if present
    if (!empty($trial['trial_details']['barcode'])) {
        $barcode = $trial['trial_details']['barcode'];
        if (!in_array($barcode, $barcodes)) $barcodes[] = $barcode;
    } else {
        $barcode = $trial['barcode'] ?? '';
    }

// Fetch engagement stats (7 days) for ALL barcodes
$seven_days_later = date('Y-m-d', strtotime('-7 days'));
$seven_days_results = [
    'langauage_viewed' => [],
    'number_of_views_read' => 0,
    'number_of_quizzes_started' => 0,
    'number_of_activities' => 0,
    'total_engagement' => 0,
];

foreach ($barcodes as $bcode) {
    $result = get_activities_user_engagement($bcode, $seven_days_later);
    if ($result && is_array($result)) {
        // Merge language list (unique)
        if (!empty($result['langauage_viewed'])) {
            $langs = array_map('trim', explode(', ', $result['langauage_viewed']));
            $seven_days_results['langauage_viewed'] = array_unique(array_merge($seven_days_results['langauage_viewed'], $langs));
        }

        // Add numeric values
        $seven_days_results['number_of_views_read']      += (int)($result['number_of_views_read'] ?? 0);
        $seven_days_results['number_of_quizzes_started'] += (int)($result['number_of_quizzes_started'] ?? 0);
        $seven_days_results['number_of_activities']      += (int)($result['number_of_activities'] ?? 0);
        $seven_days_results['total_engagement']          += (int)($result['total_engagement'] ?? 0);
    }
}

// Turn languages array back into comma-separated string
$seven_days_results['langauage_viewed'] = implode(', ', $seven_days_results['langauage_viewed']);




    if ($seven_days_results === false) {
        $notification_message = 'Failed to fetch user engagement activities for interim report. Barcode: ' . $barcode;
        error_log($notification_message);
        wp_mail('cpgemc@gmail.com', 'LOTE4Kids Error Message', $notification_message);
        return;
    }

    $barcodes_str = implode(', ', $barcodes);
    $first_name           = $trial['first_name'] ?? '';
    $kindRegards          = $trial['kindRegards'] ?? '';
    $position             = $trial['position'] ?? '';
    $logo                 = $trial['logo'] ?? '';
    $phone                = $trial['phone'] ?? '';
    $email_signature      = $trial['email'] ?? $email;
    $website              = $trial['trial_details']['website'] ?? ($trial['website'] ?? '');
    $mobileApp            = $trial['mobileApp'] ?? '';
    $clickHereLink        = $trial['clickHereLink'] ?? '';
    $websiteLink          = $trial['websiteLink'] ?? '';
  

    $language_viewed_7days = $seven_days_results['langauage_viewed'] ?? '0';
    if (strpos($language_viewed_7days, 'None, ') === 0) { $language_viewed_7days = substr($language_viewed_7days, strlen('None, ')); }


    $subject = "Your LOTE4Kids trial; Interim report";

    ob_start();
    ?>
    <?php include('email-template/interim-email-7days.php'); ?>
    <?php
    $message = ob_get_clean();

    $headers = [
        "From: LOTE4Kids <sales@storytimepods.com>",
        "Content-Type: text/html; charset=UTF-8",
        'Bcc: pete@storytimepods.com.au, sunny@storytimepods.com.au, storytimepods@pipedrivemail.com',
    ];

    wp_mail($email, $subject, $message, $headers);

        if (!empty($barcodes) && is_array($barcodes)) {

        foreach ($barcodes as $bcode) {
             $wpdb->update(
                $wpdb->prefix . 'alternate_barcode',
                ['is_interim' => 1],
                ['barcode' => $bcode],
                ['%d'],
                ['%s']
            );

            error_log("Update barcode {$bcode}, result: {$updated}");
        }
    }

}



/**
 * Named handler for final (15-day) — accepts one argument ($email)
 */

function send_email_after_15_days($email, $trial = []) {

    global $wpdb;
  

    if (is_array($email)) $email = reset($email);

    $table_name = $wpdb->prefix . 'alternate_barcode';

   $fifteen_days_ago_timestamp = date('Y-m-d H:i:s', strtotime('-15 days'));

    $sql_query = $wpdb->prepare(
    "SELECT DISTINCT barcode, time
     FROM {$table_name}
     WHERE email = %s
       AND time >= %s
       AND is_summary = 0", 
    $email,
    $fifteen_days_ago_timestamp
);
    $barcodes = $wpdb->get_col($sql_query); 

    if (empty($barcodes)) return; 

   
    $all_trials = get_option('trial_signups_by_email', []);
    $trials = $all_trials[$email] ?? [];

    
    if (empty($trials)) {
        $latest_trial = [];
    } else {
        
        $latest_trial = !empty($trials['trials']) && is_array($trials['trials']) ? end($trials['trials']) : [];
    }

    $trial = array_merge($latest_trial, $trial);
    

    if (!empty($trial['trial_details']['barcode'])) {
        $barcode = $trial['trial_details']['barcode'];
        if (!in_array($barcode, $barcodes)) $barcodes[] = $barcode;
    } else {
        $barcode = $trial['barcode'] ?? '';
    }


$fifteen_days_later = date('Y-m-d', strtotime('-15 days'));
//$fifteen_days_later = date('Y-m-d', strtotime($trial['time'] ?? '-15 days'));
$fifteen_days_results = [
    'langauage_viewed' => [],
    'number_of_views_read' => 0,
    'number_of_quizzes_started' => 0,
    'number_of_activities' => 0,
    'total_engagement' => 0,
];



foreach ($barcodes as $bcode) {
    $result = get_activities_user_engagement($bcode, $fifteen_days_later);
    if ($result && is_array($result)) {
        // Merge language list (unique)
        if (!empty($result['langauage_viewed'])) {
            $langs = array_map('trim', explode(', ', $result['langauage_viewed']));
            $fifteen_days_results['langauage_viewed'] = array_unique(array_merge($fifteen_days_results['langauage_viewed'], $langs));
        }

       
        $fifteen_days_results['number_of_views_read']      += (int)($result['number_of_views_read'] ?? 0);
        $fifteen_days_results['number_of_quizzes_started'] += (int)($result['number_of_quizzes_started'] ?? 0);
        $fifteen_days_results['number_of_activities']      += (int)($result['number_of_activities'] ?? 0);
        $fifteen_days_results['total_engagement']          += (int)($result['total_engagement'] ?? 0);
    }
}

// Turn languages array back into comma-separated string
$fifteen_days_results['langauage_viewed'] = implode(', ', $fifteen_days_results['langauage_viewed']);


    
    if ($fifteen_days_results === false) {
        $notification_message = 'Failed to fetch user engagement activities for summary report. Barcode: ' . $barcode;
        error_log($notification_message);
        wp_mail('cpgemc@gmail.com', 'LOTE4Kids Error Message', $notification_message);
        return;
    }

    $barcodes_str = implode(', ', $barcodes);
    $first_name           = $trial['first_name'] ?? '';
    $language_viewed      = $trial['language_viewed'] ?? 'N/A';
    $number_of_views_read = $trial['number_of_views_read'] ?? 'N/A';
    $number_of_quizzes    = $trial['number_of_quizzes_started'] ?? 'N/A';
    $number_of_activities = $trial['number_of_activities'] ?? 'N/A';
    //$total_engagement     = $trial['total_engagement'] ?? 'N/A';
    $total_engagement = isset($trial['total_engagement']) ? (int) $trial['total_engagement'] : 0;
    $trialExtensionLink   = $trial['trialExtensionLink'] ?? '';
    $kindRegards          = $trial['kindRegards'] ?? '';
    $position             = $trial['position'] ?? '';
    $logo                 = $trial['logo'] ?? '';
    $phone                = $trial['phone'] ?? '';
    $email_signature      = $trial['email'] ?? $email;
    $clickHereLink        = $trial['clickHereLink'] ?? '';
    $websiteLink          = $trial['websiteLink'] ?? '';
    
    $language_viewed_15days = $fifteen_days_results['langauage_viewed'] ?? '0';
    if (strpos($language_viewed_15days, 'None, ') === 0) { $language_viewed_15days = substr($language_viewed_15days, strlen('None, ')); }
    $ttlsbj = (int) ($fifteen_days_results['total_engagement'] ?? 0);
    
     if ($ttlsbj < 5) { $subject = "Your LOTE4Kids trial; Summary";}
    else { $subject = "Your LOTE4Kids trial; Summary and next steps!"; }

    ob_start();
    ?>
    
    <?php
    $ttleng = (int) ($fifteen_days_results['total_engagement'] ?? 0);
    if ($ttleng < 5) { include('email-template/summary-email-15days.php');}
    else { include('email-template/summary-next-step-15days.php'); }





    ?>

          
    <?php

    $message = ob_get_clean();

    $headers = [
        "From: LOTE4Kids <sales@storytimepods.com>",
        "Content-Type: text/html; charset=UTF-8",
        'Bcc: pete@storytimepods.com.au, sunny@storytimepods.com.au, storytimepods@pipedrivemail.com',
       
    ];


/*$debug_info = "<br><br><strong>DEBUG INFO</strong><br>";
$debug_info .= "Email: " . esc_html($email) . "<br>";
$debug_info .= "Trials data: <pre>" . print_r($trials, true) . "</pre><br>";
$debug_info .= "Latest trial: <pre>" . print_r($latest_trial, true) . "</pre><br>";
$debug_info .= "Runtime trial: <pre>" . print_r($trial, true) . "</pre><br>";
$debug_info .= "Barcodes: " . esc_html(implode(', ', $trials['barcodes'] ?? [])) . "<br>";


$message .= $debug_info;*/



    wp_mail($email, $subject, $message, $headers);


// update summary per barcode
//$mail_sent = wp_mail($email, $subject, $message, $headers);

    if (!empty($barcodes) && is_array($barcodes)) {

        foreach ($barcodes as $bcode) {
             $wpdb->update(
                $wpdb->prefix . 'alternate_barcode',
                ['is_summary' => 1],
                ['barcode' => $bcode],
                ['%d'],
                ['%s']
            );

            error_log("Update barcode {$bcode}, result: {$updated}");
        }
    }





}


/////////////////INSERT TO CRON PART///////////////////////////////
