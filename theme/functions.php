<?php
define('THEMENAME', wp_get_theme()->template);

/**
 * Enqueue scripts and styles.
 */
function __theme_scripts() {
  wp_enqueue_style( THEMENAME . '-style', get_stylesheet_directory_uri() . get_revision( THEMENAME . '.css', 'style' ), array(), null );
  wp_enqueue_script( THEMENAME . '-script', get_stylesheet_directory_uri() . get_revision( THEMENAME . '.js', 'script' ), array(), null, true );
}
add_action( 'wp_enqueue_scripts', '__theme_scripts' );

/**
 * Cache busting asset loading
 */
function get_revision( $filename, $type ) {
  // Cache the decoded manifest so that we only read it in once.
  static $manifest = null;
  if ( null === $manifest ) {
    $manifest_path = get_stylesheet_directory() . '/rev-manifest.json';
    $manifest = file_exists( $manifest_path )
      ? json_decode( file_get_contents( $manifest_path ), true )
      : [];
  }

  // If the manifest contains the requested file, return the hashed name.
  if ( array_key_exists( $filename, $manifest ) ) {
    return '/' . $type . 's/' . $manifest[ $filename ];
  }

  // Assume the file has not been hashed when it was not foun within the
  // manifest.
  return $filename;
}