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
add_action("wp_ajax_unit_map_unit_info", "bs_unit_map_unit_info");
add_action("wp_ajax_nopriv_unit_map_unit_info", "bs_unit_map_unit_info");
// define the function to be fired for logged in users
function bs_unit_map_unit_info() {

	// Store Unit slug in a variable
	$unit_slug = $_REQUEST["slug"];

	if( $unit_slug == "" )
	 	return;

	// Get Unit post object
	$args = array(
		'name'						=> $unit_slug,
		'post_type'				=> 'bi-unit',
		'post_status' 		=> 'publish',
    'posts_per_page'	=> 1
	);
	$units = get_posts( $args );
	$unit = $units[0];

	// Get the Unit name
	$unit_name = $unit->post_title;

	// Get the Institution
	$institutions = get_the_terms( $unit->ID, 'bi-institution' );
	$institution_name = $institutions[0]->name;

	// Get the number of pre-registration projects of this Unit
	$preregistration_projects = get_posts( array(
		'numberposts'	=> -1,
		'post_type'		=> 'bi-project',
		'meta_key'		=> 'who_is_behind_the_project_unit',
		'meta_value'	=>  $unit->ID,
		'tax_query' => array(
      array(
        'taxonomy' => 'bi-project-status',
        'field'    => 'slug',
        'terms'    => 'pre-registration',
      ),
    ),
	) );
	$preregistration_count = count( $preregistration_projects );

	// Get the number of completed projects of this Unit
	$completed_projects = get_posts( array(
		'numberposts'	=> -1,
		'post_type'		=> 'bi-project',
		'meta_key'		=> 'who_is_behind_the_project_unit',
		'meta_value'	=>  $unit->ID,
		'tax_query' => array(
      array(
        'taxonomy' => 'bi-project-status',
        'field'    => 'slug',
        'terms'    => 'completed',
      ),
    ),
	) );
	$completed_count = count( $completed_projects );

	// Total number of projects
	$total_projects = get_posts( array(
		'numberposts'	=> -1,
		'post_type'		=> 'bi-project',
		'meta_key'		=> 'who_is_behind_the_project_unit',
		'meta_value'	=>  $unit->ID,
	) );
	$project_count = count( $total_projects );

	// Team size
	$team_size = get_field( 'your_team_how_many_people_including_yourself_apply_behavioral_science_in_your_team', $unit->ID );

	// Policy Areas
	$policy_areas = strip_tags( get_the_term_list( $unit->ID, 'bi-project-policy-area', '', ', ') );

	// Activities
	$activity_list = '';
	$activity_values = get_field( 'activities_ which_of_the_following_activities_has_your_unit_been_involved_in', $unit->ID );
	foreach ($activity_values as $activity) {
		$activity_list .= $activity['label'] . ', ';
	}
	$activity_list = rtrim( $activity_list, ', ' );

	// Unit URL
	$unit_url = get_permalink( $unit->ID );

	$result = array(
		'unit_name'				=> $unit_name,
		'institution'			=> $institution_name,
		'preregistration'	=> $preregistration_count,
		'completed'				=> $completed_count,
		'total_projects'	=> $project_count,
		'team_size'				=> $team_size,
		'policy_areas'		=> $policy_areas,
		'activities'			=> $activity_list,
		'url'							=> $unit_url,
	);

	// Check if action was fired via Ajax call. If yes, JS code will be triggered, else the user is redirected to the post page
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	  $result = json_encode($result);
	  echo $result;
	}

	// don't forget to end your scripts with a die() function - very important
	die();
}

// AJAX for Projects map
add_action("wp_ajax_project_map_country_info", "bs_project_map_country_info");
add_action("wp_ajax_nopriv_project_map_country_info", "bs_project_map_country_info");
// define the function to be fired for logged in users
function bs_project_map_country_info() {

	// Store Country slug in a variable
	$country_iso = $_REQUEST["iso"];

	if( $country_iso == "" )
	 	return;

	// Get Country term object
	$countries = get_terms( array(
		'taxonomy'               => array( 'country' ),
		'meta_key'               => 'iso_code',
		'meta_value'             => $country_iso,
	) );
	$country_obj = $countries[0];


	// Get the Country name
	$country_name = $country_obj->name;

	// Get the number of pre-registration projects of this Country
	$preregistration_projects = get_posts( array(
		'numberposts'	=> -1,
		'post_type'		=> 'bi-project',
		'tax_query' => array(
			'relation'	=> 'AND',
      array(
        'taxonomy' => 'bi-project-status',
        'field'    => 'slug',
        'terms'    => 'pre-registration',
      ),
			array(
				'taxonomy' => 'country',
				'field'		 => 'term_id',
				'terms'		 => array($country_obj->term_id)
			),
    ),
	) );
	$preregistration_count = count( $preregistration_projects );

	// Get the number of completed projects of this Country
	$completed_projects = get_posts( array(
		'numberposts'	=> -1,
		'post_type'		=> 'bi-project',
		'tax_query' => array(
			'relation'	=> 'AND',
      array(
        'taxonomy' => 'bi-project-status',
        'field'    => 'slug',
        'terms'    => 'completed',
      ),
			array(
				'taxonomy' => 'country',
				'field'		 => 'term_id',
				'terms'		 => array($country_obj->term_id)
			),
    ),
	) );
	$completed_count = count( $completed_projects );

	// Total number of projects
	$total_projects = get_posts( array(
		'numberposts'	=> -1,
		'post_type'		=> 'bi-project',
		'tax_query' => array(
			array(
				'taxonomy' => 'country',
				'field'		 => 'term_id',
				'terms'		 => array($country_obj->term_id)
			),
    ),
	) );
	$projects_count = count( $total_projects );

	// Policy Areas
	$policy_areas = '';
	$policy_areas = strip_tags( get_the_term_list( $unit->ID, 'bi-project-policy-area', '', ', ') );
	$policies_array = array();
	foreach ($total_projects as $project) {
		$project_policies = get_the_terms( $project, 'bi-project-policy-area' );
		array_push( $policies_array, $project_policies );
	}
	$policies_array = my_array_unique( $policies_array );
	foreach ($policies_array as $policy) {
		$policy_areas .= $policy->name . ', ';
	}
	$policy_areas = rtrim( $policy_areas, ', ' );


	$result = array(
		'country_name'		=> $country_name,
		'preregistration'	=> $preregistration_count,
		'completed'				=> $completed_count,
		'total_projects'	=> $projects_count,
		'policy_areas'		=> $policy_areas,
	);

	// Check if action was fired via Ajax call. If yes, JS code will be triggered, else the user is redirected to the post page
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	  $result = json_encode($result);
	  echo $result;
	}

	// don't forget to end your scripts with a die() function - very important
	die();
}

function my_array_unique($array, $keep_key_assoc = false){
    $duplicate_keys = array();
    $tmp = array();

    foreach ($array as $key => $val){
        // convert objects to arrays, in_array() does not support objects
        if (is_object($val))
            $val = (array)$val;

        if (!in_array($val, $tmp))
            $tmp[] = $val;
        else
            $duplicate_keys[] = $key;
    }

    foreach ($duplicate_keys as $key)
        unset($array[$key]);

    return $keep_key_assoc ? $array : array_values($array);
}

// Add BI Projects BP subtab ***START***
function profile_tab_bi_projects() {
  global $bp;

	$user_id = bp_displayed_user_id();
	$current_user_id = bp_loggedin_user_id();

	$count_args_owner = array(
		'post_type' => array ( 'bi-project' ),
		'post_status' => array( 'any', 'draft', 'pending', 'publish' ),
	);
	$count_args_guest = array(
		'post_type' => array ( 'bi-project' ),
		'post_status' => array( 'publish' ),
	);

	$all_posts = nitro_get_user_posts_count( $user_id, $count_args_owner );
	$published_posts = nitro_get_user_posts_count( $user_id, $count_args_guest );

	$count_inno = 0;

	if ( $user_id == $current_user_id ) {
		$count_inno = $all_posts;
	} else {
		$count_inno = $published_posts;
	}

	$innonum = '';

	if ( $count_inno == 0 ) {
		$innonum = ' <span class="no-count">'. $count_inno .'</span>';
	} else {
		$innonum = ' <span class="count">'. $count_inno .'</span>';
	}

  bp_core_new_nav_item( array(
    'name' => __( 'BI Projects', 'opsi' ).$innonum,
    'slug' => 'bi-projects',
    'screen_function' => 'bs_my_bi_projects_screen',
    'position' => 70,
    'parent_url'      => bp_loggedin_user_domain() . '/bi-units/',
    'parent_slug'     => $bp->profile->slug,
    'default_subnav_slug' => 'bi-projects'
  ) );
}
add_action( 'bp_setup_nav', 'profile_tab_bi_projects' );

function bs_my_bi_projects_screen(){
  global $bp;
  add_action( 'bp_template_title', 'bp_my_bi_projects_screen_title' );
  add_action( 'bp_template_content', 'bp_my_bi_projects_screen_content' );
	// add_filter( 'bp_get_template_part', 'bp_innovations_template_part_filter', 10, 3 );
  bp_core_load_template( array ( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) ) );
}

function bp_my_bi_projects_screen_title() {
  // global $bp;
	// echo __( 'Innovations', 'opsi' );
	return;
}

function bp_my_bi_projects_screen_content() {
  global $bp;

	/**
	 * Fires before the display of the member activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_activity_post_form' ); ?>

	<?php
	if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) )
		bp_get_template_part( 'activity/post-form' );

	/**
	 * Fires after the display of the member activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_activity_post_form' );

  echo bp_get_author_bi_projects_list();

}

// build author Innovations / Case Studies list
function bp_get_author_bi_projects_list( $user_id = 0 ) {

	if ($user_id == 0) {
    $user_id = bp_displayed_user_id();
  }
  if (!$user_id) {
    return false;
  }

  $current_user_id = bp_loggedin_user_id();

	if ( $user_id == $current_user_id ) {
		return bp_bi_projects_list_owner();
	} else {
		return bp_bi_projects_list_guest();
	}

}

function bp_bi_projects_list_owner() {

	// WP_Query arguments
	$args = array(
		'post_type'		=> array( 'bi-project' ),
		'post_status'	=> array( 'any', 'draft', 'pending', 'publish' ),
		'author'		=> bp_loggedin_user_id(),
		'posts_per_page'=> -1
	);

	// The Query
	$query = new WP_Query( $args );

	$out  = '';

	ob_start();

	// The Loop
	if ( $query->have_posts() ) {

		?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<th><?php echo __( 'Title', 'opsi' ); ?></th>
					<th><?php echo __( 'Status', 'opsi' ); ?></th>
					<th class="text-center" colspan="3"><?php echo __( 'Actions', 'opsi' ); ?></th>
				</thead>
				<tbody>

		<?php

		while ( $query->have_posts() ) {

			$query->the_post();

			$post_status_obj = get_post_status_object( get_post_status( get_the_ID() ) );
			$edit_url = get_field('bi_project_form_page', 'option');
			?>

			<tr>
				<td>
					<?php
						if ( get_post_status( get_the_ID() ) == 'publish' ) {
							$post_url = get_permalink();
						}	else {
							$post_url = get_preview_post_link(get_the_ID());
							$post_url = str_replace( '&preview=true', '', $post_url );
							$post_url = str_replace( '?preview=true', '', $post_url );
						}
					?>
					<a href="<?php echo $post_url ?>" title="<?php echo __( 'view', 'opsi' ); ?>">
						<?php the_title(); ?>
					</a>
				</td>
				<td>
					<?php
						if ( get_post_status( get_the_ID() ) == 'pending' ) {
							echo __( 'Submitted (pending review)', 'opsi' );
						} else {
							echo $post_status_obj->label;
						}
					?>
				</td>
				<td>
					<a href="<?php the_permalink(); ?>" title="<?php echo __( 'view', 'opsi' ); ?>">
						<i class="fa fa-search" aria-hidden="true"></i>
					</a>
				</td>
				<td>
					<a href="<?php echo get_the_permalink( $edit_url ).'?edit='. get_the_ID(); ?>" title="<?php echo __( 'edit', 'opsi' ); ?>">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</a>
				</td>
				<td>
				<?php
					$get_post_status = get_post_status();
					if ( can_delete_cs( get_the_ID(), bp_loggedin_user_id() ) ) { ?>
						<a href="<?php echo get_the_permalink( $edit_url ).'?delete='. get_the_ID(); ?>" title="<?php echo __( 'remove', 'opsi' ); ?>" class="danger">
							<i class="fa fa-trash-o" aria-hidden="true"></i>
						</a>
					<?php } ?>
				</td>
			</tr>
			<?php

		}

		?>
				</tbody>
			</table>
		</div>
		<?php

	} else {

		?>
		<div id="message" class="info">
			<p><?php echo __( 'Sorry, there was no entries found.', 'opsi' ); ?></p>
		</div>
		<?php
	}

	// Restore original Post Data
	wp_reset_postdata();

	$out = ob_get_clean();

	return $out;

}

function bp_bi_projects_list_guest() {

	// WP_Query arguments
	$args = array(
		'post_type'		=> array( 'bi-project' ),
		'post_status'	=> array( 'publish' ),
		'author'		=> bp_displayed_user_id(),
		'posts_per_page'=> -1
	);

	// The Query
	$query = new WP_Query( $args );

	$out  = '';

	ob_start();

	// The Loop
	if ( $query->have_posts() ) {

		?>
			<ul>

		<?php

		while ( $query->have_posts() ) {
			$query->the_post();
			?>

			<li><a href="<?php the_permalink(); ?>" title="<?php echo __( 'view', 'opsi' ); ?>"><?php the_title(); ?></a></li>

			<?php

		}

		?>
			</ul>
		<?php

	} else {

		?>
		<div id="message" class="info">
			<p><?php echo __( 'Sorry, there was no entries found.', 'opsi' ); ?></p>
		</div>
		<?php
	}

	// Restore original Post Data
	wp_reset_postdata();

	$out = ob_get_clean();

	return $out;

}
// Add BI Unit BP subtab ***END***
