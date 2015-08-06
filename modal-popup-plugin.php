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
function fmp_color_metabox_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'fmp_color_nonce' );
    $fmp_stored_meta = get_post_meta( $post->ID );
  ?>
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
 * Adds a text meta box to the post editing screen
 */
function fmp_excerpt_metabox() {
    add_meta_box( 'fmp-excerpt-limit', __( 'Feature Excerpt Limit', 'fmp_tdm' ), 'fmp_excerpt_metabox_callback', 'feature', 'side', 'low' );
}
add_action( 'add_meta_boxes', 'fmp_excerpt_metabox' );

/**
 * Outputs the excerpt metabox
 */

function fmp_excerpt_metabox_callback( $post ) {
  ?>
  <p>
    <label for="fmp-excerpt-limit" class="fmp-row-title"><?php _e( 'Excerpt Length in Words', 'fmp_tdm' )?></label>
    <select name="fmp-excerpt-limit" id="fmp-excerpt-limit">
        <option value="select-one" <?php if ( isset ( $fmp_stored_meta['fmp-excerpt-limit'] ) ) selected( $fmp_stored_meta['fmp-excerpt-limit'][0], 'select-one' ); ?>><?php _e( 'Five', 'fmp_tdm' )?></option>';
        <option value="select-two" <?php if ( isset ( $fmp_stored_meta['fmp-excerpt-limit'] ) ) selected( $fmp_stored_meta['fmp-excerpt-limit'][0], 'select-two' ); ?>><?php _e( 'Ten', 'fmp_tdm' )?></option>';
        <option value="select-two" <?php if ( isset ( $fmp_stored_meta['fmp-excerpt-limit'] ) ) selected( $fmp_stored_meta['fmp-excerpt-limit'][0], 'select-three' ); ?>><?php _e( 'Fifteen', 'fmp_tdm' )?></option>';
        <option value="select-two" <?php if ( isset ( $fmp_stored_meta['fmp-excerpt-limit'] ) ) selected( $fmp_stored_meta['fmp-excerpt-limit'][0], 'select-four' ); ?>><?php _e( 'Twenty', 'fmp_tdm' )?></option>';
    </select>
</p>
<?php
}


/**
 * Saves the custom color picker meta input
 */
 function fmp_meta_color_save( $post_id ) {

     // Checks save status
     $is_autosave = wp_is_post_autosave( $post_id );
     $is_revision = wp_is_post_revision( $post_id );
     $is_valid_nonce = ( isset( $_POST[ 'fmp_color_nonce' ] ) && wp_verify_nonce( $_POST[ 'fmp_color_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

     // Exits script depending on save status
     if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
         return;
     }

     // Checks for input and sanitizes/saves if needed
     if( isset( $_POST[ 'meta-color' ] ) ) {
         update_post_meta( $post_id, 'meta-color', sanitize_text_field( $_POST[ 'meta-color' ] ) );
     }

 }
add_action( 'save_post', 'fmp_meta_color_save' );


?>
