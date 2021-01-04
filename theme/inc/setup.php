<?php
/**
 * GLOBAL CONSTANTS
 */

define( 'THEMENAME', wp_get_theme()->template );                        // Theme name
define( 'BLOCKNAME', 'block' );                                         // Block name
define( 'BLOCKNAME_MOD', BLOCKNAME . '--' );                                         // Block name
define( 'HEAD_CLEANUP', get_option('options_head_cleanup') ? get_option('options_head_cleanup') : [] ); // Head cleanup array
define( 'LOAD_JQUERY', ( function_exists('get_field') ? get_field( 'load_jquery', 'option' ) : false ) );          // jQuery custom version
define( 'JQUERY_VER', ( function_exists('get_field') ? get_field( 'custom_jquery_version', 'option' ) : '3.5.1' ) ); // jQuery custom version

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function myjam_theme_support() {
  // Add default posts and comments RSS feed links to head.
  add_theme_support( 'automatic-feed-links' );

  // Set content-width.
  global $content_width;
  if ( ! isset( $content_width ) ) {
    $content_width = 1140;
  }

  /**
   * Enable support for Post Thumbnails on posts and pages.
   *
   * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
   */
  add_theme_support( 'post-thumbnails' );

  /**
   * Set up the WordPress Theme logo feature
   */
  add_theme_support(
    'custom-logo',
    array(
      'height'      => '',
      'width'       => '',
      'flex-height' => true,
      'flex-width'  => true,
      'header-text' => array( 'site-title', 'site-description' ),
    )
  );

  /**
   * Let WordPress manage the document title.
   * By adding theme support, we declare that this theme does not use a
   * hard-coded <title> tag in the document head, and expect WordPress to
   * provide it for us.
   */
  add_theme_support( 'title-tag' );

  /**
   * Switch default core markup for search form, comment form, and comments
   * to output valid HTML5.
   */
  add_theme_support(
    'html5',
    array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'script',
      'style',
    )
  );

  /**
   * Make theme available for translation.
   * Translations can be filed in the /languages/ directory.
   */
  load_theme_textdomain( THEMENAME, get_template_directory() . '/languages' );

  // Add support for full and wide align images.
  add_theme_support( 'align-wide' );

  // Add theme support for selective refresh for widgets.
  add_theme_support( 'customize-selective-refresh-widgets' );

  // Editor styles
  add_theme_support( 'editor-styles' );

  // Cleanup the head code
  myjam_head_cleanup();
}
add_action( 'after_setup_theme', 'myjam_theme_support' );

function myjam_head_cleanup() {
  // Category feeds
  if ( in_array( 'feed_links_extra', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'feed_links_extra', 3 );
  }
  // Post and comment feeds
  if ( in_array( 'feed_links', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'feed_links', 2 );
  }
  // RSD link
  if ( in_array( 'rsd_link', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'rsd_link' );
  }
  // Windows live writer
  if ( in_array( 'wlwmanifest_link', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'wlwmanifest_link' );
  }
  // Previous link
  if ( in_array( 'parent_post_rel_link', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
  }
  // Start link
  if ( in_array( 'start_post_rel_link', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  }
  // Links for adjacent posts
  if ( in_array( 'adjacent_posts_rel_link_wp_head', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
  }
  // WP version
  if ( in_array( 'wp_generator', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'wp_generator' );
  }
  // Remove emoji support
  if ( in_array( 'print_emoji_detection_script', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
  }
  // Remove WP REST link
  if ( in_array( 'rest_output_link_wp_head', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
  }
  // Remove shortlink
  if ( in_array( 'wp_shortlink_wp_head', HEAD_CLEANUP ) ) {
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
  }
  // Remove version from css
  if ( in_array( 'css_version', HEAD_CLEANUP ) ) {
    add_filter( 'style_loader_src', 'My_Jam_Helpers::remove_version', 9999 );
  }
  // Remove version from scripts
  if ( in_array( 'js_version', HEAD_CLEANUP ) ) {
    add_filter( 'script_loader_src', 'My_Jam_Helpers::remove_version', 9999 );
  }
}

/**
 * Enqueue scripts and styles.
 */
function myjam_theme_scripts() {
  // Load jQuery
  if ( LOAD_JQUERY === true ) {
    wp_enqueue_script( 'jquery' );
  }

  // Main script and style
  wp_enqueue_style( THEMENAME . '-style', get_stylesheet_directory_uri() . My_Jam_Helpers::get_revision( THEMENAME . '.css', 'style' ), array(), null );
  wp_enqueue_script( THEMENAME . '-script', get_stylesheet_directory_uri() . My_Jam_Helpers::get_revision( THEMENAME . '.js', 'script' ), array(), null, true );

  // Comments
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'myjam_theme_scripts' );

// Editor styles
function myjam_add_editor_styles() {
  add_editor_style();
}
add_action( 'init', 'myjam_add_editor_styles' );

// Admin styles
function myjam_admin_style() {
  wp_enqueue_style( THEMENAME . '-admin-style', get_template_directory_uri() . '/admin-style.css');
}
add_action( 'admin_enqueue_scripts', 'myjam_admin_style' );

// Use jQuery Google API
function myjam_jquery() {
  if ( ! is_admin() && JQUERY_VER && LOAD_JQUERY === true ) {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/' . JQUERY_VER . '/jquery.min.js', false, JQUERY_VER );
    wp_enqueue_script( 'jquery' );
  }
}
add_action( 'init', 'myjam_jquery' );