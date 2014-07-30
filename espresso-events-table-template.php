<?php
/*
  Plugin Name: Event Espresso - Events Table Template
  Plugin URI: http://www.eventespresso.com
  Description: The Event Espresso Events Table Template adds a events table view to Event Espresso 4
  Shortcode Example: [EVENTS_TABLE_TEMPLATE]
  Version: 0.0.1.dev.001
  Author: Event Espresso
  Author URI: http://www.eventespresso.com
  Copyright 2014 Event Espresso (email : support@eventespresso.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA02110-1301USA
 *
 * ------------------------------------------------------------------------
 *
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package		Event Espresso
 * @ author			Event Espresso
 * @ copyright	(c) 2008-2014 Event Espresso  All Rights Reserved.
 * @ license		http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link				http://www.eventespresso.com
 * @ version	 	EE4
 *
 * ------------------------------------------------------------------------
 */
// events_table_template version
define( 'EE_EVENTS_TABLE_TEMPLATE_VERSION', '0.0.1.dev.001' );
define( 'EE_EVENTS_TABLE_TEMPLATE_PLUGIN_FILE',  __FILE__ );

function load_espresso_events_table_template() {
	if ( class_exists( 'EE_Addon' )) {
		require_once ( plugin_dir_path( __FILE__ ) . 'EE_Events_Table_Template.class.php' );
		EE_Events_Table_Template::register_addon();
	}
}
add_action( 'AHEE__EE_System__load_espresso_addons', 'load_espresso_events_table_template' );

// End of file espresso_events_table_template.php
// Location: wp-content/plugins/espresso-events-table-template/espresso_events_table_template.php
