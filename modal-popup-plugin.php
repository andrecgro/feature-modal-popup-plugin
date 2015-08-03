<?php
/*
Plugin Name: Modal Popup Plugin
Plugin URI:
Description: A plugin to help you create beautiful modal popup with your pages content based on it's ID;
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
    add_menu_page('Modal Popup Plugin Page','Modal Popup Plugin','manage_options','mpp', 'mpp_setup_options_page' );
  }

  function mpp_setup_options_page(){
    echo "<h1>Hello World</h1>";
  }
?>
