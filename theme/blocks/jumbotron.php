<?php
  $name = 'jumbotron';
  $group_class = BLOCKNAME . '__jumbotron';

  $use_page_title = get_field( $name . '_use_page_title' );
  $title = $use_page_title === true ? get_the_title( My_Jam_Helpers::acf_get_post_id() ) : get_field( $name . '_title' );
  $content = get_field( $name . '_content' );

  $inner_content = '<div class="' . $group_class . '__inner">';
  $inner_content .= ( $title ) ? '<div class="' . $group_class . '__title">' . do_shortcode( $title ) . '</div>' : '';
  $inner_content .= ( $content ) ? '<div class="' . $group_class . '__content">' . apply_filters( 'the_content', $content ) . '</div>' : '';
  $inner_content .= '</div>';

  $my_jam_jumbotron = new My_Jam_Block;
  $my_jam_jumbotron->build( $block, ['name' => $name, 'content' => $inner_content, 'classes' => $group_class] );
?>