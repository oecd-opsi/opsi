<?php

function cs_jve_bi_map() {
	if ( ! (
		is_post_type_archive( 'bi-project' )
	) ) {
		return;
	}

	$color_scale = 'bi-project' == get_post_type() ? "['#d5bcf7', '#165580']" : "['#b4d5ed', '#0f1570']";

	$wp_country_terms = get_terms( 'country' , array( 'hide_empty' => true, 'fields' => 'all' ) );

	$data = '';
	$coords = '';
	$values = '';
	$code_to_flag = '';
	$code_to_slug = '';
	$max_count = 0;
	if ( !empty( $wp_country_terms ) ) {
		foreach ( $wp_country_terms as $country_term ) {
			$count = count_posts_in_term( 'country', $country_term->slug, get_post_type() );
			$iso = trim( get_field( 'iso_code', $country_term ) );
			$data .= '"'. $iso .'": '. $count .',';
			$ltn_lng =  get_field( 'latitude_and_longitude', $country_term );
			$coords .= '"'. $iso .'": ['. $ltn_lng .'],';
			// $values .= $count.','; $values .= "\n\t";
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
			var coords = {
				<?php echo $coords; ?>
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
				markers: coords,
				backgroundColor: "#D7FAFE",
				zoomMax: 20,
				regionStyle: {
					initial: {
						fill: 'white',
						"fill-opacity": .95,
						stroke: '#0f1570', "stroke-width": .05, "stroke-opacity": 1
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
					markers: [{
	          attribute: 'fill',
	          scale: <?php echo $color_scale; ?>,
	          values: data,
	          // min: 0,
	          // max: maxValue
	        },{
	          attribute: 'r',
	          scale: [0, 10],
	          values: data,
	          // min: 0,
	          // max: maxValue
	        }],
					regions: [
						// {
						// 	values: data,
						// 	scale: <?php echo $color_scale; ?>,
						// 	normalizeFunction: 'linear',
						// 	attribute: 'fill'
						// },
						// {
						// 	values: data,
						// 	scale: <?php echo $color_scale; ?>,
						// 	normalizeFunction: 'linear',
						// 	attribute: 'stroke'
						// },
					]
				},
				onMarkerTipShow: function(e, el, code){
					if ( data[code] == null ) {
						el.html('<div style="display:none; heigth: 0; width: 0; line-height: 0;  padding: 0;">' + el.html() + '</div>');
						jQuery(el).hide();
					} else {
						el.html('<div class="opsi_tip clearfix">'+ code_to_flag[code] +' '+ el.html()+ '<br /><div class="pull-right inovcount">'+ data[code]+' <?php echo __( 'projects', 'opsi' ); ?></div></div>');
					}
				},
				onMarkerLabelShow: function(e, el, code) {
					if (data[code]) {
						el.html('<div class="opsi_tip clearfix">'+code_to_flag[code] +' '+ el.html()+ '<br /><div class="pull-right inovcount">'+ data[code]+' <?php echo __( 'projects', 'opsi' ); ?></div></div>');
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
add_action( 'wp_footer', 'cs_jve_bi_map', 1000 );

function cs_jve_bi_unit_map() {
	if ( ! (
		is_post_type_archive( 'bi-unit' )
	) ) {
		return;
	}

	$color_scale = 'bi-unit' == get_post_type() ? "['#d5bcf7', '#165580']" : "['#b4d5ed', '#0f1570']";

	// WP_Query arguments
	$args = array(
		'post_type'              => array( 'bi-unit' ),
		'post_status'            => array( 'publish' ),
		'nopaging'							 => true,
		'posts_per_page'    		 => '-1',
	);

	// The Query
	$query = new WP_Query( $args );

	$data = '';
	$coords = '';
	$name = '';
	$countries = '';
	$values = '';
	$code_to_flag = '';
	$code_to_slug = '';
	$iso_slug= '';
	$max_count = 0;

	// The Loop
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$id = get_the_id();
			$slug = get_post_field( 'post_name', $id );
			$country = get_the_terms( $id, 'country' );
			$iso = get_field( 'iso_code', $country[0] );
			$country_slug = $country[0]->slug;

			// Number of projects
			$related_projects = get_posts( array(
				'numberposts'	=> -1,
				'post_type'		=> 'bi-project',
				'meta_key'		=> 'who_is_behind_the_project_unit',
				'meta_value'	=>  $id
			) );
			$projects_count = count( $related_projects );

			// Data
			$data .= '"'. $slug .'": '. $projects_count .',';

			if ( $projects_count > $max_count ) {
				$max_count = $projects_count;
			}

			// Coords
			$lat_lng = get_field( 'unit_latitude_and_longitude', $id );

			if( ! preg_match( '/-?[0-9]+\.[0-9]+\s*,\s*-?\s*[0-9]+\.[0-9]+/', $lat_lng ) ) {
			   // If no valid coords for the Unit, get the Country coords
			   $lat_lng =  get_field( 'latitude_and_longitude', $country[0] );
			}
			$coords .= '"'. $slug .'": ['. $lat_lng .'],';
			// $values .= $count.','; $values .= "\n\t";
			// $code_to_flag .= '"'. $iso .'": "<img src=\''. get_stylesheet_directory_uri().'/images/flags/'.$iso.'.png\' width=\'24\' height=\'24\' >",'; $code_to_flag .= "\n\t";
			// $code_to_slug .= '"'. $iso .'": "'. $country_term->slug .'",'; $code_to_slug .= "\n\t";

			// Countries
			$countries .= '"'. $iso .'": 1,';

			// ISO: slug array
			$iso_slug .= '"'. $iso .'": "'. $country_slug .'",';

			// Unit names
			$names .= '"'. $slug .'": "'. get_the_title( $id ) .'",';

		}
	}

	// Restore original Post Data
	wp_reset_postdata();

	?><script type="text/javascript">
		jQuery(document).ready(function(){

			var data = {
				<?php echo $data; ?>
			};
			var coords = {
				<?php echo $coords; ?>
			};
			var names = {
				<?php echo $names; ?>
			};
			var countries = {
				<?php echo $countries; ?>
			};
			var isoSlug = {
				<?php echo $iso_slug; ?>
			};
			var maxValue = <?php echo $max_count;  ?>;
			var country_to_add = '';

			jQuery('#map-bar .max').text(maxValue);

			jQuery('#regions_div').vectorMap({
				map: 'world_mill',
				markers: coords,
				backgroundColor: "#D7FAFE",
				zoomMax: 20,
				regionStyle: {
					initial: {
						fill: 'white',
						"fill-opacity": .95,
						stroke: '#0f1570', "stroke-width": .05, "stroke-opacity": 1
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
					markers: [{
	          attribute: 'fill',
	          scale: <?php echo $color_scale; ?>,
	          values: data,
	          // min: 0,
	          // max: maxValue
	        },{
	          attribute: 'r',
	          scale: [0, 10],
	          values: data,
	          // min: 0,
	          // max: maxValue
	        }],
					regions: [
						// {
						// 	values: data,
						// 	scale: <?php //echo $color_scale; ?>,
						// 	normalizeFunction: 'linear',
						// 	attribute: 'fill'
						// },
						// {
						// 	values: data,
						// 	scale: <?php //echo $color_scale; ?>,
						// 	normalizeFunction: 'linear',
						// 	attribute: 'stroke'
						// },
					]
				},
				onMarkerTipShow: function(e, el, code){
					if ( names[code] == null ) {
						el.html('<div style="display:none; heigth: 0; width: 0; line-height: 0;  padding: 0;">' + el.html() + '</div>');
						jQuery(el).hide();
					} else {
						el.html('<div class="opsi_tip clearfix">'+ el.html()+ '<br /><div class="pull-right inovcount">'+ names[code]+' </div></div>');
					}
				},
				onMarkerLabelShow: function(e, el, code) {
					if (names[code]) {
						el.html('<div class="opsi_tip clearfix">'+ el.html()+ '<br /><div class="pull-right inovcount">'+ names[code]+' <?php echo __( 'units', 'opsi' ); ?></div></div>');
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
				onRegionClick: function(e, code, isSelected, selectedRegions) {

					jQuery('#regions_div').vectorMap('get','mapObject').setFocus({region: code, animate: true});
					jQuery('#regions_div').addClass('zoomed-map');

					jQuery.ajax({
		         type : "post",
		         dataType : "json",
		         url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",
		         data : {
							 action: "unit_map_country_info",
							 iso: code
						 },
		         success: function(response) {
		            // if ( response.type == "success" ) {
		              console.log(response);
		            // } else {
		            //   console.log('error');
		            // }
		         }
		      });

					if ( countries[code] > 0 ) {
						jQuery('.countries_widget .widget_content').show();
						country_to_add = isoSlug[code];
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

			jQuery('.back-global-view').on( 'click', function() {
				jQuery('#regions_div').removeClass('zoomed-map');
				jQuery('#regions_div').vectorMap('get','mapObject').setFocus({scale: 0, x: 0.5, y: 0.5, animate: true});
			});

		});
	</script>
	<?php
}
add_action( 'wp_footer', 'cs_jve_bi_unit_map', 1000 );
