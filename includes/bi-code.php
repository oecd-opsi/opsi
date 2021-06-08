<?php
// All codes related to BI
// init custom post type
function opsi_bi_post_types() {

	// Create BI Projects Post Type
	$bi_projects_args = array(
		'supports'            => array(
			'title',
			'author',
			'thumbnail',
			'custom-fields',
		),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 15,
		'menu_icon'           => 'dashicons-text-page',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => array( 'with_front' => false, 'slug' => __( 'bi-projects', 'opsi' ) ),
		'capability_type'     => 'post',
	);
	nitro_cpt_creator( __( 'BI Project', 'opsi' ), __( 'BI Projects', 'opsi' ), 'bi-project', __( 'bi-projects', 'opsi' ), 'opsi', 'dashicons-text-page', 15, $bi_projects_args );

	// Create BI Unit Post Type
	$bi_units_args = array(
		'supports'            => array(
			'title',
			'author',
			'thumbnail',
			'custom-fields',
		),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 15,
		'menu_icon'           => 'dashicons-groups',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => array( 'with_front' => false, 'slug' => __( 'bi-units', 'opsi' ) ),
		'capability_type'     => 'post',
	);
	nitro_cpt_creator( __( 'BI Unit', 'opsi' ), __( 'BI Unit', 'opsi' ), 'bi-unit', __( 'bi-units', 'opsi' ), 'opsi', 'dashicons-groups', 15, $bi_units_args );

  // BI project status taxonomy
  $bi_proj_status_args = array(
		'hierarchical'      => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tag_cloud'    => false,
		'show_in_rest'      => true,
	);
	nitro_taxonomy_creator( __( 'BI Project status', 'opsi' ), __( 'BI Project status', 'opsi' ), 'bi-project', 'opsi', $bi_proj_status_args );

  // BI project Methodology, Policy area, Topic, Behavioural tool taxonomies
  $bi_proj_methodology_args = array(
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => false,
		'show_in_nav_menus' => false,
		'show_tag_cloud'    => false,
		'show_in_rest'      => true,
	);
	nitro_taxonomy_creator( __( 'BI Project Methodology', 'opsi' ), __( 'BI Project Methodologies', 'opsi' ), 'bi-project', 'opsi', $bi_proj_methodology_args );
  nitro_taxonomy_creator( __( 'BI Project Policy Area', 'opsi' ), __( 'BI Project Policy Areas', 'opsi' ), array( 'bi-project', 'bi-unit' ), 'opsi', $bi_proj_methodology_args );
  nitro_taxonomy_creator( __( 'BI Project Topic', 'opsi' ), __( 'BI Project Topics', 'opsi' ), 'bi-project', 'opsi', $bi_proj_methodology_args );
  nitro_taxonomy_creator( __( 'BI Project Behavioural Tool', 'opsi' ), __( 'BI Project Behavioural Tools', 'opsi' ), 'bi-project', 'opsi', $bi_proj_methodology_args );

	// Create Institution taxonomy
	nitro_taxonomy_creator( __( 'BI Institution', 'opsi' ), __( 'BI Institution', 'opsi' ), 'bi-unit', 'opsi', $bi_proj_methodology_args );

}
add_action( 'init', 'opsi_bi_post_types' );

// AJAX for Units map
add_action("wp_ajax_unit_map_country_info", "bs_unit_map_country_info");
add_action("wp_ajax_nopriv_unit_map_country_info", "bs_unit_map_country_info");
// define the function to be fired for logged in users
function bs_unit_map_country_info() {

	// Store ISO code in a variable
	$iso_code = $_REQUEST["iso"];

	if( $iso_code == "" )
	 	return;

	// Get the country term from ISO code
	$country_term = get_terms( array(
		'taxonomy'		=> 'country',
		'meta_key'		=> 'iso_code',
		'meta_value'	=> $iso_code,
	) );
	// Country name
	$country_name = $country_term[0]->name;

	 // Number of units in the country
	 $related_units = get_posts( array(
		 'numberposts'	=> -1,
		 'post_type'		=> 'bi-unit',
		 'tax_query' => array(
        array(
            'taxonomy' => 'country',
            'field'    => 'slug',
            'terms'    => $country_term[0]->slug,
        ),
    	),
	 ) );
	 $units_count = count( $related_units );

	 // Number of people and Policy Areas
	 $number_people = 0;
	 $policy_areas = array();
	 $main_policy_areas = '';
	 foreach ( $related_units as $unit ) {
		 // Number of people
	 	$number = get_field( 'your_team_how_many_people_including_yourself_apply_behavioral_science_in_your_team', $unit );
		$number_people += $number;
		// Policy area
		$policy_terms = get_the_terms( $unit, 'bi-project-policy-area' );
		foreach ( $policy_terms as $policy_term) {
			++$policy_areas[$policy_term->name];
		}
		arsort( $policy_terms );
		$policy_terms = array_slice( $policy_terms, 0, 3 );
		$main_policy_areas = implode( ', ', $policy_terms );
	 }

	 $result = array(
		 'name'					=> $country_name,
		 'unitsnum'			=> $units_count,
		 'peoplenum'		=> $number_people,
		 'policyareas'	=> $main_policy_areas,
	 );

   // Check if action was fired via Ajax call. If yes, JS code will be triggered, else the user is redirected to the post page
   if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      $result = json_encode($result);
      echo $result;
   }

   // don't forget to end your scripts with a die() function - very important
   die();
}
