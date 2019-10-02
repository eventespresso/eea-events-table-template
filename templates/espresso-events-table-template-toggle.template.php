<?php
// Options
$date_format		= get_option( 'date_format' );
$time_format		= get_option( 'time_format' );
// Load Venue View Helper
EE_Registry::instance()->load_helper('Venue_View');
//Defaults
$reg_button_text		= !isset($reg_button_text) ? __('Register', 'event_espresso') : $reg_button_text;
$alt_button_text		= !isset($alt_button_text) ? __('View Details', 'event_espresso') : $alt_button_text;//For alternate registration pages
$sold_out_button_text	= !isset($sold_out_button_text) ? __('Sold Out', 'event_espresso') : $sold_out_button_text;//For sold out events

if ( have_posts() ) :
	// allow other stuff
	do_action( 'AHEE__espresso_events_table_template_template__before_loop' );
	?>

	<?php if ($category_filter != 'false'){ ?>
	<p class="category-filter">
		<label><?php echo __('Category Filter:', 'event_espresso'); ?></label>
		<select class="" id="ee_filter_cat">
		<option class="ee_filter_show_all"><?php echo __('Show All', 'event_espresso'); ?></option>
		<?php
		$taxonomy = array('espresso_event_categories');
		$args = array('orderby'=>'name','hide_empty'=>true);
		$ee_terms = get_terms($taxonomy, $args);

		foreach($ee_terms as $term){
			echo '<option class="' . $term->slug . '">'. $term->name . '</option>';
		}
	    ?>
		</select>
	</p>
	<?php } ?>

	<?php if ($footable != 'false' && $table_search != 'false'){ ?>
	<p>
        <?php echo __('Search:', 'event_espresso'); ?> <input id="filter" type="text"/>
    </p>
    <?php } ?>

	<table id="ee_filter_table" class="espresso-table footable table" width="100%" data-page-size="<?php echo $table_pages; ?>" data-filter="#filter">
	<thead class="espresso-table-header-row">
		<tr>
			<th data-toggle="true" class="th-group"><?php _e('Event','event_espresso'); ?></th>
			<?php if( $show_venues ) { ?>
				<th data-hide="all" class="th-group"><?php _e('Venue','event_espresso'); ?></th>
			<?php } ?>
			<th data-hide="all" class="th-group"><?php _e('Date','event_espresso'); ?></th>
			<th data-hide="all" class="th-group"><?php _e('Description','event_espresso'); ?></th>
			<th class="th-group" data-sort-ignore="true"></th>
		</tr>
	</thead>
	<?php if ($footable != 'false' && $table_paging != 'false'){ ?>
	<tfoot>
		<tr>
			<td colspan="2">
				<div class="pagination pagination-centered"></div>
			</td>
		</tr>
	</tfoot>
	<?php } ?>
	<tbody>

	<?php
	// Start the Loop.
	while ( have_posts() ) : the_post();
		// Include the post TYPE-specific template for the content.
		global $post;

		//Debug
		//d( $post );

		//Get the category for this event
		$event = EEH_Event_View::get_event();
		if ( $event instanceof EE_Event ) {
			if ( $event_categories = get_the_terms( $event->ID(), 'espresso_event_categories' )) {
				// loop thru terms and create links
				$category_slugs = array();
				foreach ( $event_categories as $term ) {
					$category_slugs[] = $term->slug;
				}
				$category_slugs = implode(' ', $category_slugs);
			} else {
				// event has no terms
				$category_slugs = '';
			}

		}
		//Create the event link
		$external_url 		= $post->EE_Event->external_url();
		$button_text		= !empty($external_url) ? $alt_button_text : $reg_button_text;
		$registration_url 	= !empty($external_url) ? $post->EE_Event->external_url() : $post->EE_Event->get_permalink();

		//Create the register now button
		$live_button 		= '<a id="a_register_link-'.$post->ID.'" class="a_register_link" href="'.$registration_url.'">'.$button_text.'</a>';

		if ( $event->is_sold_out() ) {
			$live_button	= '<a id="a_register_link-'.$post->ID.'" class="a_register_link_sold_out a_register_link" href="'.$registration_url.'">'.$sold_out_button_text.'</a>';
		}

		// If the show_all_datetimes parameter is set set the limit to NULL to pull them all,
		// if not default to only dipslay a single datetime.
		$datetime_limit = $show_all_datetimes ? NULL : 1;

		// Pull the datetimes for this event order by start_date/time
		$datetimes = EEM_Datetime::instance()->get_datetimes_for_event_ordered_by_start_time( $post->ID, $show_expired, false, $datetime_limit );

		// Reset the datetimes pointer to the earlest datetime and use that one.
		$datetime = reset( $datetimes );
		?>
		<tr class="espresso-table-row <?php echo $category_slugs; ?>">
			<td class="event_title event-<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></td>
			<?php if( $show_venues ) { ?>
				<td class="venue_title event-<?php echo $post->ID; ?>"><?php espresso_venue_name( NULL, FALSE ); ?></td>
			<?php } ?>
			<td class="start_date event-<?php echo $post->ID; ?>" data-value="<?php echo $datetime->get_raw( 'DTT_EVT_start' ); ?>">
				<ul class="ee-table-view-datetime-list">
					<?php
						// Loop over each datetime we have pulled from the database and output
						foreach ($datetimes as $datetime) { 
						?>
							<li class="datetime-id-<?php echo $datetime->ID(); ?>">
								<?php echo date_i18n(  $date_format . ' ' . $time_format, strtotime( $datetime->start_date_and_time('Y-m-d', 'H:i:s') ) ); ?>
							</li>
					<?php 
						//end foreach $datetimes
						} 
					?>
				</ul>
			<td class="event_content event-<?php echo $post->ID; ?>"><?php espresso_event_content_or_excerpt(); ?></td>
			<td class="td-group reg-col" nowrap="nowrap"><?php echo $live_button; ?></td>
		</tr>
		<?php


	endwhile;
	echo '</table>';
	// allow moar other stuff
	do_action( 'AHEE__espresso_events_table_template_template__after_loop' );

else :
	// If no content, include the "No posts found" template.
	espresso_get_template_part( 'content', 'none' );

endif;
