<?php
/**
 * ----------------------------------------------------------------
 * Check if previously logged in
 * ----------------------------------------------------------------
 */

add_action('init', function () {

    session_start();
    if (!empty($_SESSION['library_barcode'])) { return; } // if session already exists → do nothing
    if (l4k_checkCookieLoggedIn()) { return; } // if cookie exists → rebuild session
    if (l4k_checkDomainAutoLogin()) { return; } // if domain is whitelisted -> create session and login automatically
    if (l4k_checkIpAutoLogin()) { return; } // if IP is whitelisted -> create session and login automatically
    if (l4k_checkAuthAutoLogin()) { return; } // if auth querystring exists -> create session and login automatically

});

/**
 * ----------------------------------------------------------------
 * Require other functions
 * ----------------------------------------------------------------
 */

require_once('functions/functions-dev.php');        // temporary dev functions ex. for debugging
require_once('functions/functions-redirect.php');   // anything that has to do with logins and redirects 
require_once('functions/functions-admin.php');      // all admin functions
require_once('functions/functions-retrieve.php');   // all database retrieval functions
require_once('functions/functions-update.php');     // all database update functions
require_once('functions/functions-ajax.php');       // all ajax functions
require_once('functions/functions-mobile.php');     // mobile endpoints & settings

/**
 * ----------------------------------------------------------------
 * Enqueue styles and scripts
 * ----------------------------------------------------------------
 */

add_action('wp_enqueue_scripts', function () {

    l4k_loadStyles();
    l4k_loadScripts();

});

function l4k_loadStyles() {

    $assetVersion = (WP_ENVIRONMENT_TYPE == 'STG') ? time() : WP_ASSET_VERSION; 

    // load only styles specific per page
   
    if (is_page_template('page-templates/page-home.php'))                   { wp_enqueue_style('home', get_stylesheet_directory_uri() . '/assets/css/home.css', [], $assetVersion); }
    if (is_page_template('page-templates/page-faqs.php'))                   { wp_enqueue_style('faq', get_stylesheet_directory_uri() . '/assets/css/faq.css', [], $assetVersion); }
    if (is_page_template('page-templates/page-mobile-app.php'))             { wp_enqueue_style('mobile-app', get_stylesheet_directory_uri() . '/assets/css/mobile-app.css', [], $assetVersion); }
    if (is_page_template('page-templates/page-about-us.php'))               { wp_enqueue_style('about-us', get_stylesheet_directory_uri() . '/assets/css/about-us.css', [], $assetVersion); }
    if (is_page_template('page-templates/page-publishers.php'))             { wp_enqueue_style('publishers', get_stylesheet_directory_uri() . '/assets/css/publishers.css', [], $assetVersion); }
    if (is_page_template('page-templates/page-privacy-policy.php'))         { wp_enqueue_style('privacy-terms', get_stylesheet_directory_uri() . '/assets/css/privacy-terms.css', [], $assetVersion); }
    if (is_page_template('page-templates/page-terms-of-use.php'))           { wp_enqueue_style('privacy-terms', get_stylesheet_directory_uri() . '/assets/css/privacy-terms.css', [], $assetVersion); }    
    if (is_page_template('page-templates/page-available-languages.php'))    { wp_enqueue_style('member-home', get_stylesheet_directory_uri() . '/assets/css/member-home.css', [], $assetVersion); }    
    if (is_page_template('page-templates/page-contact-us.php'))             { wp_enqueue_style('contact', get_stylesheet_directory_uri() . '/assets/css/contact.css', [], $assetVersion); }
    if (is_page_template('page-templates/page-blog.php') || is_tag())       { wp_enqueue_style('blog', get_stylesheet_directory_uri() . '/assets/css/blog.css', [], $assetVersion); }
    if (is_page_template('page-templates/page-staff-access.php'))           { wp_enqueue_style('staff', get_stylesheet_directory_uri() . '/assets/css/staff.css', [], $assetVersion); }
    if (is_page_template('page-templates/page-marketing-collateral.php'))   { wp_enqueue_style('marketing-collateral', get_stylesheet_directory_uri() . '/assets/css/marketing-collateral.css', [], $assetVersion); }
    if (is_singular('post'))                                                { wp_enqueue_style('blog', get_stylesheet_directory_uri() . '/assets/css/blog.css', [], $assetVersion); }
    if (is_404())                                                           { wp_enqueue_style('404', get_stylesheet_directory_uri() . '/assets/css/404.css', [], $assetVersion); }

    if (is_search()) { 
        wp_enqueue_style('search', get_stylesheet_directory_uri() . '/assets/css/search.css', [], $assetVersion); 
        wp_enqueue_style('sidebar-main', get_stylesheet_directory_uri() . '/assets/css/sidebar-main.css', [], $assetVersion); 
    }
    if (is_singular('library')) { 
        wp_enqueue_style('library', get_stylesheet_directory_uri() . '/assets/css/library.css', [], $assetVersion); 
        wp_enqueue_style('sidebar-dashboard', get_stylesheet_directory_uri() . '/assets/css/sidebar-dashboard.css', [], $assetVersion);
    }    
    if (is_page_template('page-templates/page-member-home.php')) { 
        wp_enqueue_style('member-home', get_stylesheet_directory_uri() . '/assets/css/member-home.css', [], $assetVersion); 
        wp_enqueue_style('sidebar-main', get_stylesheet_directory_uri() . '/assets/css/sidebar-main.css', [], $assetVersion);
    } 
    if (is_singular('book')) { 
        wp_enqueue_style('book', get_stylesheet_directory_uri() . '/assets/css/book.css', [], $assetVersion); 
        wp_enqueue_style('sidebar-book', get_stylesheet_directory_uri() . '/assets/css/sidebar-book.css', [], $assetVersion); 
    }
    if (is_singular('playlist')) { 
        wp_enqueue_style('playlist', get_stylesheet_directory_uri() . '/assets/css/playlist.css', [], $assetVersion); 
        wp_enqueue_style('sidebar-playlist', get_stylesheet_directory_uri() . '/assets/css/sidebar-playlist.css', [], $assetVersion); 
    }
    if (is_singular('language')) { 
        wp_enqueue_style('language', get_stylesheet_directory_uri() . '/assets/css/language.css', [], $assetVersion); 
        wp_enqueue_style('sidebar-main', get_stylesheet_directory_uri() . '/assets/css/sidebar-main.css', [], $assetVersion); 
    }
    if (is_singular('activity') || is_page_template('page-templates/page-activities.php')) { 
        wp_enqueue_style('activity', get_stylesheet_directory_uri() . '/assets/css/activity.css', [], $assetVersion); 
        wp_enqueue_style('sidebar-main', get_stylesheet_directory_uri() . '/assets/css/sidebar-main.css', [], $assetVersion); 
    }

    // styles for all

    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap', false);
    wp_enqueue_style('lote4kids-style', get_stylesheet_directory_uri() . '/style.css', [], $assetVersion);
    wp_enqueue_style('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', [], null);
    wp_enqueue_style('line-icons', 'https://cdn.lineicons.com/5.0/lineicons.css', [], '5.0');

    return;
}

function l4k_loadScripts() {

    $assetVersion = (WP_ENVIRONMENT_TYPE == 'STG') ? time() : WP_ASSET_VERSION; 

    // load only scripts specific per page

    if (is_404())                                                           { wp_enqueue_script('language', get_stylesheet_directory_uri() . '/assets/js/404.js', ['jquery'], $assetVersion, true); }
    if (is_front_page())                                                    { wp_enqueue_script('botpress', 'https://cdn.botpress.cloud/webchat/v2.4/inject.js', ['jquery'], null, true); }
    if (is_singular('language'))                                            { wp_enqueue_script('language', get_stylesheet_directory_uri() . '/assets/js/language.js', ['jquery'], $assetVersion, true); }
    if (is_singular('playlist'))                                            { wp_enqueue_script('playlist', get_stylesheet_directory_uri() . '/assets/js/playlist.js', ['jquery'], $assetVersion, true); }
    if (is_page_template('page-templates/page-faqs.php'))                   { wp_enqueue_script('faq', get_stylesheet_directory_uri() . '/assets/js/faq.js', ['jquery'], $assetVersion, true); }
    if (is_page_template('page-templates/page-member-home.php'))            { wp_enqueue_script('member-home', get_stylesheet_directory_uri() . '/assets/js/member-home.js', ['jquery'], $assetVersion, true); }
    if (is_page_template('page-templates/page-available-languages.php'))    { wp_enqueue_script('member-home', get_stylesheet_directory_uri() . '/assets/js/member-home.js', ['jquery'], $assetVersion, true); }

    if (is_singular('book')) { 
        wp_enqueue_script('pdf', 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js', ['jquery'], '', true); 
        wp_enqueue_script('chart', 'https://cdn.jsdelivr.net/npm/chart.js', ['jquery'], '', false); 
        wp_enqueue_script('turn', get_stylesheet_directory_uri() . '/assets/js/turn.js', ['jquery'], $assetVersion, true); 
        wp_enqueue_script('book', get_stylesheet_directory_uri() . '/assets/js/book.js', ['jquery'], $assetVersion, true); 
        wp_localize_script('book', 'book_ajax', [ 'url' => admin_url('admin-ajax.php') ]); // so you can use 'book_ajax.url' variable
    }
    if (is_singular('library')) { 
        wp_enqueue_script('library', get_stylesheet_directory_uri() . '/assets/js/library.js', ['jquery'], $assetVersion, true); 
        wp_localize_script('library', 'library_ajax', [ 'url' => admin_url('admin-ajax.php') ]); // so you can use 'library_ajax.url' variable
    }

    // scripts for all

    wp_enqueue_script('jquery');
    wp_enqueue_script('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', ['jquery'], null, true);
    wp_enqueue_script('google-translate', '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit', ['jquery'], null, true);
    wp_enqueue_script('lote4kids', get_stylesheet_directory_uri() . '/assets/js/main.js', ['jquery'], $assetVersion, true);

    return;

}

/**
 * ----------------------------------------------------------------
 * Prepare current page's breadcrumb
 * ----------------------------------------------------------------
 */

function l4k_breadcrumbs() {

    global $post;
    global $wp;

    $isReadingPack = l4k_isReadingPackContext();

    // if member is logged in, show the library name instead of "Home"
    $homeLabel = 'Home';
    if ($_SESSION['library_name']) { $homeLabel = $_SESSION['library_name']; }

    $linksArr = array(array('label' => '<i class="lni lni-home-2"></i> ' . $homeLabel,
                            'permalink' => get_site_url()));

    // do not show breadcrumb on home and 404 pages
    if (is_front_page() || is_404()) 
    {
        return array();
    }

    // member home
    elseif (is_page_template('page-templates/page-member-home.php')) 
    {
        $linksArr[] = array('label' => 'Member Home', 'permalink' => get_permalink());
    }

    // home > [page title]
    elseif (is_search()) 
    {
        $linksArr[] = array('label' => "Search", 'permalink' => l4k_getCurrentURL());
        $linksArr[] = array('label' => "'".get_search_query()."'", 'permalink' => l4k_getCurrentURL());
    }

    // home > [page title]
    elseif (is_singular('page')) 
    {
        $linksArr[] = array('label' => get_the_title(), 'permalink' =>get_permalink());

        // cases like Blog > Page 2 for example
        if (is_paged())
        { 
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $current_url = home_url(add_query_arg([], $wp->request));
            $linksArr[] = array('label' => "Page ". $paged, 'permalink' => $current_url);
        }
    }

    // home > blog > [post title]
    elseif (is_singular('post')) 
    {
        $blog_page = get_page_by_path('blog');
        $linksArr[] = array('label' => "Blog", 'permalink' => get_permalink($blog_page->ID));
        $linksArr[] = array('label' => get_the_title(), 'permalink' => get_permalink());
    }

    // home > blog > tag > [tag title]
    elseif (is_tag()) 
    {
     
        $blog_page = get_page_by_path('blog');
        $linksArr[] = array('label' => "Blog", 'permalink' => get_permalink($blog_page->ID));

        $tag = get_queried_object();
        $tag_link = get_tag_link($tag->term_id);
        $linksArr[] = array('label' => 'Tag', 'permalink' => $tag_link);
        $linksArr[] = array('label' => $tag->name, 'permalink' => $tag_link);

        // cases like Tag > Page 2 for example
        if (is_paged())
        { 
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $current_url = home_url(add_query_arg([], $wp->request));
            $linksArr[] = array('label' => "Page ". $paged, 'permalink' => $current_url);
        }
    }

    // home > member home > [language title]
    elseif (is_singular('language') && !$isReadingPack) 
    {
        $linksArr[] = array('label' => 'Member Home','permalink' => home_url() . '/member-home');
        $linksArr[] = array('label' => get_the_title(),'permalink' => get_permalink());
    }

    // home > member home > [language title] > reading pack
    elseif (is_singular('language') && $isReadingPack)  
    {
        $linksArr[] = array('label' => 'Member Home', 'permalink' => home_url().'/member-home');
        $linksArr[] = array('label' => get_the_title(), 'permalink' => get_permalink());
        $linksArr[] = array('label' => 'Reading Pack', 'permalink' => get_permalink().'reading-pack');
    }

    // home > member home > [language title] > [book title]
    elseif (is_singular('book')) 
    {
        $langID = get_field('language');
        $linksArr[] = array('label' => 'Member Home', 'permalink' => home_url() . '/member-home'  );
        $linksArr[] = array('label' => get_the_title($langID), 'permalink' => get_permalink($langID));

        if ($isReadingPack) { 
            $linksArr[] = array('label' => 'Reading Pack', 'permalink' => get_permalink($langID).'reading-pack');
        }

        $linksArr[] = array('label' => get_the_title(get_field('native_story')), 'permalink' => get_permalink().'reading-pack');
    }

    // home > [language title] > [playlist title]
    elseif (is_singular('playlist')) 
    {
        $langID = get_field('language', get_the_ID());
        $linksArr[] = array('label' => 'Member Home', 'permalink' => home_url() . '/member-home'  );
        $linksArr[] = array('label' => get_the_title($langID), 'permalink' => get_permalink($langID));
        $linksArr[] = array('label' => get_field('display_title', get_the_ID()), 'permalink' => get_permalink());
    }

    // home > activities > [activity title]
    elseif (is_singular('activity')) 
    {
        $activities_page = get_page_by_path('activities');
        $linksArr[] = array('label' => "Activities", 'permalink' => get_permalink($activities_page->ID));
        $linksArr[] = array('label' => get_the_title(), 'permalink' => get_permalink());
    }

    // home > [library title] > Dashboard
    elseif (is_singular('library') && (get_query_var('dashboard', false) !== false)) 
    {
        $linksArr[] = array('label' => get_the_title(), 'permalink' => get_permalink());
        $linksArr[] = array('label' => 'Dashboard', 'permalink' => '');
    }

    // home > [library title] > Trial
    elseif (is_singular('library') && (get_query_var('trial', false) !== false)) 
    {
        $linksArr[] = array('label' => get_the_title(), 'permalink' => get_permalink());
        $linksArr[] = array('label' => 'Trial', 'permalink' => '');
    }

    // home > [library title] > Competition
    elseif (is_singular('library') && (get_query_var('competition', false) !== false)) 
    {
        $linksArr[] = array('label' => get_the_title(), 'permalink' => get_permalink());
        $linksArr[] = array('label' => 'Competition', 'permalink' => '');
    }

    // home > [library title]
    elseif (is_singular('library')) 
    {
        $linksArr[] = array('label' => get_the_title(), 'permalink' => get_permalink());
    }

    return $linksArr;
}

/**
 * ----------------------------------------------------------------
 * Add active class to the main nav when needed
 * ----------------------------------------------------------------
 */

add_filter('nav_menu_css_class', function ($classes, $item) {

    if (is_singular('activity')) { if ($item->ID == 39) { $classes[] = 'current-menu-item'; } } // activities menu item
    if (is_singular('library') && (get_query_var('dashboard', false) !== false)) { if ($item->ID == 49) { $classes[] = 'current-menu-item'; } } // staff access menu item
    if (is_singular('post') || is_tag()) { if ($item->ID == 41) { $classes[] = 'current-menu-item'; } } // blog menu item

    return $classes;

}, 10, 2);

/**
 * ----------------------------------------------------------------
 * Remove domain and return path only for any URL
 * ----------------------------------------------------------------
 */

function l4k_getLinkPathOnly($url) {

    return home_url().parse_url($url, PHP_URL_PATH);

}

/**
 * ----------------------------------------------------------------
 * Remove domain and return path only for any URL
 * ----------------------------------------------------------------
 */

function l4k_getBGColor() {

    if (is_singular('book') || is_singular('playlist')) { 
        $currentLang = get_field('language', get_the_ID());
        $bgColor = get_field('lang_background_color', $currentLang);
        return $bgColor;
    }

    if (is_singular('language')) { 
        $bgColor = get_field('lang_background_color', get_the_ID());
        return $bgColor;
    }

}

/**
 * ----------------------------------------------------------------
 * Add Logout link to the main menu if session exists
 * ----------------------------------------------------------------
 */

add_filter('wp_nav_menu_items', function($items, $args) {

    if ($args->menu === 'Main Menu') {
        if (isset($_SESSION['library_barcode'])) {
            $items .= '
                <li class="menu-item logout">
                    <a title="Logout" href="' . home_url('/?member-logout') . '">'.
                        $_SESSION['library_barcode'].'
                        <i class="lni lni-exit"></i>
                    </a>
                </li>';
        }
    }
    return $items;

}, 10, 2);

/**
 * ----------------------------------------------------------------
 * Handle member logout via ?member-logout
 * ----------------------------------------------------------------
 */

add_action('init', function() {

    if (isset($_GET['member-logout'])) {

        $redirectBackTo = $_SESSION['library_permalink'];
        
        wp_logout(); // logs WP user out
        wp_clear_auth_cookie();

        $_SESSION = []; // remove all session data
        session_start();
        session_unset();
        session_destroy();

        setcookie('remember_member', '', time() - 3600, '/'); // delete persistent cookie

        wp_safe_redirect($redirectBackTo); // redirect to most recent logged in library

        exit; 

    }

});

/**
 * ----------------------------------------------------------------
 * Save the currently viewing book to session
 * To be displayed as last viewed
 * ----------------------------------------------------------------
 */

function l4k_updateLastViewedBook($bookID) {

    $_SESSION['last_viewed_book'] = $bookID;

}

/**
 * ----------------------------------------------------------------
 * Parse vimeo URL and get the IDs
 * ----------------------------------------------------------------
 */

function l4k_parseVimeoUrl($vimeoURL) {

    $idArr      = array();
    $vimeoURL   = rtrim($vimeoURL, '/'); // remove trailing slash
    $parts      = parse_url($vimeoURL, PHP_URL_PATH);  // returns "/1005728639/2da9378777"
    $idArr      = explode('/', trim($parts, '/')); // split by slash

    return $idArr;

}

/**
 * ----------------------------------------------------------------
 * Retrieve visitor's IP address even when behind a proxy
 * ----------------------------------------------------------------
 */

function l4k_getClientIP() {

    $keys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ipList = explode(',', $_SERVER[$key]); // Could contain multiple IPs
            foreach ($ipList as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
    }

    return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'; // fallback to REMOTE_ADDR even if private

}

/**
 * ----------------------------------------------------------------
 * Retrieve visitor's domain referrer
 * ----------------------------------------------------------------
 */

function l4k_getClientReferrerDomain() {

    $referrer = $_SERVER['HTTP_REFERER'] ?? ''; // get the referrer URL
    if (empty($referrer)) { return ''; } // if no referrer, return empty string
    $parsedUrl = parse_url($referrer); // parse the URL and get the host (domain)
    return isset($parsedUrl['host']) ? $parsedUrl['host'] : ''; // return the host/domain if it exists

}

/**
 * ----------------------------------------------------------------
 * Check if book as quiz
 * ----------------------------------------------------------------
 */

function l4k_hasQuiz($bookID) {

    if (have_rows('download_links', $bookID)) :
        while (have_rows('download_links', $bookID)) : the_row();

            if (get_sub_field('activity') == 'Quiz') { return true; }

        endwhile;
    endif;

    return false;

}

/**
 * ----------------------------------------------------------------
 * Generate the quick copy video link with auth code
 * ----------------------------------------------------------------
 */

function l4k_generateQuickCopyLink($bookID) {

    if ($_SESSION['auto_login_status'] && $_SESSION['auth_token']) {
        return get_permalink($bookID) . '?auth=' . $_SESSION['auth_token'];
    }

    return get_permalink($bookID);
    
}

/**
 * ----------------------------------------------------------------
 * Staff access login form
 * ----------------------------------------------------------------
 */

function l4k_staffLoginShortCode() {

    if (is_user_logged_in()) { return '<p>You are already logged in.</p>'; }

    $error_msg = '';

    // handle login POST
    if (isset($_POST['l4k_staff_login'])) {

        $creds = [
            'user_login'    => sanitize_text_field($_POST['log']),
            'user_password' => $_POST['pwd'],
            'remember'      => true,
        ];

        $user = wp_signon($creds);

        if (is_wp_error($user)) { $error_msg = '<p class="error-msg">'. $user->get_error_message() .'</p>'; } 
        else { wp_safe_redirect( home_url('/staff-access') ); exit; }
    }

    ob_start();
    ?>

    <?php echo $error_msg; ?>

    <form method="post" id="staff-loginform">
        <p class='login-username'>
            <label>Username or Email<br>
                <input type="text" name="log" value="" required>
            </label>
        </p>

        <p class='login-password'>
            <label>Password<br>
                <input type="password" name="pwd" required>
            </label>
        </p>

        <p>
            <input type="submit" class='button-primary _btn' name="l4k_staff_login" value="Staff Login">
        </p>
    </form>

    <?php
    return ob_get_clean();
}
add_shortcode('l4k_staffLogin', 'l4k_staffLoginShortCode');

/**
 * ----------------------------------------------------------------
 * Do the redirect below if logged in user is a staff
 * Other adminstrators when logging should not be affected
 * ----------------------------------------------------------------
 */

function l4k_staffLoginRedirect($redirect_to, $requested_redirect_to, $user) {

    if (!isset($user->ID)) { return $redirect_to; } // make sure we have a valid user object

    // redirect to the library dashboard only if role is 'subscriber'
    if (in_array('subscriber', (array) $user->roles, true)) {
        $customRedirect = get_the_permalink(get_field('library', 'user_'.$user->ID)).'dashboard'; 
        if (!empty($customRedirect)) { return esc_url_raw($customRedirect); }
    }

    return $redirect_to; // default: use WordPress redirect
}
add_filter('login_redirect', 'l4k_staffLoginRedirect', 10, 3);

/**
 * ----------------------------------------------------------------
 * Add /dashboard endpoint to "Libraries" custom post type
 * Old - lote4kids.com/libraries/au-demo?dashboard=true
 * New - lote4kids.com/libraries/au-demo/dashboard
 * ----------------------------------------------------------------
 */

function l4k_addDashboardEndpoint() {
    add_rewrite_endpoint('dashboard', EP_PERMALINK);
}
add_action('init', 'l4k_addDashboardEndpoint');

function l4k_dashboardQueryVars($vars) {
    $vars[] = 'dashboard';
    return $vars;
}
add_filter('query_vars', 'l4k_dashboardQueryVars');

/**
 * ----------------------------------------------------------------
 * Add /trial endpoint to "Libraries" custom post type
 * Old - lote4kids.com/libraries/au-demo?trial=true
 * New - lote4kids.com/libraries/au-demo/trial
 * ----------------------------------------------------------------
 */

function l4k_addTrialEndpoint() {
    add_rewrite_endpoint('trial', EP_PERMALINK);
}
add_action('init', 'l4k_addTrialEndpoint');

function l4k_TrialQueryVars($vars) {
    $vars[] = 'trial';
    return $vars;
}
add_filter('query_vars', 'l4k_TrialQueryVars');

/**
 * ----------------------------------------------------------------
 * Add /competition endpoint to "Libraries" custom post type
 * Old - lote4kids.com/libraries/au-demo?competition=true
 * New - lote4kids.com/libraries/au-demo/competition
 * ----------------------------------------------------------------
 */

function l4k_addCompetitionEndpoint() {
    add_rewrite_endpoint('competition', EP_PERMALINK);
}
add_action('init', 'l4k_addCompetitionEndpoint');

function l4k_CompetitionQueryVars($vars) {
    $vars[] = 'competition';
    return $vars;
}
add_filter('query_vars', 'l4k_CompetitionQueryVars');

/**
 * ----------------------------------------------------------------
 * Add /reading-pack endpoint to "Languages" custom post type
 * Old - lote4kids.com/languages/filipino-tagalog?reading-pack=true
 * New - lote4kids.com/languages/filipino-tagalog/reading-pack
 * ----------------------------------------------------------------
 */

function l4k_addReadingPackEndpoint() {
    add_rewrite_endpoint('reading-pack', EP_PERMALINK);
}
add_action('init', 'l4k_addReadingPackEndpoint');

function l4k_readingPackQueryVars($vars) {
    $vars[] = 'reading-pack';
    return $vars;
}
add_filter('query_vars', 'l4k_readingPackQueryVars');

/**
 * ----------------------------------------------------------------
 * Divide array into 3 parts (for table display)
 * This shows up in staff dashboard's Language Breakdown menu
 * ----------------------------------------------------------------
 */

function l4k_countTotalBookCountAndLatestRelease($languages) {

    $counter = $total = $mostRecent = 0; 

    while($counter < count($languages[0])) 
    {
        $total =    $total + 
                    $languages[0][$counter]['book_count'] + 
                    $languages[1][$counter]['book_count'] + 
                    $languages[2][$counter]['book_count']; 

        $mostRecent = ($languages[0][$counter]['date_published'] > $mostRecent) ? $languages[0][$counter]['date_published'] : $mostRecent;
        $mostRecent = ($languages[1][$counter]['date_published'] > $mostRecent) ? $languages[0][$counter]['date_published'] : $mostRecent;
        $mostRecent = ($languages[2][$counter]['date_published'] > $mostRecent) ? $languages[0][$counter]['date_published'] : $mostRecent;

        $counter++;
    }

    $summary['total_count'] = $total;
    $summary['most_recent'] = date('F j, Y', strtotime($mostRecent));

    return $summary;

}

/**
 * ----------------------------------------------------------------
 * Divide array into 3 parts (for table display)
 * ----------------------------------------------------------------
 */

function l4k_splitIntoThree($array) {

    $size = ceil(count($array) / 3);

    return [
        array_slice($array, 0, $size),
        array_slice($array, $size, $size),
        array_slice($array, $size * 2)
    ];

}

/**
 * ----------------------------------------------------------------
 * Add lote logo as custom avatar image for users
 * ----------------------------------------------------------------
 */

add_filter('avatar_defaults', function($avatars) {

    $custom_avatar_url = get_stylesheet_directory_uri() . '/assets/img/logo-lote-avatar.png';
    $avatars[$custom_avatar_url] = 'L4K Custom Avatar';
    return $avatars;

});

add_filter('get_avatar_url', function( $url, $id_or_email, $args) {

    // only override on local to avoid broken gravatar urls
    if ((strpos(home_url(), 'localhost') !== false) || (strpos(home_url(), '.local') !== false)) {
        return get_stylesheet_directory_uri() . '/assets/img/logo-lote-avatar.png';
    }

    return $url;
    
}, 10, 3 );

/**
 * ----------------------------------------------------------------
 * Compare 2 strings without any special characters if same
 * ----------------------------------------------------------------
 */

function l4k_compareStringsNoSpecial($stringA, $stringB) {

    // keep only A-Z (remove numbers and special characters)
    $stringA = preg_replace('/[^A-Za-z]/', '', $stringA);
    $stringB = preg_replace('/[^A-Za-z]/', '', $stringB);

    // make comparison case-insensitive
    $stringA = strtolower($stringA);
    $stringB = strtolower($stringB);

    return $stringA === $stringB;
    
}

/**
 * ----------------------------------------------------------------
 * Get the page's current URL
 * ----------------------------------------------------------------
 */

function l4k_getCurrentURL() {

    $protocol = is_ssl() ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    return $protocol . $host . $request_uri;

}

/**
 * ----------------------------------------------------------------
 * Exempt certain elements in the site from being searched
 * ----------------------------------------------------------------
 */

function l4k_exemptFromSearch($query) {

    // only modify frontend main search queries
    if ($query->is_search() && $query->is_main_query() && !is_admin()) {

        // remove 'story' CPT
        $post_types = get_post_types(['public' => true]);
        if (isset($post_types['story'])) { unset($post_types['story']); }
        $query->set('post_type', $post_types); // only search remaining post types

        // order posts: language CPT > normal posts > book CPT
        add_filter('posts_orderby', function($orderby, $query) {
            global $wpdb;
            if ($query->is_search() && $query->is_main_query()) {
                $orderby = "
                    FIELD({$wpdb->posts}.post_type, 'language', 'post', 'book') ASC,
                    {$wpdb->posts}.post_title ASC,
                    {$wpdb->posts}.post_date DESC
                ";
            }
            return $orderby;
        }, 10, 2);

    }

}
add_action('pre_get_posts', 'l4k_exemptFromSearch');

/**
 * ----------------------------------------------------------------
 * Change search results to a max of 200 instead of just 10
 * ----------------------------------------------------------------
 */

function l4k_increaseResultsCount($query) {

    // only modify frontend main search queries
    if ($query->is_search() && !is_admin() && $query->is_main_query()) {
        $query->set('posts_per_page', 200);
    }

}
add_action('pre_get_posts', 'l4k_increaseResultsCount');

/**
 * ----------------------------------------------------------------
 * Determine if section should be shown as RTL
 * ----------------------------------------------------------------
 */

function l4k_determineRTL($bookID) {

    $langID = get_field('language', $bookID);
    $bookType = get_field('book_type', $bookID);
    $textDirection = get_field('text_direction', $langID);

    if (($bookType == 'video_monolingual') && ($textDirection == 'rtl')) { return true; }

    return false;
    
}

/**
 * ----------------------------------------------------------------
 * Determine if Reading Pack is selected
 * ----------------------------------------------------------------
 */

function l4k_isReadingPackContext() {

    return strpos($_SERVER['REQUEST_URI'], '/reading-pack/') !== false;

}

/**
 * ----------------------------------------------------------------
 * Check if language is part of the library's reading pack
 * ----------------------------------------------------------------
 */

function l4k_isLanguagePartOfReadingPacks($langID) {

    if (have_rows('language_packs', $_SESSION['library_id'])) :
        while (have_rows('language_packs', $_SESSION['library_id'])) : the_row();
            if (get_sub_field('language') == $langID) { return true; }
        endwhile;
    endif;

    return false;

}

/**
 * ----------------------------------------------------------------
 * Add additional email addresses to be notified every new comment
 * These can be set on Main Settings > Comment Notification Emails
 * ----------------------------------------------------------------
 */

function l4k_addExtraCommentNotificationEmails($emails, $comment_id) {

    $emails = [];

    if (have_rows('comment_notification_emails', 'option')) :
        while (have_rows('comment_notification_emails', 'option')) : the_row();
            $emails[] = get_sub_field('email_address');
        endwhile;
    endif;  
    
    return $emails;
}
add_filter('comment_moderation_recipients', 'l4k_addExtraCommentNotificationEmails', 10, 2);

/**
 * ----------------------------------------------------------------
 * Schedule the cron event for 3 months views cleanup
 * ----------------------------------------------------------------
 */

// 1. Add monthly interval
add_filter('cron_schedules', function ($schedules) {
    $schedules['three_months'] = [
        'interval' => 90 * DAY_IN_SECONDS,
        'display'  => __('Once Every 3 Months'),
    ];
    return $schedules;
});

// 2. Schedule event (once)
add_action('init', function(){
    if(!wp_next_scheduled('l4k_reset_recent_views')){
        wp_schedule_event(time(), 'three_months', 'l4k_reset_recent_views');
    }
});

// 3. Cron callback
// add_action('l4k_reset_recent_views', function () {

//     global $wpdb;

//     $limit   = 100;
//     $last_id = 0;
//     $total   = 0;

//     error_log('[ReadingPack] Reset started at ' . current_time('mysql'));

//     while (true) {

//         add_filter('posts_where', function ($where) use ($last_id, $wpdb) {
//             return $where . $wpdb->prepare(" AND {$wpdb->posts}.ID > %d ", $last_id);
//         });

//         $books = get_posts([
//             'post_type'      => 'book',
//             'post_status'    => 'publish',
//             'posts_per_page' => $limit,
//             'fields'         => 'ids',
//             'orderby'        => 'ID',
//             'order'          => 'ASC',
//             'no_found_rows'              => true,
//             'update_post_meta_cache'     => false,
//             'update_post_term_cache'     => false,
//             'suppress_filters'           => false, // IMPORTANT
//         ]);

//         remove_all_filters('posts_where');

//         if (empty($books)) {
//             break;
//         }

//         foreach ($books as $book_id) {
//             update_post_meta(
//                 $book_id,
//                 'additional_details_views_last_3_months',
//                 0
//             );
//             $total++;
//             $last_id = $book_id;
//         }

//         // Free memory between batches
//         wp_cache_flush();

//         error_log('[ReadingPack] Reset progress — ' . $total . ' books');
//     }

//     error_log('[ReadingPack] Reset finished — ' . $total . ' books at ' . current_time('mysql'));
// });
add_action('l4k_reset_recent_views', function () {

    $batch   = 300;
    $offset  = (int) get_option('l4k_reading_pack_offset', 0);
    $total   = (int) get_option('l4k_reading_pack_total', 0);

    error_log('[ReadingPack] Batch start — offset=' . $offset);

    $books = get_posts([
        'post_type'      => 'book',
        'post_status'    => 'publish',
        'posts_per_page' => $batch,
        'offset'         => $offset,
        'fields'         => 'ids',
        'orderby'        => 'ID',
        'order'          => 'ASC',
        'no_found_rows'  => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    if (empty($books)) {
        delete_option('l4k_reading_pack_offset');
        delete_option('l4k_reading_pack_total');

        error_log('[ReadingPack] Reset FINISHED — ' . $total . ' books');
        return;
    }

    foreach ($books as $book_id) {
        update_post_meta(
            $book_id,
            'additional_details_views_last_3_months',
            0
        );
        $total++;
    }

    update_option('l4k_reading_pack_offset', $offset + count($books), false);
    update_option('l4k_reading_pack_total', $total, false);

    error_log('[ReadingPack] Batch progress — ' . $total . ' books');

    // schedule next batch
    wp_schedule_single_event(time() + 5, 'l4k_reset_recent_views');
});



//Manual trigger via admin URL

add_action('admin_init', function () {
    if (
        current_user_can('manage_options') &&
        isset($_GET['run_reading_pack_reset'])
    ) {
        do_action('l4k_reset_recent_views');
        add_action('admin_notices', function () {
            echo '<div class="notice notice-success"><p>Reading Pack reset executed.</p></div>';
        });
    }
});

include('custom-functions.php');
?>