<?php if ( ! defined( 'EVENT_ESPRESSO_VERSION' )) { exit(); }
/**
 * EES_Espresso_Events_Table_Template
 *
 * @package			Event Espresso
 * @subpackage		espresso-events-table-template
 * @ author				Seth Shoultes
 * @ version		 	$VID:$
 */
class EES_Espresso_Events_Table_Template  extends EES_Shortcode {



	/**
	 * 	set_hooks - for hooking into EE Core, modules, etc
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public static function set_hooks() {
	}



	/**
	 * 	set_hooks_admin - for hooking into EE Admin Core, modules, etc
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public static function set_hooks_admin() {
	}



	/**
	 * 	set_definitions
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public static function set_definitions() {
	}



	/**
	 * 	run - initial shortcode module setup called during "wp_loaded" hook
	 * 	this method is primarily used for loading resources that will be required by the shortcode when it is actually processed
	 *
	 *  @access 	public
	 *  @param 	 WP $WP
	 *  @return 	void
	 */
	public function run( WP $WP ) {
		add_action('wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
		// You might want this, but delete if you don't need the template tags
		EE_Registry::instance()->load_helper( 'Event_View' );
		EE_Registry::instance()->load_helper( 'Venue_View' );
	}



	/**
	 * 	enqueue_scripts - Load the scripts and css
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function enqueue_scripts() {
		//Check to see if the events_table_template css file exists in the '/uploads/espresso/' directory
		if ( is_readable( EVENT_ESPRESSO_UPLOAD_DIR . 'css' . DS . 'espresso_events_table_template.css' )) {
			//This is the url to the css file if available
			wp_register_style( 'espresso_events_table_template', EVENT_ESPRESSO_UPLOAD_URL . 'css' . DS . 'espresso_events_table_template.css' );
		} else {
			// EE events_table_template style
			wp_register_style( 'espresso_events_table_template', EE_EVENTS_TABLE_TEMPLATE_URL . 'css' . DS . 'espresso_events_table_template.css' );
		}

		// events_table_template script
		wp_register_script( 'espresso_events_table_template', EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'espresso_events_table_template.js', array( 'jquery' ), EE_EVENTS_TABLE_TEMPLATE_VERSION, TRUE );

		// enqueue
		wp_enqueue_style( 'espresso_events_table_template' );
		wp_enqueue_script( 'espresso_events_table_template' );

	}



	/**
	 *    process_shortcode
	 *
	 *    [ESPRESSO_EVENTS_TABLE_TEMPLATE]
	 *
	 * @access 	public
	 * @param 	array $attributes
	 * @return 	string
	 */
	public function process_shortcode( $attributes = array() ) {
		
		//if 'table_paging'=false set table_pages to a large number than 10 by default if a value has not be set already
		if( isset( $attributes['table_paging']) && !filter_var($attributes['table_paging'], FILTER_VALIDATE_BOOLEAN) ) {
			if( !isset( $attributes['table_pages'] ) ) {
				$attributes['table_pages'] = 100;
			}
		}

		// make sure $attributes is an array
		$attributes = array_merge(
			// defaults
			array(
				'template_file'		=> 'espresso-events-table-template.template.php', //Default template file
				'limit' 					=> 1000,
				'show_expired' 	=> FALSE,
				'month' 				=> NULL,
				'category_slug' 	=> NULL,
				'category_filter' 	=> NULL,
				'order_by' 			=> 'start_date',
				'sort'					=> 'ASC',
				'footable'				=> NULL,
				'table_style'			=> 'standalone',
				'table_sort'			=> NULL,
				'table_paging'		=> NULL,
				'table_pages'		=> 10,
				'table_striping'	=> NULL,
				'table_search'		=> NULL
			),
			(array)$attributes
		);

		// Show Expired ?
		$attributes['show_expired'] = filter_var($attributes['show_expired'], FILTER_VALIDATE_BOOLEAN);

		if ( $attributes['footable'] != 'false' ){
			//FooTable Styles
			wp_register_style( 'footable-core', EE_EVENTS_TABLE_TEMPLATE_URL . 'css' . DS . 'footable.core.css' );
			wp_enqueue_style( 'footable-core' );

			wp_register_style( 'footable-'.$attributes['table_style'], EE_EVENTS_TABLE_TEMPLATE_URL . 'css' . DS . 'footable.'.$attributes['table_style'].'.css' );
			wp_enqueue_style( 'footable-'.$attributes['table_style'] );

			//FooTable Scripts
			wp_register_script( 'footable', EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'footable.js', array( 'jquery' ), EE_EVENTS_TABLE_TEMPLATE_VERSION, TRUE );
			// enqueue scripts
			wp_enqueue_script( 'footable' );

			//FooTable Sorting
			if ( $attributes['table_sort'] != 'false' ){
				wp_register_script( 'footable-sort', EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'footable.sort.js', array( 'jquery' ), EE_EVENTS_TABLE_TEMPLATE_VERSION, TRUE );
				wp_enqueue_script( 'footable-sort' );
			}

			//FooTable Striping
			if ( $attributes['table_striping'] != 'false' ){
				wp_register_script( 'footable-striping', EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'footable.striping.js', array( 'jquery' ), EE_EVENTS_TABLE_TEMPLATE_VERSION, TRUE );
				wp_enqueue_script( 'footable-striping' );
			}

			//FooTable Pagination
			if ( $attributes['table_paging'] != 'false' ){
				wp_register_script( 'footable-paginate', EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'footable.paginate.js', array( 'jquery' ), EE_EVENTS_TABLE_TEMPLATE_VERSION, TRUE );
				wp_enqueue_script( 'footable-paginate' );
			}

			//FooTable Filter
			if ( $attributes['table_search'] != 'false' ){
				wp_register_script( 'footable-filter', EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'footable.filter.js', array( 'jquery' ), EE_EVENTS_TABLE_TEMPLATE_VERSION, TRUE );
				wp_enqueue_script( 'footable-filter' );
			}
		}

		// run the query
		global $wp_query;
		$wp_query = new EE_Events_Table_Template_Query( $attributes );
		//d( $wp_query );
		// now filter the array of locations to search for templates
		add_filter( 'FHEE__EEH_Template__locate_template__template_folder_paths', array( $this, 'template_folder_paths' ));
		// load our template
		$events_table_template = EEH_Template::locate_template( $attributes['template_file'], $attributes );
		// now reset the query and postdata
		wp_reset_query();
		wp_reset_postdata();
		return $events_table_template;
	}



	/**
	 *    template_folder_paths
	 *
	 * @access    public
	 * @param array $template_folder_paths
	 * @return    array
	 */
	public function template_folder_paths( $template_folder_paths = array() ) {
		$template_folder_paths[] = EE_EVENTS_TABLE_TEMPLATE_TEMPLATES;
		return $template_folder_paths;
	}

}

/**
 *
 * Class EE_Events_Table_Template_Query
 *
 * Description
 *
 * @package 			Event Espresso
 * @subpackage 	core
 * @author 				Brent Christensen
 * @since 				4.4
 *
 */
class EE_Events_Table_Template_Query extends WP_Query {

	private $_limit = 10;
	private $_show_expired = FALSE;
	private $_month = NULL;
	private $_category_slug = NULL;
	private $_order_by = NULL;
	private $_sort = NULL;


	/**
	 * @param array $args
	 */
	function __construct( $args = array() ) {
		// incoming args could be a mix of WP query args + EE shortcode args
		foreach ( $args as $key =>$value ) {
			$property = '_' . $key;
			// if the arg is a property of this class, then it's an EE shortcode arg
			if ( property_exists( $this, $property )) {
				// set the property value
				$this->$property = $value;
				// then remove it from the array of args that will later be passed to WP_Query()
				unset( $args[ $key ] );
			}
		}
		// parse orderby attribute
		if ( $this->_order_by !== NULL ) {
			$this->_order_by = explode( ',', $this->_order_by );
			$this->_order_by = array_map('trim', $this->_order_by);
		}
		$this->_sort = in_array( $this->_sort, array( 'ASC', 'asc', 'DESC', 'desc' )) ? strtoupper( $this->_sort ) : 'ASC';
		// setup the events list query
		EE_Registry::instance()->load_helper( 'Event_Query' );
		//add query filters
		EEH_Event_Query::add_query_filters();
		// set params that will get used by the filters
		EEH_Event_Query::set_query_params( $this->_month, $this->_category_slug, $this->_show_expired, $this->_order_by, $this->_sort );
		// the current "page" we are viewing
		$paged = max( 1, get_query_var( 'paged' ));
		// Force these args
		$args = array_merge( $args, array(
			'post_type' => 'espresso_events',
			'posts_per_page' => $this->_limit,
			'update_post_term_cache' => FALSE,
			'update_post_meta_cache' => FALSE,
			'paged' => $paged,
			'offset' => ( $paged - 1 ) * $this->_limit
		));
		// run the query
		parent::__construct( $args );
	}







}

// End of file EES_Espresso_Events_Table_Template.shortcode.php
// Location: /wp-content/plugins/espresso-events-table-template/EES_Espresso_Events_Table_Template.shortcode.php