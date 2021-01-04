<?php
class My_Jam_Helpers {
  protected $filename;
  protected $filetype;

  /**
   * Cache busting asset loading
   */
  public function get_revision( $filename, $filetype ) {
    // Cache the decoded manifest so that we only read it in once.
    static $manifest = null;

    if ( null === $manifest ) {
      $manifest_path = get_stylesheet_directory() . '/rev-manifest.json';

      $manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];
    }

    // If the manifest contains the requested file, return the hashed name.
    if ( array_key_exists( $filename, $manifest ) ) {
      return '/' . $filetype . 's/' . $manifest[$filename];
    }

    // Assume the file has not been hashed when it was not foun within the manifest.
    return $filename;
  }

  /**
   * Remove all version numbers
   */
  public function remove_version( $src ) {
    if ( strpos( $src, 'ver=' ) ) {
      $src = remove_query_arg( 'ver', $src );
    }

    return $src;
  }

  /**
   * ACF: Get the Post ID
   */
  public function acf_get_post_id() {
    if ( is_admin() && function_exists( 'acf_maybe_get_POST' ) ) :
      return intval( acf_maybe_get_POST( 'post_id' ) );
    else :
      global $post;
      return $post->ID;
    endif;
  }
}