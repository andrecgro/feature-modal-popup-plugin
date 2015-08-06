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
/*
* Keeping the plugin safe and not allowing direct access to it's files
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
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
              'add_new_item' => __('Add').' nova Feature',
              'menu_name' => 'Feature with Modal Popup',
              'all_items' => 'Features'
          ),
          'public' => true,
          'menu_icon' => 'dashicons-desktop',
          'supports' => array(
              'title',
              'editor',
              'thumbnail'
           ),

      )
  );
}

add_action( 'init', 'fmp_create_post_feature' );

/**
 * Adds a color pickermeta box to the post editing screen
 */
function fmp_color_metabox() {
    add_meta_box( 'fmp_color_picker', __( 'Feature Icon Border Color', 'fmp_tdm' ), 'fmp_color_metabox_callback', 'feature', 'side', 'low' );
}
add_action( 'add_meta_boxes', 'fmp_color_metabox' );

/**
 * Outputs the content of the color meta box
 */
function fmp_color_metabox_callback( $post ) {?>
  <p>
      <label for="meta-color" class="fmp-row-title"><?php _e( 'Color Picker', 'fmp_tdm' )?></label>
      <input name="meta-color" type="text" value="<?php if ( isset ( $fmp_stored_meta['meta-color'] ) ) echo $fmp_stored_meta['meta-color'][0]; ?>" class="meta-color" />
  </p>
<?php
}

/**
 * Loads the color picker javascript
 */
function fmp_color_enqueue() {
    global $typenow;
    if( $typenow == 'feature' ) {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'metabox-color-js', plugin_dir_url( __FILE__ ) . 'js/metabox-color.js', array( 'wp-color-picker' ) );
    }
}
add_action( 'admin_enqueue_scripts', 'fmp_color_enqueue' );

/**
 * Removing options to set the color picker position
 */
 add_action( 'add_meta_boxes', 'my_remove_meta_boxes', 0 );
function my_remove_meta_boxes(){
	global $wp_meta_boxes;
	unset( $wp_meta_boxes['post']['side']['core']['tagsdiv-post_tag'] );
	add_meta_box( 'tagsdiv-post_tag', 'Example title', 'post_tags_meta_box', 'post', 'normal', 'core', array( 'taxonomy' => 'post_tag' ));
	//print '<pre>';print_r( $wp_meta_boxes['post'] );print '<pre>';
}


/**
 * Adds a text meta box to the post editing screen
 */
function fmp_excerpt_metabox() {
    add_meta_box( 'fmp_excerpt_limit', __( 'Feature Excerpt Limit', 'fmp_tdm' ), 'fmp_excerpt_metabox_callback', 'feature', 'side', 'low' );
}
add_action( 'add_meta_boxes', 'fmp_excerpt_metabox' );

/**
 * Outputs the excerpt metabox
 */

function fmp_excerpt_metabox_callback( $post ) {?>
  <p>
    <label for="meta-select" class="fmp-row-title"><?php _e( 'Excerpt Length in Words', 'fmp_tdm' )?></label>
    <select name="meta-select" id="meta-select">
        <option value="select-one" <?php if ( isset ( $fmp_stored_meta['meta-select'] ) ) selected( $fmp_stored_meta['meta-select'][0], 'select-one' ); ?>><?php _e( 'Five', 'fmp_tdm' )?></option>';
        <option value="select-two" <?php if ( isset ( $fmp_stored_meta['meta-select'] ) ) selected( $fmp_stored_meta['meta-select'][0], 'select-two' ); ?>><?php _e( 'Ten', 'fmp_tdm' )?></option>';
        <option value="select-two" <?php if ( isset ( $fmp_stored_meta['meta-select'] ) ) selected( $fmp_stored_meta['meta-select'][0], 'select-three' ); ?>><?php _e( 'Fifteen', 'fmp_tdm' )?></option>';
        <option value="select-two" <?php if ( isset ( $fmp_stored_meta['meta-select'] ) ) selected( $fmp_stored_meta['meta-select'][0], 'select-four' ); ?>><?php _e( 'Twenty', 'fmp_tdm' )?></option>';
    </select>
</p>
<?php
}


?>
