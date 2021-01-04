<?php
/**
 * My Jam functions and definitions
 *
 * @package myjam
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$functions_dir = 'inc';
$classes_dir = 'inc/classes';

$inc_files_array = [
  'setup',
  'acf',
];

$class_files_array = [
  'helpers',
  'blocks',
  'theme',
];

// Class files to include
foreach ( $class_files_array as $file ) {
  $myjam_php_files[] = sprintf( '%s/class.%s', $classes_dir, $file );
}

// Files to include
foreach ( $inc_files_array as $file ) {
  $myjam_php_files[] = sprintf( '%s/%s', $functions_dir, $file );
}

// Include files.
foreach ( $myjam_php_files as $file ) {
  require_once get_template_directory() . '/' . $file . '.php';
}