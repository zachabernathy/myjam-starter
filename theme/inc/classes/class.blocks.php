<?php
class My_Jam_Block {
  protected $block,
            $options,
            $echo;

  protected static $modprefix = BLOCKNAME_MOD;

  /**
   * Build Block
   */
  public function build( $block, $options, $echo = true ) {
      if ( function_exists('get_field') ) {
      $wrapper = ( $options['wrapper'] ) ? $options['wrapper'] : 'div';

      $id = ! empty( $block['anchor'] ) ? $block['anchor'] : $options['name'] . '-' . $block['id'];

      $classes = [];
      $classes[] = $options['classes'];
      $classes[] = $this->$modprefix . 'align-' . $block['align'];
      $classes[] = $this->$modprefix . 'text-' . $block['align_text'];
      $classes[] = $this->modifier_classes( $options['name'] );
      $classes[] = $this->spacing_classes( $options['padding'], $options['margin'] );
      $classes[] = $block['className'];

      $classes = array_filter( $classes );

      $styles = '';

      $combine_styles_callback = function ( $value, $key ) use ( &$styles ) {
        $styles .= $key . ":" . $value . ";";
      };

      if ( ! empty( $options['styles'] ) ) {
        array_walk_recursive( $options['styles'], $combine_styles_callback );
      }

      $styles .= $this->background_overlay('background');

      $block_output = '<' . $wrapper . ' id="' . esc_attr( $id ) . '" class="' . esc_attr( implode( ' ', $classes ) ) . '"' . ( $styles ? ' style="' . esc_attr( $styles ) . '"' : '' ) . '>';

      $block_output .= $options['content'];

      $block_output .= $this->background_overlay('overlay');

      $block_output .= '</' . $wrapper . '>';

      if ( $echo === true ) {
        echo $block_output;
      } else {
        return $block_output;
      }
    }
  }

  /**
   * Background & Overlay
   */
  private function background_overlay( $type ) {
    $background = '';

    $image = get_field( $type . '_image' );
    $image_position = get_field( $type . '_image_position' );
    $image_repeat = get_field( $type . '_image_repeat' );
    $image_fill = get_field( $type . '_image_fill' );
    $colors = get_field( $type . '_colors' );
    $colors_custom = get_field( $type . '_colors_custom' );
    $gradient_direction = get_field( $type . '_gradient_direction' );
    $opacity = get_field( $type . '_opacity' );

    $use_custom_only = $colors[0] == 'custom' ? true : false;
    $include_custom = in_array( 'custom', $colors ) ? true : false;

    if ( ( $remove_custom_from_array = array_search('custom', $colors) ) !== false ) {
      unset( $colors[$remove_custom_from_array] );
    }

    $number_of_theme_colors = ! $use_custom_only ? count($colors) : 0;
    $single_color = ! $use_custom_only ? ( ! empty($colors) ? My_Jam::myjam_get_theme_colors( intval($colors[0]) ) : '' ) : '';
    $colors_array = [];
    $custom_colors_array = [];

    if ( $include_custom && $colors_custom !== false ) {
      $number_of_custom_colors = $number_of_theme_colors == 0 ? count($colors_custom) : $number_of_theme_colors;
      $custom_colors_array = My_Jam::myjam_get_theme_colors( null, $colors_custom );
      $single_color = $number_of_custom_colors == 1 ? My_Jam::myjam_get_theme_colors( 0, $colors_custom ) : $single_color;
    }

    $total_number_of_colors = $number_of_theme_colors + $number_of_custom_colors;

    if ( $image ) {
      $image_info = wp_get_attachment_image_src( $image, 'full' );

      $background = 'background-image:url(\'' . $image_info[0] . '\');';

      $background .= 'background-position:' . $this->direction( $image_position )['position'] . ';';

      $background .= 'background-repeat:' . $image_repeat . ';';

      if ( $image_fill != 'nochange' ) {
        $background .= 'background-size:' . $image_fill . ';';
      }

      $background .= $single_color ? 'background-color:' . $single_color . ';' : '';
    } else if ( ( ! empty($colors) || $colors_custom !== false ) && $total_number_of_colors > 1 ) {
      if ( empty( $colors_array ) ) {
        foreach ( $colors as $c ) {
          $colors_array[] = My_Jam::myjam_get_theme_colors( intval($c) );
        }
      }

      $colors_array = array_merge( $colors_array, $custom_colors_array );

      $gradient_setting = $this->direction( $gradient_direction );

      $gradient = ( $gradient_setting['gradient'] ) ?: 'linear-gradient(' . $gradient_setting['degree'] . 'deg, ';

      $background = 'background-image:' . $gradient . implode( $colors_array, ', ' ) . ');';
    } else if ( $total_number_of_colors == 1 ) {
      $background = 'background-color:' . $single_color . ';';
    }

    if ( $type == 'overlay' ) {
      return $background ? '<div class="' . BLOCKNAME . '__overlay" style="' . $background . 'opacity:' . ( $opacity < 100 ? '0.' . $opacity : '1' ) . ';"></div>' : '';
    } else {
      return $background;
    }
  }

  /**
   * Image & Gradient Direction
   */
  private function direction( $field ) {
    switch ( $field ) {
      case 'up':
        $output['degree'] = 0;
        $output['position'] = 'top center';
        break;
      case 'up-right':
        $output['degree'] = 45;
        $output['position'] = 'top right';
        break;
      case 'right':
        $output['degree'] = 90;
        $output['position'] = 'center right';
        break;
      case 'down-right':
        $output['degree'] = 135;
        $output['position'] = 'bottom right';
        break;
      case 'down':
        $output['degree'] = 180;
        $output['position'] = 'bottom center';
        break;
      case 'down-left':
        $output['degree'] = 225;
        $output['position'] = 'bottom left';
        break;
      case 'left':
        $output['degree'] = 270;
        $output['position'] = 'center left';
        break;
      case 'up-left':
        $output['degree'] = 315;
        $output['position'] = 'top left';
        break;
      case 'circle':
        $output['gradient'] = 'radial-gradient(ellipse at center, ';
        $output['position'] = 'center center';
        break;

      default:
        $output['degree'] = 45;
        $output['position'] = 'center center';
        break;
    }

    return $output;
  }

  /**
   * Mod Classes
   */
  private function modifier_classes( $name, $fields = null ) {
    $name = $name . '_';

    $modifier_classes = array();

    $fields = array(
      $name . 'fullscreen'
    );

    foreach ( $fields as $field ) :
      if ( get_field( $field ) === true || get_field( $field ) ) :
        $modifier_classes[] .= $this->$modprefix . str_replace( $name, '', $field );
      endif;
    endforeach;

    return implode( ' ', $modifier_classes );
  }

  /**
   * Spacing Class
   */
  private function spacing_classes( $default_padding = null, $default_margin = null ) {
    $types = array( 'padding' => 'p', 'margin' => 'm' );

    $spacing = array();

    foreach ( $types as $type => $char ) {
      $prefix = $type . '_';
      $char = $char;

      $top = (int)get_field( $prefix . 'top' );
      $right = (int)get_field( $prefix . 'right' );
      $bottom = (int)get_field( $prefix . 'bottom' );
      $left = (int)get_field( $prefix . 'left' );

      if ( ( $top === $right ) && ( $top === $left ) && ( $top === $bottom ) && ( $top > -1 ) ) :
        $spacing[] = $char . '-' . $top;
      else :
        if ( $top === $bottom && ( $top > -1 && $bottom > -1 ) ) :
          $spacing[] = $char . 'y-' . $top;
        elseif ( $top > -1 || $bottom > -1 ) :
          $spacing[] = ( $top > -1 ) ? $char . 't-' . $top : '';
          $spacing[] = ( $bottom > -1 ) ? $char . 'b-' . $bottom : '';
        endif;

        if ( $right === $left && ( $right > -1 && $left > -1 ) ) :
          $spacing[] = $char . 'x-' . $right;
        elseif ( $right > -1 || $left > -1 ) :
          $spacing[] = ( $right > -1 ) ? $char . 'r-' . $right : '';
          $spacing[] = ( $left > -1 ) ? $char . 'l-' . $left : '';
        endif;
      endif;
    }

    $spacing = array_filter( $spacing );

    return implode( ' ', $spacing );
  }
}