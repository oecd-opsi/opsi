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
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => array( 'with_front' => false, 'slug' => __( 'bi-projects', 'opsi' ) ),
		'capability_type'     => 'post',
	);
	nitro_cpt_creator( __( 'BI Project', 'opsi' ), __( 'BI Projects', 'opsi' ), 'bi-project', __( 'bi-projects', 'opsi' ), 'opsi', 'dashicons-text-page', 15, $bi_projects_args );

	// Create BI Unit Post Type
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
		'menu_icon'           => 'dashicons-groups',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => array( 'with_front' => false, 'slug' => __( 'bi-units', 'opsi' ) ),
		'capability_type'     => 'post',
	);
	nitro_cpt_creator( __( 'BI Unit', 'opsi' ), __( 'BI Unit', 'opsi' ), 'bi-unit', __( 'bi-units', 'opsi' ), 'opsi', 'dashicons-groups', 15, $bi_projects_args );

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
	nitro_taxonomy_creator( __( 'BI Project Methodology', 'opsi' ), __( 'BI Project Methodology', 'opsi' ), 'bi-project', 'opsi', $bi_proj_methodology_args );
  nitro_taxonomy_creator( __( 'BI Project Policy Area', 'opsi' ), __( 'BI Project Policy Area', 'opsi' ), 'bi-project', 'opsi', $bi_proj_methodology_args );
  nitro_taxonomy_creator( __( 'BI Project Topic', 'opsi' ), __( 'BI Project Topic', 'opsi' ), 'bi-project', 'opsi', $bi_proj_methodology_args );
  nitro_taxonomy_creator( __( 'BI Project Behavioural Tool', 'opsi' ), __( 'BI Project Behavioural Tool', 'opsi' ), 'bi-project', 'opsi', $bi_proj_methodology_args );

	// Create Institution taxonomy
	nitro_taxonomy_creator( __( 'BI Institution', 'opsi' ), __( 'BI Institution', 'opsi' ), 'bi-unit', 'opsi', $bi_proj_methodology_args );

}
add_action( 'init', 'opsi_bi_post_types' );
