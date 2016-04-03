<?php
/*
Plugin Name: BxSlider WP
Plugin URI: http://www.codefleet.net/bxslider-wp/
Description: Provides an easy to use interface for BxSlider that blends seamlessly with your WordPress workflow.
Version: 1.5.2
Author: Nico Amarilla
Author URI: http://www.codefleet.net/
License:

  Copyright 2013 (kosinix@codefleet.net)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

// Autoloader
function bxslider_wp_autoloader($classname) {
    if(false !== strpos($classname, 'BxSliderWp')){
        $plugin_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR;
        $classname = str_replace('_', DIRECTORY_SEPARATOR, $classname);
        $file = $plugin_dir .'src'.DIRECTORY_SEPARATOR. $classname . '.php';
        require_once $file;
    }
}
spl_autoload_register('bxslider_wp_autoloader');

$bxslider_wp_instance = null;

// Hook the plugin
add_action('plugins_loaded', 'bxslider_wp_init', 9); // Priority must be less than target action
function bxslider_wp_init() {
    global $bxslider_wp_instance;
    
    remove_action('plugins_loaded', 'bxslider_wp_init'); // Remove bxslider free initialization
    
    $plugin = new BxSliderWp_Main();
    
    $plugin['name'] = 'BxSlider WP';
    $plugin['path'] = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR;
    $plugin['url'] = plugin_dir_url(__FILE__);
    $plugin['debug'] = false;
    $plugin['version'] = '1.5.2';
	$plugin['textdomain'] = 'bxslider';
    $plugin['slug'] = 'bxslider-wp/bxslider-wp.php'; 
    $plugin['nonce_name'] = 'bxslider_wp_nonce';
    $plugin['nonce_action'] = 'bxslider_wp_action';
    
    
    load_plugin_textdomain( $plugin['textdomain'], false, basename(dirname(__FILE__)).'/lang' ); // Load language files
    
    $plugin['view'] = new BxSliderWp_View( $plugin['view.view_folder'] );
    $plugin['view.folder'] = $plugin['path'].'views'.DIRECTORY_SEPARATOR;
    $plugin['view']->set_view_folder($plugin['view.folder']);
    
    $plugin['data'] = new BxSliderWp_Data();
    $plugin['admin'] = new BxSliderWp_Admin();
    $plugin['frontend'] = new BxSliderWp_Frontend();
    $plugin['widgets'] = new BxSliderWp_Widgets();
    
    $plugin->run();
    
    $bxslider_wp_instance = $plugin;
       
    if(!function_exists('bxslider')) {
        /**
        * BxSlider
        *
        * Displays the slider on template files.
        *
        * @param string $slider_slug The slug of the slider.
        */
        function bxslider( $slider_slug ){
            $codefleet_bxslider_data = new BxSliderWp_Data();
            $slider_view = new BxSliderWp_View(realpath(plugin_dir_path(__FILE__)). DIRECTORY_SEPARATOR .'views');
            if(isset($codefleet_bxslider_data)){
                echo $codefleet_bxslider_data->get_slider_render($slider_slug, $slider_view);
            }
        }
    }
}