<?php
/**
* Plugin Name:    WP Inventory API Add On
* Plugin URI:    http://www.wpinventory.com
* Description:   WP Inventory Manager add on that allows the opening of endpoints for custom app or 3rd party integrations.
* Version:        0.0.1
* Author:        WP Inventory Manager
* Author URI:    http://www.wpinventory.com/
* Text Domain:    wpinventory
*
* ------------------------------------------------------------------------
* Copyright 2009-2019 WP Inventory Manager
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
exit;
}

function activate_wpim_api() {
$min_version = '1.3.2';
if ( ! WPIMCore::check_version( $min_version, 'WP Inventory Manager API' ) ) {
//		return;
}

add_action( 'wpim_core_loaded', 'launch_wpim_api' );
}

function launch_wpim_api() {
	require_once "wpinventory_api.class.php";
}

// Cannot load the plugin files until we are certain required WP Inventory classes are loaded
add_action('wpim_load_add_ons', 'activate_wpim_api' );