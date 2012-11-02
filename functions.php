<?php
/**
 * @package WordPress
 * @subpackage LESS_Wordpress
 *
 * Credits for most of this file go to Walker Hamiltons work on his Wordpress
 * theme html5-boilerplate-for-wordpress.
 * https://github.com/walker/html5-boilerplate-for-wordpress
 */

if(!function_exists("lwp_setup")) {
  function lwp_setup(){
    if(function_exists( "register_nav_menu")) {
      add_theme_support("menus");
      register_nav_menu("primary", __("Primary navigation", "lwp"));
      register_nav_menu("footer",  __("Footer navigation",  "lwp"));
    }

    // Widgetized Sidebar HTML5 Markup
    if ( function_exists("register_sidebar") ) {
      register_sidebar(array(
        "name"          => "Sidebar",
        "before_widget" => "<section class='widget'>",
        "after_widget"  => "</section>",
        "before_title"  => "<h2>",
        "after_title"   => "</h2>"
      ));
    }

    // Get language files for theme
    $locale = get_locale();
    $locale_file = TEMPLATEPATH . "/languages/$locale.php";
    if (is_readable( $locale_file ) )
      require_once( $locale_file );

    add_theme_support( "post-thumbnails" );
    load_theme_textdomain( "lwp", TEMPLATEPATH . "/languages" );
  }
}

if(!function_exists("lwp_init")) {
  function lwp_init(){
    remove_action( "wp_head", "wp_shortlink_wp_head" );                   // Shortlinks for articles
    remove_action( "wp_head", "wp_generator" );                           // "Generated by Wordpress"
    remove_action( "wp_head", "feed_links_extra", 3 );                    // Category Feeds
    remove_action( "wp_head", "feed_links", 2 );                          // Post and Comment Feeds
    remove_action( "wp_head", "rsd_link" );                               // EditURI link
    remove_action( "wp_head", "wlwmanifest_link" );                       // Windows Live Writer
    remove_action( "wp_head", "index_rel_link" );                         // index link
    remove_action( "wp_head", "parent_post_rel_link", 10, 0 );            // previous link
    remove_action( "wp_head", "start_post_rel_link", 10, 0 );             // start link
    remove_action( "wp_head", "adjacent_posts_rel_link_wp_head", 10, 0 ); // Links for Adjacent Posts

    if(!is_admin()) {
      wp_enqueue_script("jquery");
    }
  }
}
add_action( "after_setup_theme", "lwp_setup" );
add_action( "init", "lwp_init" );

// Add ?v=[last modified time] to a file url
if(!function_exists('versioned_resource')) {
  function versioned_resource($relative_url) {
    $file = dirname(__FILE__).$relative_url;
    $file_version = "";

    if(file_exists($file)) {
      $file_version = "?v=".filemtime($file);
    }

    return get_bloginfo('template_url').'/'.$relative_url.$file_version;
  }
}

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function lwp_excerpt_length( $length ) {
  return 40;
}
add_filter( 'excerpt_length', 'lwp_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function lwp_continue_reading_link() {
  return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading', 'lwp' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and lwp_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function lwp_auto_excerpt_more( $more ) {
  return ' &hellip;' . lwp_continue_reading_link();
}
add_filter( 'excerpt_more', 'lwp_auto_excerpt_more' );

