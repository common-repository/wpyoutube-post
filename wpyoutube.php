<?php

/*

 * Plugin Name: Youtube video To Wordpress Post 

 * Author URI: https://nktechnologys.com/

 * Author Email: 05navneetSharma@gmail.com

 * Description: This plugin is used for getting all the video with title and description from your youtube channel and create the post into wordpress based on that content.All you need to done is set your channel id and youtube API key from admin and click on button Sync it will Sync all your youtube post in to wordpress post.

 * Author: Navneet Sharma Chochra

 * Version: 1.0

 * Requires at least: 4.7

 * Tested up to: 6.0.1

 */

defined( 'ABSPATH' ) or exit;



  add_action('plugins_loaded', 'load_yt_wp_plugin');

  function load_yt_wp_plugin() {

    define('YTBTOWP_PLUGIN_URL', plugin_dir_url(__FILE__));
    define('YTBTOWP_PLUGIN_DIR', plugin_dir_path(__FILE__));
    define('YTBTOWP_MAXRESULT', 10);

    require_once YTBTOWP_PLUGIN_DIR . '/inc/loader.php';
      
  }





 /*-------------------------------------------------------------------
| Activation Hook 
 --------------------------------------------------------------------*/


  register_activation_hook(__FILE__, 'yt_wp_activate');

  function yt_wp_activate() {

      
  }





  /*-------------------------------------------------------------------
  | Deactivate Hook 
  --------------------------------------------------------------------*/

  register_deactivation_hook(__FILE__, 'yt_wp_deactivation');

  function yt_wp_deactivation() {


  }


  /*-------------------------------------------------------------------
  | Uninstalled Hook 
  --------------------------------------------------------------------*/
  function yt_wp_uninstall(){
     


  }

  register_uninstall_hook(__FILE__, 'yt_wp_uninstall');




?>