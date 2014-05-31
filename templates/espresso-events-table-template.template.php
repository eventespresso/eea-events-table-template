<?php
$temp_month = '';

if ( have_posts() ) :
	// allow other stuff
	do_action( 'AHEE__espresso_events_table_template_template__before_loop' );
	?>
	<table id="ee_filter_table" class="espresso-table" width="100%">
	<thead class="espresso-table-header-row">
		<tr>
			<th class="th-group"><?php _e('Event','event_espresso'); ?></th>
			<th class="th-group"><?php _e('Venue','event_espresso'); ?></th>
			<th class="th-group"><?php _e('Date','event_espresso'); ?></th>
			<th class="th-group"></th>
		</tr>
	</thead>
	<tbody>
	<?php
	// Start the Loop.
	while ( have_posts() ) : the_post();
		// Include the post TYPE-specific template for the content.
		global $post;

		//Debug
		//d( $post );
		
		$button_text		= !isset($button_text) ? __('Register', 'event_espresso') : $button_text;
		$alt_button_text	= !isset($alt_button_text) ? __('View Details', 'event_espresso') : $alt_button_text;//For alternate registration pages
		$external_url 		= $post->EE_Event->external_url();
		$button_text		= !empty($external_url) ? $alt_button_text : $button_text;
		$registration_url 	= !empty($external_url) ? $post->EE_Event->external_url() : $post->EE_Event->get_permalink();
		
		//Create the register now button
		$live_button 		= '<a id="a_register_link-'.$post->ID.'" href="'.$registration_url.'">'.$button_text.'</a>';
		
		//Get the venue for this event
		$venues = espresso_event_venues();
		$venue = array_shift( $venues );
		
		//Debug
		//d( $venue );

		if ( $venue instanceof EE_Venue ) {
			$venue_name = $venue->name();
			$venue_address = $venue->address();
			$venue_city = $venue->city();
			if ($venue->state_obj() instanceof EE_State ) {
				$state = $venue->state_obj()->name();
			}
		}
		?>
		<tr class="espresso-table-row ?>">
			<td id="event_title-<?php echo $post->ID; ?>" class="event_title"><?php echo $post->post_title ?></td>
			<td id="venue_title-<?php echo $post->ID; ?>" class="venue_title"><?php echo (isset($venue_name) && !empty($venue_name)) ? $venue_name : '' ?></td>
			<td id="start_date-<?php echo $post->ID; ?>" class="start_date"><?php echo date(get_option('date_format'). ' '.get_option('time_format'), strtotime($post->DTT_EVT_start)) ?></td>
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
