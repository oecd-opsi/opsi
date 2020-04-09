<?php

function cs_jve_map() {
	if ( ! (
			is_post_type_archive( 'covid_response' ) ||
			is_post_type_archive( 'case' ) ||
			is_tax('case_type') ||
			is_tax( 'innovation-tag' ) ||
			is_tax( 'country' ) ||
			is_tax( 'innovation-badge' )
	) ) {
		return;
	}

	$color_scale = 'case' == get_post_type() ? "['#d5bcf7', '#165580']" : "['#e9f2ef', '#167a59']";

	if ( is_tax('case_type') ) {

		// create empty array
		$wp_country_terms = [];
		// get slug of taxonomy term of queried object
		$taxonomy = get_queried_object();
		$tax_term_slug = $taxonomy->slug;

		// get all posts with taxonomy term of the archive
		$the_query = new WP_Query( array(
	    'post_type' => 'case',
	    'tax_query' => array(
        array (
          'taxonomy' => 'case_type',
          'field' => 'slug',
          'terms' => $tax_term_slug,
	      )
		  ),
			'posts_per_page' => -1,
			'post_status' => 'publish',
		) );

		// loop posts
		while ( $the_query->have_posts() ) : $the_query->the_post();

			// get object of Country taxonomy terms
			$terms = get_the_terms(get_the_ID(), 'country');
			// loop Country terms
			foreach( $terms as $term ) {
				$termID = $term->term_id;
				// check if ID is alreay in array
				if ( !isset($wp_country_terms[$termID]) ) {
					$wp_country_terms[$termID] = $term;
					// get all posts with taxonomy term of the archive and current country term
					$the_country_query = new WP_Query( array(
				    'post_type' => 'case',
				    'tax_query' => array(
							'relation' => 'AND',
			        array (
			          'taxonomy' => 'case_type',
			          'field' => 'slug',
			          'terms' => $tax_term_slug,
				      ),
							array (
			          'taxonomy' => 'country',
			          'field' => 'ID',
			          'terms' => $termID,
				      ),
					  ),
						'posts_per_page' => -1,
						'post_status' => 'publish',
					) );
					$wp_country_terms[$termID]->count = $the_country_query->post_count;
				}
			}

		endwhile;
		wp_reset_postdata();

	} else {

		$wp_country_terms = get_terms( 'country' , array( 'hide_empty' => true, 'fields' => 'all' ) );

	}

	$data = '';
	$code_to_flag = '';
	$code_to_slug = '';
	$max_count = 0;
	if ( !empty( $wp_country_terms ) ) {
		foreach ( $wp_country_terms as $country_term ) {
			$count = count_posts_in_term( 'country', $country_term->slug, get_post_type() );
			$iso = trim( get_field( 'iso_code', $country_term ) );
			$data .= '"'. $iso .'": '. $count .','; $data .= "\n\t";
			$code_to_flag .= '"'. $iso .'": "<img src=\''. get_stylesheet_directory_uri().'/images/flags/'.$iso.'.png\' width=\'24\' height=\'24\' >",'; $code_to_flag .= "\n\t";
			$code_to_slug .= '"'. $iso .'": "'. $country_term->slug .'",'; $code_to_slug .= "\n\t";

			if ( $count > $max_count ) {
				$max_count = $count;
			}
		}
	}
	?><script type="text/javascript">
		jQuery(document).ready(function(){

			var data = {
				<?php echo $data; ?>
			};
			var code_to_flag = {
				<?php echo $code_to_flag;  ?>
			};
			var code_to_slug = {
				<?php echo $code_to_slug;  ?>
			};
			var maxValue = <?php echo $max_count;  ?>;
			var country_to_add = '';

			jQuery('#map-bar .max').text(maxValue);



			jQuery('#regions_div').vectorMap({
				map: 'world_mill',
				backgroundColor: "#D7FAFE",
				zoomMax: 20,
				regionStyle: {
					initial: {
						fill: 'white',
						"fill-opacity": .95,
						stroke: '#ededed', "stroke-width": .05, "stroke-opacity": .95
					},
					hover: {
						"fill-opacity": 1
					},
					selected: {
						fill: 'yellow'
					},
					selectedHover: {}
				},
				series: {
					regions: [
						{
							values: data,
							scale: <?php echo $color_scale; ?>,
							normalizeFunction: 'linear',
							attribute: 'fill'
						},
						{
							values: data,
							scale: <?php echo $color_scale; ?>,
							normalizeFunction: 'linear',
							attribute: 'stroke'
						},
					]
				},
				onRegionTipShow: function(e, el, code){
					if ( data[code] == null ) {
						el.html('<div style="display:none; heigth: 0; width: 0; line-height: 0;  padding: 0;">' + el.html() + '</div>');
						jQuery(el).hide();
					} else {
						el.html('<div class="opsi_tip clearfix">'+ code_to_flag[code] +' '+ el.html()+ '<br /><div class="pull-right inovcount">'+ data[code]+' <?php echo __( 'innovations', 'opsi' ); ?></div></div>');
					}
				},
				onRegionLabelShow: function(e, el, code) {
					if (data[code]) {
						el.html('<div class="opsi_tip clearfix">'+code_to_flag[code] +' '+ el.html()+ '<br /><div class="pull-right inovcount">'+ data[code]+' <?php echo __( 'innovations', 'opsi' ); ?></div></div>');
					} else {
						el.html('<div style="display:none heigth: 0; width: 0; line-height: 0; padding: 0;">' + el.html() + '</div>');
						jQuery(el).hide();
					}
				},
				onRegionOver: function(e, code) {
					if (data[code]) {
						var perc = (data[code] / maxValue) * 100;
						jQuery("#curs").css("left", perc + "%");
					}
				},
				onRegionOut: function(e, code) {
					jQuery("#curs").css("left", "-9999px");
				},
				onRegionClick: function(e, code) {

					if ( data[code] > 0 ) {
						jQuery('.countries_widget .widget_content').show();
						country_to_add = code_to_slug[code];
						FWP.refresh();
					} else {
						country_to_add = '';
					}

				}
			});

			jQuery(document).on('facetwp-refresh', function() {
				if ( jQuery.inArray( country_to_add, FWP.facets['countries'] ) == -1 && country_to_add != '' ) {
					FWP.facets['countries'].push( country_to_add );
					country_to_add = '';
				}
			});

			jQuery('.facetwp-facet-countries select').on( 'fs:changed', function() {
				// ptnewval = $( this ).val();
				// console.log(FWP.facets);
				// FWP.refresh();
			});

		});
	</script>
	<?php
}
add_action( 'wp_footer', 'cs_jve_map', 1000 );
