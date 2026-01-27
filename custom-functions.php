<?php
ini_set('session.gc_maxlifetime', 604800);
ini_set('session.cookie_lifetime', 604800);
session_start();
function library_login_func()
{
    global $wpdb;
    /*Redirect back to the library page if already loged in*/
    if ((isset($_SESSION['library_region_name']) || isset($_SESSION['library_code_name']) ||  isset($_SESSION['library_login_page_id'])) && !is_user_logged_in()) {
        $libUrl = get_permalink($_SESSION['library_page_id']);
        wp_redirect($libUrl);
        die;
    }

    if (isset($_POST['library_login_submit']) && $_POST['library_login_submit'] && isset($_POST['barCode']) && $_POST['barCode']) {

        // if ( ! isset( $_SESSION['library_login_nonce'] ) || ! wp_verify_nonce( $_SESSION['library_login_nonce'], 'library_login_nonce' )) {

        //     $args = array(
        //         'Barcode' => $_POST['barCode'],
        //         'Region' => $_POST['library_region'],
        //         'LibraryName' => $_POST['library_name'],
        //         'Email' => '',
        //         'FullName' => '',
        //         'PageURL' => get_the_permalink(),
        //         'PageTitle' => get_the_title(),
        //         'ErrorMessage' => 'Sorry, your nonce did not verify.',
        //         'EventType' => 'login',
        //         'ClientIP' => getIPAddress(),
        //         'UserAgent' => $_SERVER['HTTP_USER_AGENT'],
        //         'EventType' => 'login',
        //         'Object' => 'library',
        //     );

        //     $alert_id = 1070;

        //     lote_activity_log($args, $alert_id);

        //     $content2 = "<div class='validation-heading' style='padding-bottom: 0 !important; font-size: 25px; font-weight: 700;'>Form Submission Failed!</div>";

        //     $content2 .= '<div class="library-names-email"><span style="position: relative; top: -1px; color: #000!important; margin-right: 8px; font-size: 12px;">●</span><span style="font-size: 14px;">Error Message: Required Fields ('.$args['ErrorMessage'].')</span></div>';
        //     $content2 .= '<div class="library-names-email"><span style="position: relative; top: -1px; color: #000!important; margin-right: 8px; font-size: 12px;">●</span><span style="font-size: 14px;">Page Title: '.$args['PageTitle'].'</span></div>';
        //     $content2 .= '<div class="library-names-email"><span style="position: relative; top: -1px; color: #000!important; margin-right: 8px; font-size: 12px;">●</span><span style="font-size: 14px;">Page URL: '.$args['PageURL'].'</span></div>';
        //     $content2 .= '<div class="library-names-email"><span style="position: relative; top: -1px; color: #000!important; margin-right: 8px; font-size: 12px;">●</span><span style="font-size: 14px;">Email: '.$args['email'].'</span></div>';
        //     $content2 .= '<div class="library-names-email"><span style="position: relative; top: -1px; color: #000!important; margin-right: 8px; font-size: 12px;">●</span><span style="font-size: 14px;">Library Name: '.$args['LibraryName'].'</span></div>';
        //     $content2 .= '<div class="library-names-email"><span style="position: relative; top: -1px; color: #000!important; margin-right: 8px; font-size: 12px;">●</span><span style="font-size: 14px;">Region: '.$args['Region'].'</span></div>';
        //     $content2 .= '<div class="library-names-email"><span style="position: relative; top: -1px; color: #000!important; margin-right: 8px; font-size: 12px;">●</span><span style="font-size: 14px;">Barcode: '.$args['Barcode'].'</span></div>';



        //     $to = 'pete@storytimepods.com.au';
        //     $subject = 'Form Submission';
        //     $body = $content2;
        //     $headers = array('Content-Type: text/html; charset=UTF-8');

        //     wp_mail( $to, $subject, $body, $headers );


        //     return  'Sorry, your nonce did not verify.';    
        // }  

        $termination_date = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT termination_date FROM {$wpdb->prefix}alternate_barcode WHERE barcode LIKE '%s'",
                '%' . $_POST['barCode'] . '%'
            )
        );
        $date_now = date('Y-m-t 23:59:59');

        if (!empty($termination_date)) {
            if ($termination_date != '0000-00-00 00:00:00' && $date_now > $termination_date) {
                return  'Sorry, your barcode has expired. Need help? Get in touch <a style="text-decoration:underline;color: #44443d;" href="/contact-us">here</a>.';
            }
        }

        if (!isset($_POST['library_login_nonce_name']) || !wp_verify_nonce($_POST['library_login_nonce_name'], 'library_login_nonce')) {
            return  'Sorry, your nonce did not verify.';
        }

        if (isset($_POST['rememberme']) && $_POST['rememberme']) {
            $_POST['barCode'] = $_POST['barCode'] . '$&';
        }


        $_SESSION['library_region_name'] = $_POST['library_region'];
        $_SESSION['library_code_name'] = $_POST['library_name'];
        $_SESSION['library_page_id'] = $_POST['library_page'];
        $_SESSION['library_login_page_id'] = $_POST['library_login_id'];
        $_SESSION['barCode'] = $_POST['barCode'];

        $libUrl = get_permalink($_POST['library_page']);
        $libUrl = $libUrl;
        // add_wp_activity_log();
        unset($_SESSION['rewards']);
        add_web_activity(900);
        if (isset($_SESSION['previous_page_url'])) {
            wp_redirect($_SESSION['previous_page_url']);
        } else {
            wp_redirect($libUrl);
        }
        die;
    }

    $html = '<form method="post" class="custom_library_login_form" id="custom_library_login">';
    $html .= '<input type="text" class="library_login_input" name="barCode"><label id="barCode-custom-error" class="error" for="barCode" style=""></label>
<p class="forgetmenot"><input name="rememberme" type="checkbox" id="rememberme" value="forever" style="display: inline;width: 15px;"> <label style="display: inline;vertical-align: bottom;" for="rememberme">Remember Me</label></p>';

    $url = get_the_permalink();
    $library = basename($url);
    $library = explode('-', $library, 2);
    $library_name = trim($library[1]);
    $library_region = ($library[0]);

    $library_group_name = get_field('library_group_name', get_the_id(), true);
    $library_group_region = get_field('library_group_region', get_the_id(), true);
    $library_name = $library_group_name ? $library_group_name : $library_name;
    $library_region = ($library_group_region && strpos($library_group_region, 'Select') === false) ? $library_group_region : $library_region;

    $html .= wp_nonce_field("library_login_nonce", "library_login_nonce_name");
    $html .= '
<input type="hidden" value="' . get_the_id() . '" name="library_login_id">
<input type="hidden" value="' . $library_name . '" name="library_name">
<input type="hidden" value="' . $library_region . '" name="library_region">
<input type="hidden" value="' . get_field('library_page', get_the_id(), true) . '" name="library_page">

<input type="submit" value="Submit" class="library_login_submit" name="library_login_submit">';

    $bar_code_criteria = get_field('bar_code_criteria', get_the_id(), true);

    $html .= '</form>
<script>    
jQuery( "#custom_library_login" ).on( "submit", function(e) {
    jQuery("#barCode-custom-error").hide().text("");
    var enteredBarCode = jQuery( ".library_login_input" ).val();
    var validationsCriteria = ' . json_encode($bar_code_criteria) . ';  

    if(enteredBarCode == ""){
        jQuery("#barCode-custom-error").show().text("Invalid Library Barcode or Card Number. Please try again.");
        e.preventDefault();  
    } 
    else if(checkBarcodeValidations(enteredBarCode,validationsCriteria) == true){

    }
    else if(checkBarcodeValidations(enteredBarCode,validationsCriteria) == "error1") {
        e.preventDefault(); 
         
        var fd = new FormData();

        fd.append("action", "get_login_faied");  
        fd.append("page_id", jQuery(".page-id").text()); 
        fd.append("error_message", "Library Login Failed"); 
        fd.append("page_url", jQuery(".page-url").text()); 
        fd.append("bar_code", jQuery(".library_login_input").val()); 
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                console.log(response);

            }
        });
    }
    else if(checkBarcodeValidations(enteredBarCode,validationsCriteria) == "error2") {
        e.preventDefault(); 

        var fd = new FormData();

        fd.append("action", "get_login_faied");  
        fd.append("page_id", jQuery(".page-id").text()); 
        fd.append("error_message", "Barcode Expired"); 
        fd.append("page_url", jQuery(".page-url").text()); 
        fd.append("bar_code", jQuery(".library_login_input").val()); 
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                console.log(response);

            }
        }); 
    }
    else {
        jQuery("#barCode-custom-error").show().text("Invalid Library Barcode or Card Number. Please try again.");
        e.preventDefault();  

        var fd = new FormData();

        fd.append("action", "get_login_faied");  
        fd.append("page_id", jQuery(".page-id").text()); 
        fd.append("error_message", "Library Login Failed"); 
        fd.append("page_url", jQuery(".page-url").text()); 
        fd.append("bar_code", jQuery(".library_login_input").val()); 
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: "/wp-admin/admin-ajax.php",
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                console.log(response);

            }
        });
    }  
}); 

function startsWith(str, word) {
    return str.lastIndexOf(word, 0) === 0;
}

function checkBarcodeValidations(enteredBarCode,validationsCriteria){
    var isBarCodeValid = false;  
    if(enteredBarCode == "" || validationsCriteria == "")
    return isBarCodeValid;

    jQuery.each( validationsCriteria, function( key, barCodeData ) {

        var PreFix = barCodeData.barcode_prefix;
        var barCodeLength = barCodeData.barcode_length;
        var IsCaseSensitive = barCodeData.case_sensitive;
        var barcodeValue = enteredBarCode;
        var startDate = barCodeData.start_date;
        startDate = new Date(startDate);
        var endDate = barCodeData.end_date;
        endDate = new Date(endDate);
        var CurrentDate = new Date();

        if(IsCaseSensitive != "yes") {
            barcodeValue = enteredBarCode.toLowerCase();
            PreFix = PreFix.toLowerCase();
        }
        console.log(CurrentDate.getTime());
        if(startsWith(barcodeValue, PreFix) != false && /\d/.test(enteredBarCode) != false && (barCodeLength == enteredBarCode.length || (parseInt(barCodeLength)+2 == enteredBarCode.length && enteredBarCode.indexOf("$&") != -1))){
            if(CurrentDate.getTime() < startDate.getTime() && barCodeData.start_date){
                console.log("test 1");
                jQuery("#barCode-custom-error").show().text("Barcode will be valid on "+barCodeData.start_date);
                isBarCodeValid =  "error1";
                return false;
            }
            else if(CurrentDate.getTime() > endDate.getTime() && barCodeData.end_date){
                console.log("test 2");
                jQuery("#barCode-custom-error").show().text("Barcode expired last "+ barCodeData.end_date);
                isBarCodeValid =  "error2";
                return false;
            }
            else {
                console.log("test 3");
                isBarCodeValid = true;
                return false;
            }
            
        }

    });
    return isBarCodeValid;
}

</script>';
    return $html;
}

add_shortcode('library_login', 'library_login_func');

function add_custom_sidebar()
{
    $html = '';
    if (isset($_SESSION['library_login_page_id']) && !empty($_SESSION['library_login_page_id'])) {
        $library_logo = get_field('library_logo_url', $_SESSION['library_login_page_id'], true);
        $library_des =  get_field('library_description', $_SESSION['library_login_page_id'], true);

        $html .= '<div class="custom_sidebardata">
<div class="library_info">
<div class="library_logo">
<p><a href="#"><img loading="lazy" class="size-full wp-image-104 aligncenter" src="' . $library_logo . '" alt="" width="168" height="126"></a></p>
</div>

<div class="library_animation">
<br>
<p><a href="/avatar-dress-up"><img loading="lazy" style="margin-top:-23px!important;" class="aligncenter" src="https://firebasestorage.googleapis.com/v0/b/lote4kids-gamification.firebasestorage.app/o/default_body.png?alt=media&token=57faff74-435c-43ba-8871-2ae533debcc8" alt="" width="170" height="161"></a></p>
<p style="font-size: 16px; text-align: center; margin-top: -10px;">
  <a href="/avatar-dress-up/" style="color: #44443D !important;">
    <b>Click Me!</b>
  </a>
</p>
<div class="library_des">
<p style="text-align: justify;">' . $library_des . '</p>
</div>
</div>
</div>';
        return $html;
    } else if (!isset($_SESSION['library_page_id']) || !$_SESSION['library_page_id']) {
        $_SESSION['previous_page_url'] = get_the_permalink(get_the_ID());
        wp_redirect(site_url());
    }
}
add_shortcode('add_custom_sidebar', 'add_custom_sidebar');

function custom_library_logout()
{
    /*Manual Logout by the user*/
    if (isset($_SESSION['library_login_page_id']) && $_SESSION['library_login_page_id'] && isset($_GET['action']) && $_GET['action'] == 'logout') {
        $libUrl = get_permalink(109645);
        // add_wp_activity_log(901);
        unset($_SESSION['library_region_name']);
        unset($_SESSION['library_code_name']);
        unset($_SESSION['library_page_id']);
        unset($_SESSION['barCode']);
        unset($_SESSION['library_login_page_id']);
        unset($_SESSION['rewards']);
        // Load WordPress.
        // require( 'wp-load.php' );

        // define( 'WP_USE_THEMES', false );

        // // Add one page/post per line.
        // $pages_to_clean_preload = [
        //         'https://staging14.lote4kids.com/',//copy this line as many times as necessary.
        //         'https://staging14.lote4kids.com/member-home/',//copy this line as many times as necessary.
        //         ];

        // if ( function_exists( 'rocket_clean_post' ) ) {

        //     foreach( $pages_to_clean_preload as $page_to_clean) {
        //         rocket_clean_post( url_to_postid ( $page_to_clean ) );
        //     }
        // }

        // if ( function_exists( 'get_rocket_option' ) ) {

        //     if( 1 == get_rocket_option( 'manual_preload' ) ) {

        //         $args = array();

        //         if( 1 == get_rocket_option( 'cache_webp' ) ) {
        //             $args[ 'headers' ][ 'Accept' ]          = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
        //             $args[ 'headers' ][ 'HTTP_ACCEPT' ]     = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
        //         }

        //         // Preload desktop pages/posts.
        //         rocket_preload_page( $pages_to_clean_preload, $args );

        //         if( 1 == get_rocket_option( 'do_caching_mobile_files' ) ) {
        //             $args[ 'headers' ][ 'user-agent' ]  = 'Mozilla/5.0 (Linux; Android 8.0.0;) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Mobile Safari/537.36';

        //             // Preload mobile pages/posts.
        //             rocket_preload_page(  $pages_to_clean_preload, $args );
        //         }
        //     }
        // }

        wp_redirect($libUrl);
        die;
    }
    /*Auto logout after 24 hour if barcode does not contain $&*/
    $auto_logout_time = get_option('auto_logout_time');
    $auto_logout_time = $auto_logout_time ? $auto_logout_time : 86400;
    if (isset($_SESSION['most_recent_activity']) && (time() -   $_SESSION['most_recent_activity'] > $auto_logout_time) && @$_SESSION['barCode'] && strpos(@$_SESSION['barCode'], '$&') === false) {
        $libUrl = get_permalink(109645);
        // add_wp_activity_log(902);
        unset($_SESSION['library_region_name']);
        unset($_SESSION['library_code_name']);
        unset($_SESSION['library_page_id']);
        unset($_SESSION['barCode']);
        unset($_SESSION['library_login_page_id']);
        unset($_SESSION['rewards']);
        // Load WordPress.
        // require( 'wp-load.php' );

        // define( 'WP_USE_THEMES', false );

        // // Add one page/post per line.
        // $pages_to_clean_preload = [
        //         'https://staging14.lote4kids.com/',//copy this line as many times as necessary.
        //         'https://staging14.lote4kids.com/member-home/',//copy this line as many times as necessary.
        //         ];

        // if ( function_exists( 'rocket_clean_post' ) ) {

        //     foreach( $pages_to_clean_preload as $page_to_clean) {
        //         rocket_clean_post( url_to_postid ( $page_to_clean ) );
        //     }
        // }

        // if ( function_exists( 'get_rocket_option' ) ) {

        //     if( 1 == get_rocket_option( 'manual_preload' ) ) {

        //         $args = array();

        //         if( 1 == get_rocket_option( 'cache_webp' ) ) {
        //             $args[ 'headers' ][ 'Accept' ]          = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
        //             $args[ 'headers' ][ 'HTTP_ACCEPT' ]     = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
        //         }

        //         // Preload desktop pages/posts.
        //         rocket_preload_page( $pages_to_clean_preload, $args );

        //         if( 1 == get_rocket_option( 'do_caching_mobile_files' ) ) {
        //             $args[ 'headers' ][ 'user-agent' ]  = 'Mozilla/5.0 (Linux; Android 8.0.0;) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Mobile Safari/537.36';

        //             // Preload mobile pages/posts.
        //             rocket_preload_page(  $pages_to_clean_preload, $args );
        //         }
        //     }
        // }

        wp_redirect($libUrl);
        die;
    }
    $_SESSION['most_recent_activity'] = time();
}
add_action('init', 'custom_library_logout');


function rocket_preload_page($pages_to_preload, $args)
{

    foreach ($pages_to_preload as $page_to_preload) {
        wp_remote_get(esc_url_raw($page_to_preload), $args);
    }
}




/*
Will be use incase of multile library pages
function custom_redirects() {
    if(isset($_SESSION['library_page_id']) && $_SESSION['library_page_id'] && $_SESSION['library_page_id'] != get_the_id()){

    }
}
add_action( 'template_redirect', 'custom_redirects' );
*/

add_filter('admin_init', 'general_settings_register_fields');
function general_settings_register_fields()
{
    register_setting('general', 'auto_logout_time', 'esc_attr');
    add_settings_field('auto_logout_time', '<label for="auto_logout_time">' . __('Auto Logout Time(In Seconds)', 'auto_logout_time') . '</label>', 'general_auto_logout_time_callback', 'general');
}

function general_auto_logout_time_callback()
{
    $auto_logout_time = get_option('auto_logout_time');
    echo '<input id="auto_logout_time" style="width: 35%;" type="text" name="auto_logout_time" value="' . $auto_logout_time . '" />';
}

// function add_wp_activity_log($alert_id = 900)
// {
//     /*
// Done Customizations:
// 1) /lote4kids.com/public_html/wp-content/plugins/wp-security-audit-log-premium/default.php // Hint Custom Code  Function name: wsaldefaults_wsal_init
// Add below code in this $wsal->alerts->RegisterGroup array
// array( 900, WSAL_LOW, __( 'Library logged in', 'wp-security-audit-log' ), __( 'Library logged in %LineBreak% Library URL: %LibraryURL% %LineBreak% Library Name: %LibraryName% %LineBreak% Region: %Region% %LineBreak% Barcode: %Barcode%', 'wp-security-audit-log' ), 'library', 'login' ), array( 901, WSAL_LOW, __( 'User logged out', 'wp-security-audit-log' ), __( 'User logged out %LineBreak% Library URL: %LibraryURL% %LineBreak% Library Name: %LibraryName% %LineBreak% Region: %Region% %LineBreak% Barcode: %Barcode%', 'wp-security-audit-log' ), 'library', 'logout' ), array( 902, WSAL_LOW, __( 'User auto logged out', 'wp-security-audit-log' ), __( 'User auto logged out %LineBreak% Library URL: %LibraryURL% %LineBreak% Library Name: %LibraryName% %LineBreak% Region: %Region% %LineBreak% Barcode: %Barcode%', 'wp-security-audit-log' ), 'library', 'logout' ),


// 2) /lote4kids.com/public_html/wp-content/plugins/wp-security-audit-log-premium/Classes/AlertManager.php / Hint Custom Code
// Add this line in $object array
// 'library'           => __( 'Library', 'wp-security-audit-log' ),//Custom Code  function name: get_event_objects_data
// */

//     if (!class_exists('WpSecurityAuditLog')) {
//         return;
//     }


//     $eventType = ($alert_id == 900) ? 'login' : 'logout';
//     $LibUrl = ($alert_id == 900) ? get_permalink($_SESSION['library_page_id']) : get_permalink($_SESSION['library_login_page_id']);
//     global $wpdb;
//     $wpdb->insert(
//         $wpdb->prefix . 'wsal_occurrences',
//         array(
//             'site_id' => '1',
//             'alert_id' => $alert_id,
//             'created_on' => time(),
//             'is_read' => 0,
//             'is_migrated' => 0
//         )
//     );
//     $eventID = $wpdb->insert_id;
//     $wpdb->insert(
//         $wpdb->prefix . 'wsal_metadata',
//         array(
//             'occurrence_id' => $eventID,
//             'name' => 'Object',
//             'value' => 'library'
//         )
//     );

//     $wpdb->insert(
//         $wpdb->prefix . 'wsal_metadata',
//         array(
//             'occurrence_id' => $eventID,
//             'name' => 'EventType',
//             'value' => $eventType
//         )
//     );


//     $wpdb->insert(
//         $wpdb->prefix . 'wsal_metadata',
//         array(
//             'occurrence_id' => $eventID,
//             'name' => 'UserAgent',
//             'value' => $_SERVER['HTTP_USER_AGENT']
//         )
//     );

//     $wpdb->insert(
//         $wpdb->prefix . 'wsal_metadata',
//         array(
//             'occurrence_id' => $eventID,
//             'name' => 'LibraryURL',
//             'value' => $LibUrl
//         )
//     );

//     $wpdb->insert(
//         $wpdb->prefix . 'wsal_metadata',
//         array(
//             'occurrence_id' => $eventID,
//             'name' => 'LibraryName',
//             'value' => ' ' . $_SESSION['library_code_name']
//         )
//     );

//     $wpdb->insert(
//         $wpdb->prefix . 'wsal_metadata',
//         array(
//             'occurrence_id' => $eventID,
//             'name' => 'Region',
//             'value' => ' ' . $_SESSION['library_region_name'],
//         )
//     );

//     $wpdb->insert(
//         $wpdb->prefix . 'wsal_metadata',
//         array(
//             'occurrence_id' => $eventID,
//             'name' => 'Barcode',
//             'value' => ' ' . $_SESSION['barCode']
//         )
//     );

//     $wpdb->insert(
//         $wpdb->prefix . 'wsal_metadata',
//         array(
//             'occurrence_id' => $eventID,
//             'name' => 'ClientIP',
//             'value' => getIPAddress(),
//         )
//     );
// }


function getIPAddress()
{
    //whether ip is from the share internet  
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from the proxy  
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //whether ip is from the remote address  
    else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = '::1';
    }
    return $ip;
}

/*add_filter( 'request',  'bypass_url_users');
function bypass_url_users($query_vars){
    print_r($query_vars);
    die;
}*/


add_action('template_redirect', 'bypass_library_for_auto_login');
function bypass_library_for_auto_login()
{
    if (is_front_page() && isset($_GET['library'])) {
        $lfk_library_url = lfk_library_url();
        $lfk_library = explode(",", $lfk_library_url);

        $args = array(
            'post_type' => 'page',
            // 'posts_per_page' => -1,
            'name' => $lfk_library[1],
        );
        $query = new WP_Query($args);


        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $library_group_name = get_field('library_group_name', get_the_ID(), true);
                // $library_group_region = get_field('library_group_region', get_the_ID(), true);
                $library_group_region = get_field('library_group_region', get_the_ID(), true);
                $library_page = get_field('library_page', get_the_ID(), true);
                // if ($library_group_name == $lfk_library[0] && $library_group_region == $lfk_library[1]) {
                $_SESSION['library_region_name'] = $library_group_region;
                // $_SESSION['library_code_name'] = $lfk_library[0];
                $_SESSION['library_code_name'] = $library_group_name;
                $_SESSION['library_page_id'] = $library_page;
                $_SESSION['library_login_page_id'] = get_the_ID();
                $_SESSION['barCode'] = $lfk_library[0];
                // }
            }
        }


        wp_reset_postdata();
        wp_redirect(site_url('/member-home'));
    }
}
add_action('template_redirect', 'bypass_library_for_ez_proxy_users');

function bypass_library_for_ez_proxy_users()
{

    //https://login.ezproxy.christchurchcitylibraries.com/ 
    if (!isset($_SESSION['library_page_id']) && get_the_id()) {
        $ip_authantication_settings = get_field('ip_authantication_settings', get_the_id(), true);

        if ($ip_authantication_settings) {
            //defined_url 
            $predefined_url = get_field('predefined_url', get_the_id(), true);
            $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            //$HTTP_REFERER_URL = ($HTTP_REFERER)?add_query_arg('qurl', get_the_permalink(get_the_id()), $HTTP_REFERER):'';

            //IP addresses 
            $ip_address_whitelist = get_field('ip_address_whitelist', get_the_id(), true);
            $ip = getIPAddress();

            $library_barcode_number = get_field('library_barcode_number', get_the_id(), true);
            $library_page = get_field('library_page', get_the_id(), true);

            //Checking IP Address in whitelist
            $IsIPWhitelisted =  validateIP($ip_address_whitelist, $ip);
            //mail("vsdevelopers108@gmail.com","DebugData","Test IsIPWhitelisted=".$IsIPWhitelisted." predefined_url=".$predefined_url." HTTP_REFERER_URL=".$HTTP_REFERER." library_barcode_number=".$library_barcode_number." ip=".$ip);       
            ///Make sure if IP address and predefined URL is corract as per  EZ-Proxy
            if ($IsIPWhitelisted && $library_barcode_number) {   //&& (strpos($HTTP_REFERER,'ezproxy') !== false)       
                $url = get_the_permalink();

                $library = basename($url);
                $library = explode('-', $library, 2);
                $library_name = trim($library[1]);
                $library_region = ($library[0]);
                $library_group_name = get_field('library_group_name', get_the_id(), true);
                $library_group_region = get_field('library_group_region', get_the_id(), true);

                $_SESSION['library_region_name'] = ($library_group_region && strpos($library_group_region, 'Select') === false) ? $library_group_region : $library_region;
                $_SESSION['library_code_name'] = $library_group_name ? $library_group_name : $library_name;
                $_SESSION['library_page_id'] = $library_page;
                $_SESSION['library_login_page_id'] = get_the_id();
                $_SESSION['barCode'] = $library_barcode_number;
                $libUrl = isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : get_permalink($library_page);
                $libUrl = (strpos($HTTP_REFERER, 'ezproxy') !== false) ? $libUrl : get_permalink($library_page);
                // add_wp_activity_log();
                add_web_activity(900);
                wp_redirect($libUrl);
                die;
            }
        }
    }
}



function lfk_library_url()
{
    $library_url = '#';

    $library =  $_GET['library'];

    if ($library) {
        $post_data = get_post($library);

        // $simple_string = $post_data->post_name;
        $simple_string = json_encode($post_data->post_name);
        // if (get_current_user_id() == 473) {
        //     echo '<pre>qwe';
        //     print_r(str_replace(' ', '%20', $library));
        //     echo '</pre>';
        //     die();
        // }
        // Display the original string
        //echo "Original String: " . $simple_string."<br>";

        // Store the cipher method
        $ciphering = "AES-128-CTR";

        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567891011121';

        // Store the encryption key
        $encryption_key = "GeeksforGeeks";

        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt(
            $simple_string,
            $ciphering,
            $encryption_key,
            $options,
            $encryption_iv
        );

        // Display the encrypted string
        //echo "Encrypted String: " . $encryption . "<br>";

        // Non-NULL Initialization Vector for decryption
        $decryption_iv = '1234567891011121';

        // Store the decryption key
        $decryption_key = "GeeksforGeeks";

        // Use openssl_decrypt() function to decrypt the data
        $decryption = openssl_decrypt(
            $library,
            $ciphering,
            $decryption_key,
            $options,
            $decryption_iv
        );

        // Display the decrypted string
        //echo "Decrypted String: " . $decryption;


        // $library_url = 'http://staging21.lote4kids.com/?library=' . $encryption;
        // $library_url = 'http://lote4kids.com/';
    }

    return $decryption;
}
function validateIP($whitelist, $ip)
{
    if (!$whitelist || !$ip) {
        return false;
    }

    foreach ($whitelist as $key => $IpRange) {
        $lower_range_ip_address = $IpRange['ip_address_from'];
        $upper_range_ip_address = $IpRange['ip_address_to'];
        $response = ip_in_range($lower_range_ip_address, $upper_range_ip_address, $ip);
        if ($response) {
            return true;
        }
    }
    return false;
}

function ip_in_range($lower_range_ip_address, $upper_range_ip_address, $needle_ip_address)
{
    # Get the numeric reprisentation of the IP Address with IP2long
    $min    = ip2long($lower_range_ip_address);
    $max    = ip2long($upper_range_ip_address);
    $needle = ip2long($needle_ip_address);
    # Then it's as simple as checking whether the needle falls between the lower and upper ranges
    return (($needle >= $min) and ($needle <= $max));
}

// add_action('wp_ajax_nopriv_filter_language', 'filter_language');
// add_action('wp_ajax_filter_language', 'filter_language');
// function filter_language()
// {
//     $post = get_post($_POST['page_id']);

//     $args = array(
//         'PageTitle' => $post->post_title,
//         'PageUrl' => site_url($post->post_name),
//         'LibraryName' =>  $_SESSION['library_code_name'],
//         'BarCode' => $_SESSION['barCode'],
//         'RegionName' => $_SESSION['library_region_name'],
//         'ButtonClicked' => $_POST['button_clicked'],
//         'ClientIP' => getIPAddress(),
//         'UserAgent' => $_SERVER['HTTP_USER_AGENT'],
//         'EventType' => 'viewed',
//         'Object' => 'post',
//     );

//     $alert_id = 1092;
//     lote_activity_log($args, $alert_id);
//     echo json_encode($args);
//     wp_die();
// }

include('thea-functions.php');