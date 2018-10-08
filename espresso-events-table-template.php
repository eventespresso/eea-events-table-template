<?php
/*
  Plugin Name: Event Espresso - Events Table View Template (EE 4.4.9+)
  Plugin URI: http://www.eventespresso.com
  Description: The Event Espresso Events Table Template adds a events table view to Event Espresso 4. Add [ESPRESSO_EVENTS_TABLE_TEMPLATE] to any WordPress page/post.
  Shortcode Example: [ESPRESSO_EVENTS_TABLE_TEMPLATE]
  Shortcode Parameters: footable = false (disables FooTable), table_search = false (turn off search), table_style = standalone (alternate styles: metro), table_sort = false (disables FooTable sorting), table_striping = false (turn off striping), table_paging = false (hide paging), table_pages = 10, limit = 1000, show_expired = FALSE, month = NULL, category_slug = NULL, category_filter = false, order_by = start_date, sort = ASC, template_file = espresso-events-table-template-toggle.template.php (creates a table with two columns and a toggle to expand the row) (users can upload custom templates to wp-content/uploads/espresso/templates/)
  Version: 1.3.9.p
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
define( 'EE_EVENTS_TABLE_TEMPLATE_VERSION', '1.3.9.p' );
define( 'EE_EVENTS_TABLE_TEMPLATE_PLUGIN_FILE',  plugin_basename( __FILE__ ) );

function load_espresso_events_table_template() {
	if ( class_exists( 'EE_Addon' )) {
		require_once ( plugin_dir_path( __FILE__ ) . 'EE_Events_Table_Template.class.php' );
		EE_Events_Table_Template::register_addon();
	}
}
add_action( 'AHEE__EE_System__load_espresso_addons', 'load_espresso_events_table_template' );

// End of file espresso_events_table_template.php
// Location: wp-content/plugins/espresso-events-table-template/espresso_events_table_template.php
