<?php

namespace EventEspresso\TableTemplate\domain\entities\shortcodes;

use EEH_Template;
use EventEspresso\core\services\shortcodes\EspressoShortcode;
use EventEspresso\TableTemplate\domain\queries\EventsTableTemplateQuery;

defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * Class EventsTableTemplate
 * new shortcode class for the ESPRESSO_EVENTS_TABLE_TEMPLATE shortcode
 *
 * @package       Event Espresso
 * @author        Brent Christensen
 * @since         $VID:$
 */
class EventsTableTemplate extends EspressoShortcode
{



    /**
     * the actual shortcode tag that gets registered with WordPress
     *
     * @return string
     */
    public function getTag()
    {
        return 'ESPRESSO_EVENTS_TABLE_TEMPLATE';
    }



    /**
     * the length of time in seconds to cache the results of the processShortcode() method
     * 0 means the processShortcode() results will NOT be cached at all
     *
     * @return int
     */
    public function cacheExpiration()
    {
        return 10;
        // return MINUTE_IN_SECONDS * 15;
    }



    /**
     * a place for adding any initialization code that needs to run prior to wp_header().
     * this may be required for shortcodes that utilize a corresponding module,
     * and need to enqueue assets for that module
     *
     * @return void
     */
    public function initializeShortcode()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueueScriptsAndStyles'), 10);
    }




    /**
     * @return    void
     */
    public function enqueueScriptsAndStyles()
    {
        //Check to see if the events_table_template css file exists in the '/uploads/espresso/' directory
        if (is_readable(EVENT_ESPRESSO_UPLOAD_DIR . 'css' . DS . 'espresso_events_table_template.css')) {
            //This is the url to the css file if available
            wp_register_style(
                'espresso_events_table_template',
                EVENT_ESPRESSO_UPLOAD_URL . 'css' . DS . 'espresso_events_table_template.css'
            );
        } else {
            // EE events_table_template style
            wp_register_style(
                'espresso_events_table_template',
                EE_EVENTS_TABLE_TEMPLATE_URL . 'css' . DS . 'espresso_events_table_template.css'
            );
        }
        // events_table_template script
        wp_register_script(
            'espresso_events_table_template',
            EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'espresso_events_table_template.js',
            array('jquery'),
            EE_EVENTS_TABLE_TEMPLATE_VERSION,
            true
        );
        // enqueue
        wp_enqueue_style('espresso_events_table_template');
        wp_enqueue_script('espresso_events_table_template');
    }



    /**
     * array for defining custom attribute sanitization callbacks,
     * where keys match keys in your attributes array,
     * and values represent the sanitization function you wish to be applied to that attribute.
     * So for example, if you had an integer attribute named "event_id"
     * that you wanted to be sanitized using absint(),
     * then you would return the following:
     *      array('event_id' => 'absint')
     * Entering 'skip_sanitization' for the callback value
     * means that no sanitization will be applied
     * on the assumption that the attribute
     * will be sanitized at some point... right?
     * You wouldn't pass around unsanitized attributes would you?
     * That would be very Tom Foolery of you!!!
     *
     * @return array
     */
    protected function customAttributeSanitizationMap()
    {
        return array(
            'category_slug' => 'skip_sanitization',
            'show_expired'  => 'skip_sanitization',
            'order_by'      => 'skip_sanitization',
            'month'         => 'skip_sanitization',
            'sort'          => 'skip_sanitization',
        );
    }

    /**
     * callback that runs when the shortcode is encountered in post content.
     * IMPORTANT !!!
     * remember that shortcode content should be RETURNED and NOT echoed out
     *
     * @param array $attributes
     * @return string
     */
    public function processShortcode($attributes = array())
    {
        // grab attributes and merge with defaults
        $attributes = $this->getAttributes($attributes);
        $this->enqueueFootableAssets($attributes);
        // run the query
        global $wp_query;
        $wp_query = new EventsTableTemplateQuery($attributes);
        // now filter the array of locations to search for templates
        add_filter(
            'FHEE__EEH_Template__locate_template__template_folder_paths',
            array($this, 'templateFolderPaths')
        );
        // load our template
        $events_table_template = EEH_Template::locate_template($attributes['template_file'], $attributes);
        // now reset the query and postdata
        wp_reset_query();
        wp_reset_postdata();
        return $events_table_template;
    }



    /**
     * merge incoming attributes with filtered defaults
     *
     * @param array $attributes
     * @return array
     */
    private function getAttributes(array $attributes)
    {
        // if 'table_paging'=false set table_pages to a large number
        // than 10 by default if a value has not be set already
        if (
            ! isset($attributes['table_pages'])
            && isset($attributes['table_paging'])
            && ! filter_var($attributes['table_paging'], FILTER_VALIDATE_BOOLEAN)
        ) {
            $attributes['table_pages'] = 100;
        }
        // validate show_venue as a boolean
        if ( !empty($attributes['show_venues']) ) {
            $attributes['show_venues'] = filter_var($attributes['show_venues'], FILTER_VALIDATE_BOOLEAN);
        }
        // validate show_expired as a boolean
        if ( !empty($attributes['show_expired']) ) {
            $attributes['show_expired'] = filter_var($attributes['show_expired'], FILTER_VALIDATE_BOOLEAN);
        }
        // make sure $attributes is an array and add defaults (union only adds missing elements)
        $attributes = (array)$attributes + array(
                // defaults
                'template_file'        => 'espresso-events-table-template.template.php',
                'limit'                => 1000,
                'show_expired'         => false,
                'month'                => null,
                'category_slug'        => null,
                'category_filter'      => null,
                'category_filter_text' => null,
                'order_by'             => 'start_date',
                'sort'                 => 'ASC',
                'footable'             => null,
                'table_style'          => 'standalone',
                'table_sort'           => null,
                'table_paging'         => null,
                'table_pages'          => 10,
                'table_striping'       => null,
                'table_search'         => null,
                'show_all_datetimes'   => false,
                'show_venues'          => true,
            );
        return $attributes;
    }



    /**
     * @param $attributes
     * @return void
     */
    public function enqueueFootableAssets($attributes)
    {
        if ($attributes['footable'] !== 'false') {
            //FooTable Styles
            wp_register_style(
                'footable-core',
                EE_EVENTS_TABLE_TEMPLATE_URL . 'css' . DS . 'footable.core.css'
            );
            wp_enqueue_style('footable-core');
            wp_register_style(
                'footable-' . $attributes['table_style'],
                EE_EVENTS_TABLE_TEMPLATE_URL . 'css' . DS . 'footable.' . $attributes['table_style'] . '.css'
            );
            wp_enqueue_style('footable-' . $attributes['table_style']);
            //FooTable Scripts
            wp_register_script(
                'footable',
                EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'footable.js',
                array('jquery'),
                EE_EVENTS_TABLE_TEMPLATE_VERSION,
                true
            );
            // enqueue scripts
            wp_enqueue_script('footable');
            //FooTable Sorting
            if ($attributes['table_sort'] !== 'false') {
                wp_register_script(
                    'footable-sort',
                    EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'footable.sort.js',
                    array('jquery'),
                    EE_EVENTS_TABLE_TEMPLATE_VERSION,
                    true
                );
                wp_enqueue_script('footable-sort');
            }
            //FooTable Striping
            if ($attributes['table_striping'] !== 'false') {
                wp_register_script(
                    'footable-striping',
                    EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'footable.striping.js',
                    array('jquery'),
                    EE_EVENTS_TABLE_TEMPLATE_VERSION,
                    true
                );
                wp_enqueue_script('footable-striping');
            }
            //FooTable Pagination
            if ($attributes['table_paging'] !== 'false') {
                wp_register_script(
                    'footable-paginate',
                    EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'footable.paginate.js',
                    array('jquery'),
                    EE_EVENTS_TABLE_TEMPLATE_VERSION,
                    true
                );
                wp_enqueue_script('footable-paginate');
            }
            //FooTable Filter
            if ($attributes['table_search'] !== 'false') {
                wp_register_script(
                    'footable-filter',
                    EE_EVENTS_TABLE_TEMPLATE_URL . 'scripts' . DS . 'footable.filter.js',
                    array('jquery'),
                    EE_EVENTS_TABLE_TEMPLATE_VERSION,
                    true
                );
                wp_enqueue_script('footable-filter');
            }
        }
    }


    /**
     * @param array $template_folder_paths
     * @return    array
     */
    public function templateFolderPaths($template_folder_paths = array())
    {
        $template_folder_paths[] = EE_EVENTS_TABLE_TEMPLATE_TEMPLATES;
        return $template_folder_paths;
    }




}
