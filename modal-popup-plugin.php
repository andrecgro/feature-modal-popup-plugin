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

          $labels = array(
                    'name'          => 'Features' ,
                    'singular_name' => 'Feature',
                    'edit_item'     => __( 'Edit' ) . ' Feature',
                    'add_new'       => __( 'Add' ) . ' nova',
                    'add_new_item'  => __('Add').' nova Feature',
                    'menu_name'     => 'Feature with Modal Popup',
                    'all_items'     => 'Features',
                    'rewrite'       => array( 'slug' => 'features' ),
          );
          $args = array(
                    'has_archive'   => true,
                    'public'        => true,
                    'menu_icon'     => 'dashicons-desktop',
                    'supports'      => array(
                                              'title',
                                              'editor',
                                              'thumbnail'
                                            ),
           );



  register_post_type('feature',$args);

}

add_action( 'init', 'fmp_create_post_feature' );

/**
 * Adding Flush Rules just when the plugin is activated
 */
 function fmp_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry,
    // when you add a post of this CPT.
    fmp_create_post_feature();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'fmp_rewrite_flush' );

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
    $fmp_color_stored_meta = get_post_meta( $post->ID );
  ?>
  <p>
      <label for="meta-color" class="fmp-row-title"><?php _e( 'Color Picker', 'fmp_tdm' )?></label>
      <input name="meta-color" type="text" value="<?php if ( isset ( $fmp_color_stored_meta['meta-color'] ) ) echo $fmp_color_stored_meta['meta-color'][0]; ?>" class="meta-color" />
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
    wp_nonce_field( basename( __FILE__ ), 'fmp_limit_nonce' );
    $fmp_excerpt_stored_meta = get_post_meta( $post->ID );
  ?>
  <p>
    <label for="fmp-excerpt-limit" class="fmp-row-title"><?php _e( 'Excerpt Length in Words', 'fmp_tdm' )?></label>
    <select name="fmp-excerpt-limit" id="fmp-excerpt-limit">
        <option value="select-five" <?php if ( isset ( $fmp_excerpt_stored_meta['fmp-excerpt-limit'] ) ) selected( $fmp_excerpt_stored_meta['fmp-excerpt-limit'][0], 'select-five' ); ?>><?php _e( '5', 'fmp_tdm' )?></option>';
        <option value="select-ten" <?php if ( isset ( $fmp_excerpt_stored_meta['fmp-excerpt-limit'] ) ) selected( $fmp_excerpt_stored_meta['fmp-excerpt-limit'][0], 'select-ten' ); ?>><?php _e( '10', 'fmp_tdm' )?></option>';
        <option value="select-fifteen" <?php if ( isset ( $fmp_excerpt_stored_meta['fmp-excerpt-limit'] ) ) selected( $fmp_excerpt_stored_meta['fmp-excerpt-limit'][0], 'select-fifteen' ); ?>><?php _e( '15', 'fmp_tdm' )?></option>';
        <option value="select-twenty" <?php if ( isset ( $fmp_excerpt_stored_meta['fmp-excerpt-limit'] ) ) selected( $fmp_excerpt_stored_meta['fmp-excerpt-limit'][0], 'select-twenty' ); ?>><?php _e( '20', 'fmp_tdm' )?></option>';
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

/**
 * Saves the custom meta input
 */
function fmp_limit_excerpt_save( $post_id ) {

    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'fmp_limit_nonce' ] ) && wp_verify_nonce( $_POST[ 'fmp_limit_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'fmp-excerpt-limit' ] ) ) {
        update_post_meta( $post_id, 'fmp-excerpt-limit', sanitize_text_field( $_POST[ 'fmp-excerpt-limit' ] ) );
    }

}
add_action( 'save_post', 'fmp_limit_excerpt_save' );
/**
 * Echoes first image from a post
 */

function fmp_echo_first_image( $postID ) {
	$args = array(
		'numberposts' => 1,
		'order' => 'ASC',
		'post_mime_type' => 'image',
		'post_parent' => $postID,
		'post_status' => null,
		'post_type' => 'attachment',
	);

	$attachments = get_children( $args );

	if ( $attachments ) {
		foreach ( $attachments as $attachment ) {
			$image_attributes = wp_get_attachment_image_src( $attachment->ID, 'full' );

			echo '<img class="img-responsive"src="' . wp_get_attachment_thumb_url( $attachment->ID ) . '" class="current" width="'.$image_attributes[1].'" height="auto ">';
		}
	}
}
add_action( 'save_post', 'fmp_echo_first_image' );

/**
 * Adding bootstrap
 */

 add_action('wp_head','head_code');

function head_code()
{

$output = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>';
$output .= '<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>';
$output .= '<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">';


echo $output;

}

// force use of templates from plugin folder
function fmp_force_template( $template )
{
    if( is_archive( 'feature' ) ) {
        $template = WP_PLUGIN_DIR .'/'. plugin_basename( dirname(__FILE__) ) .'/archive-feature.php';
	}

	if( is_singular( 'feature' ) ) {
        $template = WP_PLUGIN_DIR .'/'. plugin_basename( dirname(__FILE__) ) .'/single-feature.php';
	}

    return $template;
}
add_filter( 'template_include', 'fmp_force_template' );

/**
 * enqueue scripts and styles
 */
function fmp_enqueues() {
	wp_enqueue_style( 'style-name', plugin_dir_url(__FILE__) . '/css/style.css' );
}

add_action( 'wp_enqueue_scripts', 'fmp_enqueues' );

/**
 * Adding shortcode to call a loop showing all posts(archive-feature.php)
 */
 function fmp_shortcode(){?>
    <h1 class="text-center"><?php echo get_the_title(); ?></h1>
     <div class="row">
       <div class="col-md-12 col-sm-12 col-xs-12">

         <?php
         $loop = new WP_Query( array( 'post_type' => 'feature', 'posts_per_page' => '4') );
          if ( $loop -> have_posts() ) : while ( $loop -> have_posts() ) : $loop->the_post(); ?>


             <div class="feature col-md-3 col-sm-6 col-xs-12 to-click"  >
               <?php


                     $color = get_post_meta( get_the_ID(), 'meta-color', true );
                     //Adjusting the value of fmp-excerpt-limit
                     $limit =  get_post_meta( get_the_ID(), 'fmp-excerpt-limit', true );
                     switch ($limit) {
                       case 'select-five':
                         $limit = 5;
                         break;
                       case 'select-ten':
                         $limit = 10;
                         break;
                       case 'select-fifteen':
                         $limit = 15;
                         break;
                       case 'select-twenty':
                         $limit = 20;
                         break;
                       default:
                         $limit = 15;
                         break;
                     }
               ?>


                   <div class="fmp-circle center-block on-click add-color" data-id="<?php the_ID()?>" data-color="<?php echo $color ?>">

                     <!-- Getting featured image to make the icon -->
                     <?php if (has_post_thumbnail( get_the_ID() ) ): ?>
                     <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ); ?>
                     <i id="fmp-icon" style="background: url('<?php echo $image[0]; ?>') no-repeat center"></i>
                     <?php endif; ?>

                   </div>

                     <h3 id="feature-title" class="text-uppercase text-center">
                     <?php the_title(  ); ?>
                     </h3>
                     <div class="title-container" style="border-color:<?php print $color; ?>">
                     </div>

                     <p class="text-center" id="feature-text">
                   <?php
                         $content =  get_the_content();
                         $trimmed = wp_trim_words( $content, $limit, ('<br><br><a id="read-more"><p class="text-center other-on-click" data-test="'.get_the_ID().'">Read more</p></a>') );
                         print $trimmed;
                    ?>
                 </p>
             </div>

               <!-- MODAL POPUP-->
           <div  class="modal modal-feature" id="fmpModal-<?php the_ID()?>"  tabindex="-1" role="dialog" aria-labelledby="featureModalLabel-1" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&times;</button>
                          <h1 class="text-center"> <?php the_title()?> </h1>
                      </div>
                      <div class="modal-body">

                          <div class="row">
                            <div class="col-md-4 col-sm-12 col-xs-12 modal-img">
                              <?php fmp_echo_first_image(get_the_ID()); ?>
                            </div>

                          <div class="content col-md-8 col-sm-12 col-xs-12">
                                  <?php
                                  ob_start();
                                  the_content();
                                  $postOutput = preg_replace('/<img[^>]+./','', ob_get_contents());
                                  ob_end_clean();
                                  echo $postOutput;
                               ?>

                              </div><!--//content-->
                          </div><!--//row-->
                      </div><!--//modal-body-->
                  </div><!--//modal-content-->
              </div><!--//modal-dialog-->
           </div><!--//modal-->


         <?php endwhile; else : ?>
         	<p><?php _e( 'Sorry, no feature registered here.' ); ?></p><!--END OF THE LOOP-->
         <?php endif; ?>

       </div><!--//col-md-12 col-sm-12 col-xs-12-->
     </div><!--//row-->
   </div><!--//container-->

   <script>

   //Functions to call the modal

   $(document).ready(function(){
     //Calling modal from 'on-click' class
     $('.on-click').click(function(){
       var fieldData = $(this).data('id');
       $('#fmpModal-' + fieldData).modal('show');
     });
   //Calling modal from 'on-click' class - ('Read More' button)
     $('.other-on-click').click(function(){
       var fieldTest = $(this).data('test');
       $('#fmpModal-' + fieldTest).modal('show');
     });
     //Adding color to border and circle elements

     $('.add-color').mouseover(function(){
       var dataColor = $(this).data('color');
         $(this).css('border-color',dataColor);
     });
     $('.add-color').mouseout(function(){
       var dataColor = $(this).data('color');
         $(this).css('border-color','#ececec');
     });
     $('.entry-content').css('max-width','none');
     $('.post-edit-link').css('display','none');
     $('.entry-header').css('display','none');


   });

   </script>

<?php
 }

 add_shortcode( 'feature_modal', 'fmp_shortcode' );


?>
