<?php
/*
Plugin Name: Modal Popup Plugin
Plugin URI:
Description: A plugin to put some beautiful modal popups filled with a page cotent whatever you want to.
Version: 1.0
Author: André Rocha
Author URI: https://github.com/andrecgro
License: GPLv2
*/

/*
 *      Copyright 2014 André Rocha <andre.cgrocha@gmail.com>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 3 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */


?>

<?php
// Se quisermos ativar o modo de depuração de erros
// definimos com o valor true a seguinte constante
define( 'WP_DEBUG', true );

// Vamos carregar o ambiente do WordPress a partir
//de um único local, o wp-load.php
require( './wp-load.php' );

// Registamos a função para correr na ativação do plugin
register_activation_hook( __FILE__, 'ewp_install_hook' );

function ewp_install_hook() {
  // Vamos testar a versão do PHP e do WordPress
  // caso as versões sejam antigas, desativamos
  // o nosso plugin.
  if ( version_compare( PHP_VERSION, '5.2.1', '<' )
    or version_compare( get_bloginfo( 'version' ), '3.3', '<' ) ) {
      deactivate_plugins( basename( __FILE__ ) );
  }
}

function mpp_widgets_init(){
  register_sidebar(array(

      'name' => __('Modal Popup Plugin', 'mpp'),

      'id' => 'sidebar-modal-popup',

      'before_widget' => '',

      'after_widget' => '',

      'before_title' => '<h1 class="widget-title">',

      'after_title' => '</h1>',

  ));
}

add_action('widgets_init', 'mpp_widgets_init');

/*WIDGETS*/

add_action('widgets_init', 'mpp_register_widgets');

function mpp_register_widgets()
{

    register_widget('modal-popup-widget');

}

add_action('customize_controls_print_scripts', 'modal_popup_widget_scripts');

function modal_popup_widget_scripts()
{

    wp_enqueue_media();

    wp_enqueue_script('modal_popup_widget_scripts', get_template_directory_uri() . '/js/widget.js', false, '1.0', true);

}


class mpp extends WP_Widget
{


    function mpp_plugin()
    {

        $widget_ops = array('classname' => 'ctUp-ads');

        $this->WP_Widget('ctUp-ads-widget', 'Modal Popup Plugin', $widget_ops);

    }


    function widget($args, $instance)
    {

        extract($args);


        echo $before_widget;

?>


        <div class="col-lg-3 col-sm-3 focus-box" data-scrollreveal="enter left after 0.15s over 1s">


			<?php if( !empty($instance['image_uri']) ): ?>
            <div class="service-icon">

				<?php if( !empty($instance['id']) ): ?>


					<a onclick="mudaEstilo('<?php echo $instance['id'] ?>')"><i class="pixeden" style="cursor:pointer;background:url(<?php echo esc_url($instance['image_uri']); ?>) no-repeat center;width:100%; height:100%;"></i>  <!--FOCUS ICON--></a>

				<?php else: ?>

					<i class="pixeden" style="cursor:pointer;background:url(<?php echo esc_url($instance['image_uri']); ?>) no-repeat center;width:100%; height:100%;"></i> <!-- FOCUS ICON-->

				<?php endif; ?>


            </div>
			<?php endif; ?>

            <h5 class="red-border-bottom"><?php if( !empty($instance['title']) ): echo apply_filters('widget_title', $instance['title']); endif; ?></h5>
            <!-- FOCUS HEADING -->


			<?php
				if( !empty($instance['id']) ):

					echo '<p>';
						echo robins_get_the_excerpt($instance['id']);//htmlspecialchars_decode(apply_filters('widget_title', $instance['text']));
					echo '</p>';
				endif;
			?>
            <a onclick="mudaEstilo('<?php echo $instance['id'] ?>')" style="cursor:pointer;">Saiba Mais</a>
        </div>
<!-- POPUP -->
<div  class="modal modal-feature" id="<?php echo $instance['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="featureModalLabel-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="feature-modal-1">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="mudaEstilo('<?php echo $instance['id'] ?>')">&times;</button>
                    <h1> <?php echo get_the_title($instance['id']); ?> </h1>

                </div>
                <div class="modal-body">

                    <div class="row">

                    <div class="content col-md-12 col-sm-12 col-xs-12">

                           <?php while ( have_posts() ) : the_post(); ?>

                                   <?php echo (get_post_field( 'post_content' , $instance['id']) );?>


                         <?php endwhile; // end of the loop. ?>

                        </div><!--//content-->
                    </div><!--//row-->
                </div><!--//modal-body-->
            </div><!--//modal-content-->
        </div><!--//modal-dialog-->
    </div><!--//modal-->

<!--//popup-->

        <?php

        echo $after_widget;


    }


    function update($new_instance, $old_instance)
    {

        $instance = $old_instance;

        $instance['text'] = wp_filter_post_kses($new_instance['text']);

        $instance['title'] = strip_tags($new_instance['title']);

		$instance['image_uri'] = strip_tags($new_instance['image_uri']);

        $instance['id'] = strip_tags($new_instance['id']);

        return $instance;

    }


    function form($instance)
    {

        ?>


        <p>

            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('ID', 'zerif-lite'); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('id'); ?>"
                   id="<?php echo $this->get_field_id('id'); ?>" value="<?php if( !empty($instance['id']) ): echo $instance['id']; endif; ?>"
                   class="widefat"/>

        </p>


        <p>

            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'zerif-lite'); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('title'); ?>"
                   id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty($instance['title']) ): echo $instance['title']; endif; ?>"
                   class="widefat"/>

        </p>


        <p>

            <label for="<?php echo $this->get_field_id('image_uri'); ?>"><?php _e('Image', 'zerif-lite'); ?></label><br/>



            <?php

            if ( !empty($instance['image_uri']) ) :

                echo '<img class="custom_media_image" src="' . $instance['image_uri'] . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" /><br />';

            endif;

            ?>



            <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name('image_uri'); ?>"
                   id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php if( !empty($instance['image_uri']) ): echo $instance['image_uri']; endif; ?>"
                   style="margin-top:5px;">


            <input type="button" class="button button-primary custom_media_button" id="custom_media_button"
                   name="<?php echo $this->get_field_name('image_uri'); ?>" value="<?php _e('Upload Image','zerif-lite'); ?>"
                   style="margin-top:5px;"/>

        </p>



    <?php

    }

}
//* Register and Enqueue scripts for popup
function cc_popup_script() {
    wp_register_script( 'popup', get_stylesheet_directory_uri() . '/js/popup.js', array( 'jquery' ), '1.0.0', false );
    wp_enqueue_script( 'popup' );
 }

add_action('wp_enqueue_scripts', 'cc_popup_script');
 ?>
