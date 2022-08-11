<?php

/**
 * Class EE_Events_Table_Template_Query
 * Description
 *
 * @package               Event Espresso
 * @subpackage            core
 * @author                Brent Christensen
 * @since                 4.4
 */
class EE_Events_Table_Template_Query extends WP_Query
{

    private $_limit        = 10;

    private $_show_expired = false;

    private $_month;

    private $_category_slug;

    private $_order_by;

    private $_sort;


    /**
     * @param array $args
     * @throws EE_Error
     * @throws ReflectionException
     */
    public function __construct($args = [])
    {
        // incoming args could be a mix of WP query args + EE shortcode args
        foreach ($args as $key => $value) {
            $property = '_' . $key;
            // if the arg is a property of this class, then it's an EE shortcode arg
            if (property_exists($this, $property)) {
                // set the property value
                $this->{$property} = $value;
                // then remove it from the array of args that will later be passed to WP_Query()
                unset($args[ $key ]);
            }
        }
        // parse orderby attribute
        if ($this->_order_by !== null) {
            $this->_order_by = explode(',', (string) $this->_order_by);
            $this->_order_by = array_map('trim', $this->_order_by);
        }
        $this->_sort = in_array(
            $this->_sort,
            ['ASC', 'asc', 'DESC', 'desc'],
            true
        )
            ? strtoupper($this->_sort)
            : 'ASC';
        // setup the events list query
        EE_Registry::instance()->load_helper('Event_Query');
        //add query filters
        EEH_Event_Query::add_query_filters();
        // set params that will get used by the filters
        EEH_Event_Query::set_query_params(
            $this->_month,
            $this->_category_slug,
            $this->_show_expired,
            $this->_order_by,
            $this->_sort
        );
        // the current "page" we are viewing
        $paged = max(1, get_query_var('paged'));
        // Force these args
        $args = array_merge($args, [
            'post_type'              => 'espresso_events',
            'posts_per_page'         => $this->_limit,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'paged'                  => $paged,
            'offset'                 => ($paged - 1) * $this->_limit,
        ]);
        // run the query
        parent::__construct($args);
    }
}
