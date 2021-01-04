<?php
// ACF: Theme Options
if( function_exists( 'acf_add_options_page' ) ) {
  acf_add_options_page( array(
    'page_title'  => 'Theme Settings',
    'menu_title'  => 'Theme Settings',
    'menu_slug'   => THEMENAME . '-theme-settings',
    'capability'  => 'manage_options',
    'redirect'    => false
  ) );
}

// ACF: Block Categories
if ( function_exists( 'block_categories' ) ) {
  function myjam_acf_block_category( $categories, $post ) {
    return array_merge(
      $categories,
      array(
        array(
          'slug' => THEMENAME,
          'title' => __( 'My Jam', THEMENAME ),
          'icon'  => 'awards'
        ),
      )
    );
  }
  add_filter( 'block_categories', 'myjam_acf_block_category', 10, 2);
}

// ACF: Blocks
if( function_exists( 'acf_register_block_type' ) ) {
  function myjam_acf_register_block_types() {
    // register a block
    acf_register_block_type(array(
      'name'              => THEMENAME . '-jumbotron',
      'title'             => __( 'My Jam: Jumbotron', THEMENAME ),
      'description'       => __( 'A custom jumbotron block.', THEMENAME ),
      'render_template'   => 'blocks/jumbotron.php',
      'category'          => THEMENAME,
      'icon'              => '<svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.5 13.5A.5.5 0 0 1 3 13h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zM2 2h12s2 0 2 2v6s0 2-2 2H2s-2 0-2-2V4s0-2 2-2z"/></svg>',
      'keywords'          => array( THEMENAME, 'jumbotron' ),
      'align'             => 'full',
      'supports'          => array( 'anchor' => true, 'align_text' => true, 'align' => array( 'wide', 'full' ) )
    ));
  }
  add_action( 'init', 'myjam_acf_register_block_types' );
}


// ACF: Add direction selection
function myjam_acf_load_direction_field_choices( $field ) {
  $field['choices'] = array();

  if ( $field['name'] == 'background_image_repeat' ) {
    $field['choices']['no-repeat'] = '<span class="field-choice-text">No Repeat</span>';
    $field['choices']['repeat-x'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93LWxlZnQtcmlnaHQiIHZpZXdCb3g9IjAgMCAxNiAxNiI+PHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMSAxMS41YS41LjUgMCAwMC41LjVoMTEuNzkzbC0zLjE0NyAzLjE0NmEuNS41IDAgMDAuNzA4LjcwOGw0LTRhLjUuNSAwIDAwMC0uNzA4bC00LTRhLjUuNSAwIDAwLS43MDguNzA4TDEzLjI5MyAxMUgxLjVhLjUuNSAwIDAwLS41LjV6bTE0LTdhLjUuNSAwIDAxLS41LjVIMi43MDdsMy4xNDcgMy4xNDZhLjUuNSAwIDExLS43MDguNzA4bC00LTRhLjUuNSAwIDAxMC0uNzA4bDQtNGEuNS41IDAgMTEuNzA4LjcwOEwyLjcwNyA0SDE0LjVhLjUuNSAwIDAxLjUuNXoiLz48L3N2Zz4=">';
    $field['choices']['repeat-y'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93LWRvd24tdXAiIHZpZXdCb3g9IjAgMCAxNiAxNiI+PHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMTEuNSAxNWEuNS41IDAgMDAuNS0uNVYyLjcwN2wzLjE0NiAzLjE0N2EuNS41IDAgMDAuNzA4LS43MDhsLTQtNGEuNS41IDAgMDAtLjcwOCAwbC00IDRhLjUuNSAwIDEwLjcwOC43MDhMMTEgMi43MDdWMTQuNWEuNS41IDAgMDAuNS41em0tNy0xNGEuNS41IDAgMDEuNS41djExLjc5M2wzLjE0Ni0zLjE0N2EuNS41IDAgMDEuNzA4LjcwOGwtNCA0YS41LjUgMCAwMS0uNzA4IDBsLTQtNGEuNS41IDAgMDEuNzA4LS43MDhMNCAxMy4yOTNWMS41YS41LjUgMCAwMS41LS41eiIvPjwvc3ZnPg==">';
    $field['choices']['repeat'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93cy1tb3ZlIiB2aWV3Qm94PSIwIDAgMTYgMTYiPjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTcuNjQ2LjE0NmEuNS41IDAgMDEuNzA4IDBsMiAyYS41LjUgMCAwMS0uNzA4LjcwOEw4LjUgMS43MDdWNS41YS41LjUgMCAwMS0xIDBWMS43MDdMNi4zNTQgMi44NTRhLjUuNSAwIDExLS43MDgtLjcwOGwyLTJ6TTggMTBhLjUuNSAwIDAxLjUuNXYzLjc5M2wxLjE0Ni0xLjE0N2EuNS41IDAgMDEuNzA4LjcwOGwtMiAyYS41LjUgMCAwMS0uNzA4IDBsLTItMmEuNS41IDAgMDEuNzA4LS43MDhMNy41IDE0LjI5M1YxMC41QS41LjUgMCAwMTggMTB6TS4xNDYgOC4zNTRhLjUuNSAwIDAxMC0uNzA4bDItMmEuNS41IDAgMTEuNzA4LjcwOEwxLjcwNyA3LjVINS41YS41LjUgMCAwMTAgMUgxLjcwN2wxLjE0NyAxLjE0NmEuNS41IDAgMDEtLjcwOC43MDhsLTItMnpNMTAgOGEuNS41IDAgMDEuNS0uNWgzLjc5M2wtMS4xNDctMS4xNDZhLjUuNSAwIDAxLjcwOC0uNzA4bDIgMmEuNS41IDAgMDEwIC43MDhsLTIgMmEuNS41IDAgMDEtLjcwOC0uNzA4TDE0LjI5MyA4LjVIMTAuNUEuNS41IDAgMDExMCA4eiIvPjwvc3ZnPg==">';

    $field['default_value'] = 'repeat-x';
  } else {
    $field['choices']['up-left'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93LXVwLWxlZnQiIHZpZXdCb3g9IjAgMCAxNiAxNiI+PHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMiAyLjVhLjUuNSAwIDAxLjUtLjVoNmEuNS41IDAgMDEwIDFIMy43MDdsMTAuMTQ3IDEwLjE0NmEuNS41IDAgMDEtLjcwOC43MDhMMyAzLjcwN1Y4LjVhLjUuNSAwIDAxLTEgMHYtNnoiLz48L3N2Zz4=">';
    $field['choices']['up'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93LXVwIiB2aWV3Qm94PSIwIDAgMTYgMTYiPjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTggMTVhLjUuNSAwIDAwLjUtLjVWMi43MDdsMy4xNDYgMy4xNDdhLjUuNSAwIDAwLjcwOC0uNzA4bC00LTRhLjUuNSAwIDAwLS43MDggMGwtNCA0YS41LjUgMCAxMC43MDguNzA4TDcuNSAyLjcwN1YxNC41YS41LjUgMCAwMC41LjV6Ii8+PC9zdmc+">';
    $field['choices']['up-right'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93LXVwLXJpZ2h0IiB2aWV3Qm94PSIwIDAgMTYgMTYiPjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTE0IDIuNWEuNS41IDAgMDAtLjUtLjVoLTZhLjUuNSAwIDAwMCAxaDQuNzkzTDIuMTQ2IDEzLjE0NmEuNS41IDAgMDAuNzA4LjcwOEwxMyAzLjcwN1Y4LjVhLjUuNSAwIDAwMSAwdi02eiIvPjwvc3ZnPg==">';
    $field['choices']['left'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93LWxlZnQiIHZpZXdCb3g9IjAgMCAxNiAxNiI+PHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMTUgOGEuNS41IDAgMDAtLjUtLjVIMi43MDdsMy4xNDctMy4xNDZhLjUuNSAwIDEwLS43MDgtLjcwOGwtNCA0YS41LjUgMCAwMDAgLjcwOGw0IDRhLjUuNSAwIDAwLjcwOC0uNzA4TDIuNzA3IDguNUgxNC41QS41LjUgMCAwMDE1IDh6Ii8+PC9zdmc+">';
    $field['choices']['circle'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWNpcmNsZSIgdmlld0JveD0iMCAwIDE2IDE2Ij48cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik04IDE1QTcgNyAwIDEwOCAxYTcgNyAwIDAwMCAxNHptMCAxQTggOCAwIDEwOCAwYTggOCAwIDAwMCAxNnoiLz48L3N2Zz4=">';
    $field['choices']['right'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93LXJpZ2h0IiB2aWV3Qm94PSIwIDAgMTYgMTYiPjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTEgOGEuNS41IDAgMDEuNS0uNWgxMS43OTNsLTMuMTQ3LTMuMTQ2YS41LjUgMCAwMS43MDgtLjcwOGw0IDRhLjUuNSAwIDAxMCAuNzA4bC00IDRhLjUuNSAwIDAxLS43MDgtLjcwOEwxMy4yOTMgOC41SDEuNUEuNS41IDAgMDExIDh6Ii8+PC9zdmc+">';
    $field['choices']['down-left'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93LWRvd24tbGVmdCIgdmlld0JveD0iMCAwIDE2IDE2Ij48cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0yIDEzLjVhLjUuNSAwIDAwLjUuNWg2YS41LjUgMCAwMDAtMUgzLjcwN0wxMy44NTQgMi44NTRhLjUuNSAwIDAwLS43MDgtLjcwOEwzIDEyLjI5M1Y3LjVhLjUuNSAwIDAwLTEgMHY2eiIvPjwvc3ZnPg==">';
    $field['choices']['down'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93LWRvd24iIHZpZXdCb3g9IjAgMCAxNiAxNiI+PHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNOCAxYS41LjUgMCAwMS41LjV2MTEuNzkzbDMuMTQ2LTMuMTQ3YS41LjUgMCAwMS43MDguNzA4bC00IDRhLjUuNSAwIDAxLS43MDggMGwtNC00YS41LjUgMCAwMS43MDgtLjcwOEw3LjUgMTMuMjkzVjEuNUEuNS41IDAgMDE4IDF6Ii8+PC9zdmc+">';
    $field['choices']['down-right'] = '<img width="16px" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpIGJpLWFycm93LWRvd24tcmlnaHQiIHZpZXdCb3g9IjAgMCAxNiAxNiI+PHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMTQgMTMuNWEuNS41IDAgMDEtLjUuNWgtNmEuNS41IDAgMDEwLTFoNC43OTNMMi4xNDYgMi44NTRhLjUuNSAwIDExLjcwOC0uNzA4TDEzIDEyLjI5M1Y3LjVhLjUuNSAwIDAxMSAwdjZ6Ii8+PC9zdmc+">';

    $field['default_value'] = $field['name'] == 'background_image_position' ? 'circle' : 'up-right';
  }

  return $field;
}
add_filter( 'acf/load_field/name=background_image_repeat', 'myjam_acf_load_direction_field_choices' );
add_filter( 'acf/load_field/name=background_image_position', 'myjam_acf_load_direction_field_choices' );
add_filter( 'acf/load_field/name=backround_gradient_direction', 'myjam_acf_load_direction_field_choices' );
add_filter( 'acf/load_field/name=overlay_gradient_direction', 'myjam_acf_load_direction_field_choices' );

// ACF: Add theme colors to checkbox list
function myjam_acf_load_theme_colors_field_choices( $field ) {
  $field['choices'] = array();

  $i = 0;
  foreach ( My_Jam::myjam_get_theme_colors() as $color ) {
    $field['choices'][$i] = '<span style="background:' . $color . '"></span>';
    $i++;
  }

  $field['choices']['custom'] = '<span class="custom">Custom</span>';

  return $field;
}
add_filter( 'acf/load_field/name=background_colors', 'myjam_acf_load_theme_colors_field_choices' );
add_filter( 'acf/load_field/name=overlay_colors', 'myjam_acf_load_theme_colors_field_choices' );

// ACF: Add theme colors to color picker
function myjam_acf_color_picker_choices() { ?>
  <script type="text/javascript">
    (function($) {
      acf.add_filter( 'color_picker_args', function( args, $field ){
        // add the hexadecimal codes here for the colors to appear as swatches
        args.palettes = <?php echo json_encode( My_Jam::myjam_get_theme_colors() ); ?>
        // return colors
        return args;
      });
    })(jQuery);
  </script>
<?php }
add_action( 'acf/input/admin_footer', 'myjam_acf_color_picker_choices' );

function myjam_acf_head_cleanup_choices( $field ) {
  $field['choices'] = array();

  $things_to_cleanup = [
    'feed_links_extra'                => 'Category Feeds',
    'feed_links'                      => 'Post and Comment Feeds',
    'rsd_link'                        => 'RSD Link',
    'wlwmanifest_link'                => 'Windows Live Writer',
    'parent_post_rel_link'            => 'Previous Link',
    'start_post_rel_link'             => 'Start Link',
    'adjacent_posts_rel_link_wp_head' => 'Links for Adjacent Posts',
    'wp_shortlink_wp_head'            => 'Shortlink',
    'rest_output_link_wp_head'        => 'WP REST Link',
    'print_emoji_detection_script'    => 'Emoji Support',
    'wp_generator'                    => 'WordPress Version',
    'css_version'                     => 'CSS Versions',
    'js_version'                      => 'JS Versions',
  ];

  foreach ( $things_to_cleanup as $key => $value ) {
    $field['choices'][$key] = $value;
  }

  return $field;
}
add_filter( 'acf/load_field/name=head_cleanup', 'myjam_acf_head_cleanup_choices' );