<?php if ( ! defined( 'EVENT_ESPRESSO_VERSION' )) { exit(); }
// define the plugin directory path and URL
define( 'EE_EVENTS_TABLE_TEMPLATE_PATH', plugin_dir_path( __FILE__ ));
define( 'EE_EVENTS_TABLE_TEMPLATE_URL', plugin_dir_url( __FILE__ ));
define( 'EE_EVENTS_TABLE_TEMPLATE_TEMPLATES', EE_EVENTS_TABLE_TEMPLATE_PATH . 'templates' );
/**
 *
 * Class  EE_Events_Table_Template
 *
 * @package			Event Espresso
 * @subpackage		espresso-events-table-template
 * @author			    Seth Shoultes
 *
 */
Class  EE_Events_Table_Template extends EE_Addon {

	/**
	 * class constructor
	 */
	public function __construct() {
		// register our activation hook
		register_activation_hook( __FILE__, array( $this, 'set_activation_indicator_option' ));
	}

	public static function register_addon() {

		// register addon via Plugin API
		EE_Register_Addon::register(
			'Events_Table_Template',
			array(
				'version' 					=> EE_EVENTS_TABLE_TEMPLATE_VERSION,
				'min_core_version' => '4.4.9.rc.034',
				'base_path' 				=> EE_EVENTS_TABLE_TEMPLATE_PATH,
				'main_file_path' 		=> EE_EVENTS_TABLE_TEMPLATE_PATH . 'espresso-events-table-template.php',
				//'admin_callback'	=> 'additional_admin_hooks',
				'autoloader_paths' => array(
					'EE_Events_Table_Template' 	=> EE_EVENTS_TABLE_TEMPLATE_PATH . 'EE_Events_Table_Template.class.php',
				),
				'shortcode_paths' 	=> array( EE_EVENTS_TABLE_TEMPLATE_PATH . 'EES_Espresso_Events_Table_Template.shortcode.php' ),
				// if plugin update engine is being used for auto-updates. not needed if PUE is not being used.
				'pue_options'			=> array(
					'pue_plugin_slug' 		=> 'eea-events-table-view-template',
					'plugin_basename' 	=> EE_EVENTS_TABLE_TEMPLATE_PLUGIN_FILE,
					'checkPeriod' 				=> '24',
					'use_wp_update' 		=> FALSE
				)
			)
		);
	}



	/**
	 * 	additional_admin_hooks
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function additional_admin_hooks() {
		// is admin and not in M-Mode ?
		if ( is_admin() && ! EE_Maintenance_Mode::instance()->level() ) {
			add_filter( 'plugin_action_links', array( $this, 'plugin_actions' ), 10, 2 );
		}
	}



	/**
	 * plugin_actions
	 *
	 * Add a settings link to the Plugins page, so people can go straight from the plugin page to the settings page.
	 * @param $links
	 * @param $file
	 * @return array
	 */
	public function plugin_actions( $links, $file ) {
		if ( $file == EE_EVENTS_TABLE_TEMPLATE_PLUGIN_FILE ) {
			// before other links
			array_unshift( $links, '<a href="admin.php?page=espresso_events_table_template">' . __('Settings') . '</a>' );
		}
		return $links;
	}






}
// End of file EE_Events_Table_Template.class.php
// Location: wp-content/plugins/espresso-events-table-template/EE_Events_Table_Template.class.php
