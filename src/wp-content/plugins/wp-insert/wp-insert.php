<?php
/*
Plugin Name: Wp-Insert
Plugin URI: http://www.wp-insert.smartlogix.co.in/
Description: WP-INSERT by SMARTLOGIX :  The Ultimate Adsense / Ad-Management Plugin for WordPress
Version: 2.4.2
Author: Namith Jawahar
Author URI: http://www.smartlogix.co.in/
WP-INSERT by SMARTLOGIX :  The Ultimate Adsense / Ad-Management Plugin for WordPress
*/

/*  Copyright 2015  NAMITH JAWAHAR  (website : http://www.smartlogix.co.in)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*Definitions*/
if(!defined('WP_INSERT_URL'))
	define('WP_INSERT_URL', plugin_dir_url(__FILE__));
if(!defined('WP_INSERT_DIR'))
	define('WP_INSERT_DIR', plugin_dir_path(__FILE__));
if(!defined('WP_INSERT_VERSION'))
	define('WP_INSERT_VERSION', '2.4');
if(!defined('WP_INSERT_DEBUG'))
	define('WP_INSERT_DEBUG', false);
/*Includes*/

require_once(dirname(__FILE__).'/includes/modules/core/modules.php');
require_once(dirname(__FILE__).'/includes/modules/general/modules.php');

register_activation_hook(__FILE__, 'wp_insert_vi_api_reset_settings');
?>