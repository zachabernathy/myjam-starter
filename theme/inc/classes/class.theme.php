<?php
class My_Jam {
  /**
   * Get theme color
   */
  public function myjam_get_theme_colors( $specific_color = null, $colors = null ) {
    $colors = ( $colors ) ? $colors : get_field( 'theme_colors', 'option' );
    $colors_only = [];

    for ( $i = 0; $i < count($colors); $i++ ) {
      $colors_only[] = $colors[$i]['color'];
    }

    return ( is_int( $specific_color ) ) ? $colors_only[$specific_color] : $colors_only;
  }

  /**
   * Display Date
   */
  public function myjam_get_date( $class = null, $more_classes = null, $icon = true, $pre_text = null, $post_id = null ) {
    $time = '<time datetime="' . get_the_date( 'c', $post_id ) . '" itemprop="datePublished">' . get_the_time( get_option( 'date_format' ), $post_id ) . '</time>';
    $time .= '<meta content="' . get_the_modified_date( 'c', $post_id ) . '" itemprop="dateModified">';

    $pre_text = ( $pre_text ) ? '<span class="' . $class . '__pretext">' . $pre_text . '</span>' : '';

    return '<div class="' . $class . '__date ' . $more_classes . '">' . $pre_text . ( ( $icon === true ) ? '<span class="label fas fa-calendar-day"></span>' : '' ) . ' ' . $time . '</div>';
  }

  /**
   * Display Footer Links
   */
  public function myjam_get_footer_link( $type ) {
    $output = '';

    $class = 'footer__link footer__link--' . $type;

    switch ( $type ) {
      case 'wordpress':
        $output .= '<span class="' . $class . '">' . _x( 'Powered by <a href="https://www.wordpress.org/" target="_blank" title="WordPress" class="footer__link__text" rel="noopener noreferrer">', 'footer_wordpress_text', THEMENAME ) . ' <span class="fab fa-wordpress-simple"></span></a></span>';
        break;

      case 'author':
        $output .= '<span class="' . $class . '">' . sprintf( _x('Theme by', 'footer_theme_by_text', THEMENAME ) ) . ' <a href="https://www.lifefromheretothere.com/" target="_blank" title="The Z Man" class="footer__link__text" rel="noopener noreferrer">' . _x( 'The Z Man', 'footer_author_text', THEMENAME ) . '</a></span>';
        break;

      case 'copyright':
        $output .= '<span class="' . $class . '">' . _x( '&copy; Copyright', 'footer_copyright_text', THEMENAME ) . ' ' . date_i18n( 'Y' ) . ' <a href="' . home_url( '/' ) . '" title="' . get_bloginfo( 'name', 'display' ) . '" class="footer__link__text" rel="noopener noreferrer">' . get_bloginfo( 'name', 'display' ) . '</a></span>';
        break;

      default:
        break;
    }

    return $output;
  }

  /**
   * Display Social Icons
   */
  public function social_icons() {
    if ( function_exists('get_field') ) {
      $name = 'social';
      $group_class = BLOCKNAME . '__social';

      $output = '';

      if( have_rows( 'social_links', 'option' ) ) :
        $output .= '<div class="' . $group_class . '">';

        while ( have_rows( 'social_links', 'option' ) ) : the_row();
          $url = get_sub_field( 'social_url' );
          $display = get_sub_field( 'social_display' );

          $url_parts = parse_url( $url );

          $text = str_replace( array( 'www.', '.com' ), '', $url_parts['host'] );

          $icon = $this->myjam_globals['social_font_override'] ? get_sub_field( 'social_custom_icon' ) : 'mjs-icon-' . $text;

          $color = get_sub_field( 'social_color' ) ? ' style="color:' . get_sub_field( 'social_color' ) . ';"' : '';

          $class[] = $group_class . '__link';

          foreach ( $display as $d ) {
            $class[] = $group_class . '--has-' . $d;
          };

          $output .= sprintf( '<a href="%s" class="%s"' . $color . ' rel="noopener noreferrer"><i class="%s"></i><span>%s</span></a>', esc_url( $url ), implode( ' ', $class ), $icon, ucwords( $text ) );
        endwhile;

        $output .= '</div>';
      endif;

      return $output;
    }
  }
}

function social_font() {
  if ( function_exists('get_field') ) {
    $my_plugin = new My_Jam();

    $font_url = get_field( 'social_font_override', 'option' ) ? get_field( 'social_font_override', 'option' ) : get_stylesheet_directory_uri() . '/fonts/font-social.css';

    wp_register_style( THEMENAME . '-font-social', $font_url, array(), null );

    if ( $my_plugin->social_icons() ) {
      wp_enqueue_style( THEMENAME . '-font-social' );
    }
  }
}
add_action( 'wp_enqueue_scripts', 'social_font' );