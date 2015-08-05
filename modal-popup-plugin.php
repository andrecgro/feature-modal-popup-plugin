<?php
/*
Plugin Name: Feature with Modal Popup
Plugin URI:
Description: A plugin to help you create a beautiful section of features with modal popup;
Version: 1.0
Author: André Rocha
Author URI: https://github.com/andrecgro
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Paths: /languages
*/

/*
 *     Copyright 2015 André Rocha <andre.cgrocha@gmail.com>
 *
 *     Feature with Modal Popup is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 2 of the License, or
 *     any later version.
 *
 *     Feature with Modal Popup is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Feature with Modal Popup. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */
 ?>


<?php

/**
 * Adds the plugin post type
 */
function fmp_create_post_feature() {
  register_post_type( 'feature',
      array(
          'labels' => array(
              'name' => 'Features' ,
              'singular_name' => 'Feature',
              'edit_item' => __( 'Edit' ) . ' Feature',
              'add_new' => __( 'Add' ) . ' nova',
              'menu_name' => 'Feature with Modal Popup',
              'all_items' => 'Features'
          ),
          'public' => true,
      )
  );
}

add_action( 'init', 'fmp_create_post_feature' );

/**
 * Adds a meta box to the post editing screen
 */
function fmp_color_metabox() {
    add_meta_box( 'fmp_color_picker', __( 'Border Color', 'fmp_tdm' ), 'fmp_color_metabox_callback', 'feature' );
}
add_action( 'add_meta_boxes', 'fmp_color_metabox' );

/**
 * Include the file to outputs the content of the color meta box
 */
function fmp_color_metabox_callback( $post ) {
    echo 'This is a meta box';
}

?>
