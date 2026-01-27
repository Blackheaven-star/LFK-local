<?php
/**
 * ----------------------------------------------------------------
 * Add "Main Settings" & "Mobile Settings" menu in CMS
 * ----------------------------------------------------------------
 */

if (function_exists('acf_add_options_page')) {

    acf_add_options_page([
        'page_title'  => 'Main Settings',
        'menu_title'  => 'Main Settings',
        'menu_slug'   => 'main-settings',
        'capability'  => 'manage_options',
        'redirect'    => false,
        'position'    => 2,
        'icon_url'    => 'dashicons-admin-settings'

    ]);

    acf_add_options_sub_page([
        'page_title'  => 'Mobile Settings',
        'menu_title'  => 'Mobile Settings',
        'menu_slug'   => 'mobile-settings',
        'capability'  => 'manage_options',
        'parent_slug' => 'main-settings'
    ]);

}

/**
 * ----------------------------------------------------------------
 * Add "Languages" custom post type and menu in CMS
 * ----------------------------------------------------------------
 */

add_action('init', function () {

    $labels = [
        'name'               => 'Languages',
        'singular_name'      => 'Language',
        'add_new_item'       => 'Add New Language',
        'edit_item'          => 'Edit Language',
        'new_item'           => 'New Language',
        'view_item'          => 'View Language',
        'search_items'       => 'Search Languages',
        'not_found'          => 'No languages found',
        'not_found_in_trash' => 'No languages found in Trash',
    ];

    register_post_type('language', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_menu'       => true,      
        'menu_position'      => 6,         
        'menu_icon'          => 'dashicons-translation',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'rewrite'            => ['slug' => 'languages', 'with_front' => false],
        'capability_type'    => 'post',
    ]);

});

/**
 * ----------------------------------------------------------------
 * Add "Languages" count to the WP CMS sidebar
 * ----------------------------------------------------------------
 */

add_filter('admin_menu', function() {

    global $menu;

    $cpt_slug = 'language';
    $count = wp_count_posts($cpt_slug)->publish;

    foreach ($menu as $key => $value) {
        if ($menu[$key][2] === "edit.php?post_type=$cpt_slug") {
            $menu[$key][0] .= " ($count)";
            break;
        }
    }

});

/**
 * ----------------------------------------------------------------
 * Add "Libraries" custom post type and menu in CMS
 * ----------------------------------------------------------------
 */

add_action('init', function () {

    $labels = [
        'name'               => 'Libraries',
        'singular_name'      => 'Library',
        'add_new_item'       => 'Add New Library',
        'edit_item'          => 'Edit Library',
        'new_item'           => 'New Library',
        'view_item'          => 'View Library',
        'search_items'       => 'Search Libraries',
        'not_found'          => 'No libraries found',
        'not_found_in_trash' => 'No libraries found in Trash',
    ];

    register_post_type('library', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'show_in_menu'       => true,
        'menu_position'      => 7,  
        'menu_icon'          => 'dashicons-index-card',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'rewrite'            => ['slug' => 'libraries', 'with_front' => false],
        'capability_type'    => 'post',
    ]);

});

/**
 * ----------------------------------------------------------------
 * Add "Libraries" count to the WP CMS sidebar
 * ----------------------------------------------------------------
 */

add_filter('admin_menu', function() {

    global $menu;

    $cpt_slug = 'library';
    $count = wp_count_posts($cpt_slug)->publish;

    foreach ($menu as $key => $value) {
        if ($menu[$key][2] === "edit.php?post_type=$cpt_slug") {
            $menu[$key][0] .= " ($count)";
            break;
        }
    }

});

/**
 * ----------------------------------------------------------------
 * Add "View Dashboard" and etc. in WP CMS for Libraries post
 * ----------------------------------------------------------------
 */

add_filter('post_row_actions', function($actions, $post) {

    if ($post->post_type === 'library') 
    {
    	// show link only if the library has a value for dashboard_iframe_source meta
		if (get_field('dashboard_iframe_source', $post->ID)) {
			$actions['view_dashboard'] = '<a href="' . get_permalink($post->ID).'dashboard' . '">View Dashboard</a>';
		}

		// show link only if the library has a value for trial_form meta
		if (get_field('trial_form', $post->ID)) {
        	$actions['view_trial'] = '<a href="' . get_permalink($post->ID).'trial' . '">View Trial Form</a>';
        }

		// show link only if the library has a value for competition_form meta
		if (get_field('competition_form', $post->ID)) {
        	$actions['competition_form'] = '<a href="' . get_permalink($post->ID).'competition' . '">View Competition Form</a>';
        }
    }

    return $actions;

}, 10, 2);

/**
 * ----------------------------------------------------------------
 * Add "Books" custom post type and menu in CMS
 * ----------------------------------------------------------------
 */

add_action('init', function () {

    $labels = [
        'name'               => 'Books',
        'singular_name'      => 'Book',
        'add_new_item'       => 'Add New Book',
        'edit_item'          => 'Edit Book',
        'new_item'           => 'New Book',
        'view_item'          => 'View Book',
        'search_items'       => 'Search Books',
        'not_found'          => 'No books found',
        'not_found_in_trash' => 'No books found in Trash',
    ];

    register_post_type('book', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'show_in_menu'       => true,
        'menu_position'      => 8,
        'menu_icon'          => 'dashicons-book',
        'supports'           => ['title', 'editor', 'thumbnail', 'comments'],
        'rewrite'            => ['slug' => 'books', 'with_front' => false],
        'capability_type'    => 'post',
    ]);

});

/**
 * ----------------------------------------------------------------
 * Add "Books" count to the WP CMS sidebar
 * ----------------------------------------------------------------
 */

add_filter('admin_menu', function() {

    global $menu;

    $cpt_slug = 'book';
    $count = wp_count_posts($cpt_slug)->publish;

    foreach ($menu as $key => $value) {
        if ($menu[$key][2] === "edit.php?post_type=$cpt_slug") {
            $menu[$key][0] .= " ($count)";
            break;
        }
    }

});

/**
 * ----------------------------------------------------------------
 * Add "Playlists" custom post type and menu in CMS
 * ----------------------------------------------------------------
 */

add_action('init', function() {
    $labels = [
        'name'                  => 'Playlists',
        'singular_name'         => 'Playlist',
        'menu_name'             => 'Playlists',
        'name_admin_bar'        => 'Playlist',
        'add_new'               => 'Add Playlist',
        'add_new_item'          => 'Add New Playlist',
        'edit_item'             => 'Edit Playlist',
        'new_item'              => 'New Playlist',
        'view_item'             => 'View Playlist',
        'search_items'          => 'Search Playlists',
        'not_found'             => 'No playlists found',
        'not_found_in_trash'    => 'No playlists found in Trash',
        'all_items'             => 'All Playlists',
    ];

    register_post_type('playlist', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'show_in_menu'       => true,
        'menu_position'      => 9,
        'menu_icon'          => 'dashicons-playlist-audio',
        'supports'           => ['title', 'editor', 'thumbnail', 'comments'],
        'rewrite'            => ['slug' => 'playlists', 'with_front' => false],
        'capability_type'    => 'post',
    ]);
});


/**
 * ----------------------------------------------------------------
 * Add "Playlists" count to the WP CMS sidebar
 * ----------------------------------------------------------------
 */

add_filter('admin_menu', function() {

    global $menu;

    $cpt_slug = 'playlist';
    $count = wp_count_posts($cpt_slug)->publish;

    foreach ($menu as $key => $value) {
        if ($menu[$key][2] === "edit.php?post_type=$cpt_slug") {
            $menu[$key][0] .= " ($count)";
            break;
        }
    }

});

/**
 * ----------------------------------------------------------------
 * Add "Story" custom post type and menu in CMS
 * ----------------------------------------------------------------
 */

add_action('init', function () {

    $labels = [
        'name'               => 'Stories',
        'singular_name'      => 'Story',
        'add_new_item'       => 'Add New Story',
        'edit_item'          => 'Edit Story',
        'new_item'           => 'New Story',
        'view_item'          => 'View Story',
        'search_items'       => 'Search Stories',
        'not_found'          => 'No stories found',
        'not_found_in_trash' => 'No stories found in Trash',
    ];

    register_post_type('story', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'show_in_menu'       => true,
        'menu_position'      => 10,
        'menu_icon'          => 'dashicons-excerpt-view',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'rewrite'            => ['slug' => 'stories', 'with_front' => false],
        'capability_type'    => 'post',
    ]);

});

/**
 * ----------------------------------------------------------------
 * Add "Story" count to the WP CMS sidebar
 * ----------------------------------------------------------------
 */

add_filter('admin_menu', function() {

    global $menu;

    $cpt_slug = 'story';
    $count = wp_count_posts($cpt_slug)->publish;

    foreach ($menu as $key => $value) {
        if ($menu[$key][2] === "edit.php?post_type=$cpt_slug") {
            $menu[$key][0] .= " ($count)";
            break;
        }
    }

});

/**
 * ----------------------------------------------------------------
 * Add "Activities" custom post type and menu in CMS
 * ----------------------------------------------------------------
 */

add_action('init', function () {

    $labels = [
        'name'               => 'Activities',
        'singular_name'      => 'Activity',
        'add_new_item'       => 'Add New Activity',
        'edit_item'          => 'Edit Activity',
        'new_item'           => 'New Activity',
        'view_item'          => 'View Activity',
        'search_items'       => 'Search Activities',
        'not_found'          => 'No activities found',
        'not_found_in_trash' => 'No activities found in Trash',
    ];

    register_post_type('activity', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'show_in_menu'       => true,
        'menu_position'      => 11,
        'menu_icon'          => 'dashicons-welcome-write-blog',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'rewrite'            => ['slug' => 'activities', 'with_front' => false],
        'capability_type'    => 'post',
    ]);

});

/**
 * ----------------------------------------------------------------
 * Add "Activities" count to the WP CMS sidebar
 * ----------------------------------------------------------------
 */

add_filter('admin_menu', function() {

    global $menu;

    $cpt_slug = 'activity';
    $count = wp_count_posts($cpt_slug)->publish;

    foreach ($menu as $key => $value) {
        if ($menu[$key][2] === "edit.php?post_type=$cpt_slug") {
            $menu[$key][0] .= " ($count)";
            break;
        }
    }

});

/**
 * ----------------------------------------------------------------
 * Add "Endpoints" custom post type and menu in CMS
 * ----------------------------------------------------------------
 */

add_action('init', function () {

    $labels = [
        'name'               => 'Endpoints',
        'singular_name'      => 'Endpoint',
        'add_new_item'       => 'Add New Endpoint',
        'edit_item'          => 'Edit Endpoint',
        'new_item'           => 'New Endpoint',
        'view_item'          => 'View Endpoint',
        'search_items'       => 'Search Endpoints',
        'not_found'          => 'No endpoints found',
        'not_found_in_trash' => 'No endpoints found in Trash',
    ];

    register_post_type('endpoint', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'show_in_menu'       => true,
        'menu_position'      => 12,
        'menu_icon'          => 'dashicons-rest-api',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'rewrite'            => ['slug' => 'endpoints', 'with_front' => false],
        'capability_type'    => 'post',
    ]);
});

/**
 * ----------------------------------------------------------------
 * Add "Endpoints" count to the WP CMS sidebar
 * ----------------------------------------------------------------
 */

add_filter('admin_menu', function() {

    global $menu;

    $cpt_slug = 'endpoint';
    $count = wp_count_posts($cpt_slug)->publish;

    foreach ($menu as $key => $value) {
        if ($menu[$key][2] === "edit.php?post_type=$cpt_slug") {
            $menu[$key][0] .= " ($count)";
            break;
        }
    }

});

/**
 * ----------------------------------------------------------------
 * Move "Media" from position 10 to 15
 * ----------------------------------------------------------------
 */

add_action('admin_menu', function () {

    global $menu;
    $menu[15] = $menu[10]; // Move Media from position 10 to 15
    unset($menu[10]);

}, 999);

/**
 * ----------------------------------------------------------------
 * Change "Posts" label to "Blog" in CMS
 * ----------------------------------------------------------------
 */

add_action('admin_menu', function() {

    global $menu;
    foreach ($menu as $key => $value) {
        if ($value[2] === 'edit.php') { // Posts menu slug
            $menu[$key][0] = 'Blog';   // rename it
            break;
        }
    }

});

/**
 * ----------------------------------------------------------------
 * Allow SVG upload and make sure to sanitize the SVG
 * ----------------------------------------------------------------
 */

add_filter('upload_mimes', function($mimes){

    $mimes['svg'] = 'image/svg+xml';
    return $mimes;

});

/**
 * ----------------------------------------------------------------
 * Color code ACF for flipbook vs. video type
 * ----------------------------------------------------------------
 */

add_action('acf/input/admin_head', function() {

    ?>
    <style>
        .bg-flipbook { background: #f2faff !important; }
        .bg-video { background: #f3fef6 !important; }
    </style>
    <?php

});

/**
 * ----------------------------------------------------------------
 * Disable WP admin bar on top of the page for "subscriber"
 * ----------------------------------------------------------------
 */

function l4k_HideAdminBarForSubscribers() {

    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        if (in_array( 'subscriber', (array) $user->roles)) {
            show_admin_bar(false);
        }
    }

}
add_action('after_setup_theme', 'l4k_HideAdminBarForSubscribers');

/**
 * ----------------------------------------------------------------
 * Change login logo and link in WP CMS
 * ----------------------------------------------------------------
 */

function l4k_customLoginLogo() {
    ?>
    <style type="text/css">
        #login h1 a {
            background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo-main.svg');
            background-size: contain;
            width: 100%;
            height: 80px;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'l4k_customLoginLogo');

function l4k_customLoginLogoURL() { return home_url(); }
add_filter('login_headerurl', 'l4k_customLoginLogoURL');

function l4k_customLoginLogoTitle() { return get_bloginfo('name'); }
add_filter('login_headertext', 'l4k_customLoginLogoTitle');
?>