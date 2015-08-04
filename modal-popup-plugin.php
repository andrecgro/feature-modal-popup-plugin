<?php
/*
Plugin Name: Feature and Modal Popup Plugin
Plugin URI:
Description: A plugin to help you create a beautiful section of features with modal popup;
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
  add_action('admin_menu', 'mpp_setup_menu_page');

    function mpp_setup_menu_page(){
    add_menu_page('Modal Popup Plugin Page','Modal Popup Plugin','manage_options','mpp', 'mpp_setup_options_page' , '' ,'20.1');
    add_submenu_page('mpp', 'Adicionar Nova Feature', 'Adicionar Nova Feature','manage_options', 'new_feature' , 'mpp_setup_new_feature_page');
}


    function mpp_setup_options_page(){

      if ( !current_user_can( 'manage_options' ) )  {
  		    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  	  }
    }

    function mpp_setup_new_feature_page(){

          include 'template.php';
    }
?>
