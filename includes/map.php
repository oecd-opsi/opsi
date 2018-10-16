<?php

function cs_jve_map() {
	if ( !( is_post_type_archive( 'case' ) || is_tax( 'innovation-tag' ) || is_tax( 'country' ) || is_tax( 'innovation-badge' ) ) ) { return; }

	$wp_country_terms = get_terms( 'country' , array( 'hide_empty' => true, 'fields' => 'all' ) );
	$data = '';
	$code_to_flag = '';
	$code_to_slug = '';
	$max_count = 0;
	if ( !empty( $wp_country_terms ) ) {
		foreach ( $wp_country_terms as $country_term ) {
			$iso = trim( get_field( 'iso_code', $country_term ) );
			$data .= '"'. $iso .'": '. $country_term->count .','; $data .= "\n\t";
			$code_to_flag .= '"'. $iso .'": "<img src=\''. get_stylesheet_directory_uri().'/images/flags/'.$iso.'.png\' width=\'24\' height=\'24\' >",'; $code_to_flag .= "\n\t";
			$code_to_slug .= '"'. $iso .'": "'. $country_term->slug .'",'; $code_to_slug .= "\n\t";
			
			if ( $country_term->count > $max_count ) {
				$max_count = $country_term->count;
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
							scale: ['#d5bcf7', '#165580'],
							normalizeFunction: 'linear',
							attribute: 'fill'
						},
						{
							values: data,
							scale: ['#d5bcf7','#165580'],
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