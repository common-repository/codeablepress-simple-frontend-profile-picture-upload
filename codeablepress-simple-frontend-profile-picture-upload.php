<?php defined( 'ABSPATH' ) || exit;

/**
 * Plugin Name: CodeablePress: Simple Frontend Profile Picture Upload
 * Plugin URI:  https://codeablepress.com/product/simple-frontend-profile-picture-upload/
 * Description: A simple, lightweight, and secure way for users to upload profile pictures directly from the WooCommerce My Account page or via shortcode.
 * Version: 	1.0.0
 * Author: 		CodeablePress
 * Author URI: 	https://codeablepress.com
 * License: 	GPL-3.0-or-later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: codeablepress-simple-frontend-profile-picture-upload
 * Domain Path: /languages
 * 
 * ---------------------------------------------------------------------------//
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Simple Frontend Profile Picture Upload. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author     CodeablePress
 * @copyright  Copyright (c) 2024, Jack Calihan, 2024 CodeablePress
 * @link       https://codeablepress.com
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 */

// Plugin directory path.
define('CSFPP_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Plugin URL for accessing static files like JS, CSS, and images.
define('CSFPP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include the bootstrap file
require_once CSFPP_PLUGIN_DIR . 'bootstrap.php';