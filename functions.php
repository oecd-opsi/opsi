<?php

  add_theme_support( 'post-thumbnails' );

  add_action( 'after_setup_theme', 'nitro_theme_setup' );
  function nitro_theme_setup() {
    add_image_size( 'slider', 1920, 872, true );
    add_image_size( 'blog', 848, 288, true );
    add_image_size( 'blog_thumb', 270, 160, true );
    add_image_size( 'tiny', 46, 46, true );
    load_theme_textdomain( 'opsi', get_template_directory() . '/languages' );
    add_theme_support( 'html5', array( 'search-form' ) );
  }
add_action('after_setup_theme', 'remove_admin_bar');

// function remove_admin_bar() {
// if (!current_user_can('administrator') && !is_admin()) {
//   show_admin_bar(false);
// }
// }

  add_filter( 'jpeg_quality', create_function( '', 'return 85;' ) );
  add_filter('acf-image-crop/image-quality', 85);
  add_filter('widget_text', 'do_shortcode');

  /*-----------------------------------------------------------------------------------*/
  /*	Widgets
  /*-----------------------------------------------------------------------------------*/

	// make text widgets run shortcodes
	add_filter('widget_text', 'do_shortcode');

	require_once('widgets/archives-accordion.php');
	require_once('widgets/blogposts.php');
	require_once('shortcodes/user-shortcodes.php');
	require_once('shortcodes/generic-shortcodes.php');
	require_once('includes/acf-hooks.php');
	require_once('includes/collaborators.php');
	require_once('includes/map.php');
	require_once('includes/buddypress.php');



  /*-----------------------------------------------------------------------------------*/
  /*	Sidebars
  /*-----------------------------------------------------------------------------------*/

  //Register Sidebars
  if ( function_exists('register_sidebar') ) {

  function nitro_register_sidebars() {

    register_sidebar(array(
      'name' => 'Sidebar',
      'id' => 'sidebar',
      'description' => 'Widgets in this area will be shown in right sidebar position.',
      'before_widget' => '<aside id="%1$s" class="widget sidebar-box sidebar-right %2$s">',
      'after_widget' => '</div></aside>',
      'before_title' => '<h2 class="widget-title">',
      'after_title' => '</h2><div class="widget_content collapse-xs">',
    ));
    register_sidebar(array(
      'name' => 'Blog',
      'id' => 'blog',
      'description' => 'Widgets in this area will be shown in right sidebar position on blog only.',
      'before_widget' => '<aside id="%1$s" class="widget sidebar-box sidebar-right %2$s">',
      'after_widget' => '</div></aside>',
      'before_title' => '<h2 class="widget-title">',
      'after_title' => '</h2><div class="widget_content collapse-xs">',
    ));

    register_sidebar(array(
      'name' => 'Single Blog',
      'id' => 'singleblog',
      'description' => 'Widgets in this area will be shown in right sidebar position on single blog entry only.',
      'before_widget' => '<aside id="%1$s" class="widget sidebar-box sidebar-right %2$s">',
      'after_widget' => '</div></aside>',
      'before_title' => '<h2 class="widget-title">',
      'after_title' => '</h2><div class="widget_content collapse-xs">',
    ));
    register_sidebar(array(
      'name' => 'Buddypress',
      'id' => 'buddypress',
      'description' => 'Widgets in this area will be shown in right sidebar position on Buddypress pages only.',
      'before_widget' => '<aside id="%1$s" class="widget sidebar-box sidebar-right buddypress_aside %2$s">',
      'after_widget' => '</div></aside>',
      'before_title' => '<h2 class="widget-title">',
      'after_title' => '</h2><div class="widget_content collapse-xs">',
    ));
    register_sidebar(array(
      'name' => 'Case Study Form Sidebar',
      'id' => 'casestudyformsidebar',
      'description' => 'Widgets in this area will be shown on left sidebar position on Case Study Form only.',
      'before_widget' => '<aside id="%1$s" class="widget sidebar-box sidebar-left csf_aside %2$s">',
      'after_widget' => '</div></aside>',
      'before_title' => '<h2 class="widget-title">',
      'after_title' => '</h2><div class="widget_content collapse-xs">',
    ));
    register_sidebar(array(
      'name' => 'Case Study Archive Sidebar',
      'id' => 'sidebar_case_study',
      'description' => 'Widgets in this area will be shown on right sidebar position on Case Study Archive only.',
      'before_widget' => '<aside id="%1$s" class="widget sidebar-box sidebar-left csa_aside %2$s">',
      'after_widget' => '</div></aside>',
      'before_title' => '<h2 class="widget-title">',
      'after_title' => '</h2><div class="widget_content collapse-xs">',
    ));
    register_sidebar(array(
      'name' => 'Case Study Archive Sidebar for OpenGov branding',
      'id' => 'sidebar_case_study_opengov_brand',
      'description' => 'Widgets in this area will be shown on right sidebar position on OpenGov Case Study Archive only.',
      'before_widget' => '<aside id="%1$s" class="widget sidebar-box sidebar-left csa_aside %2$s">',
      'after_widget' => '</div></aside>',
      'before_title' => '<h2 class="widget-title">',
      'after_title' => '</h2><div class="widget_content collapse-xs">',
    ));
    register_sidebar(array(
      'name' => 'Case Study Open Government Branding',
      'id' => 'sidebar_case_study_open_gov_left',
      'description' => 'Widgets in this area will be shown on top-left sidebar position on Open Government Case Study page.',
      'before_widget' => '<aside id="%1$s" class="widget sidebar-box sidebar-open-gov-left csa_aside %2$s">',
      'after_widget' => '</aside>',
      'before_title' => '<h2 class="widget-title">',
      'after_title' => '</h2>',
    ));


   }


   add_action( 'widgets_init', 'nitro_register_sidebars' );
  }

  register_nav_menu( 'primary', 'Primary Menu' );
  register_nav_menu( 'mobile', 'Mobile Extra Menu' );


  add_filter( 'wp_nav_menu_items', 'mobile_custom_menu_item', 10, 2 );
  function mobile_custom_menu_item ( $items, $args ) {
      if ($args->theme_location == 'mobile') {
          $items = '<li><a href="#" class="search_mobile"><i class="fa fa-search" aria-hidden="true"></i></a></li>'.$items;
      }
      return $items;
  }

// fix file upload permissions bug (untacched files showing)
function my_files_only( $wp_query ) {

	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'query-attachments' && strpos($_SERVER['HTTP_REFERER'], '/case-study-form/') !== false ) {

        if ( !( current_user_can('editor') || current_user_can('administrator') ) ) {
            global $current_user;
            $wp_query->set( 'author', $current_user->id );
        }
    }

}
add_filter('parse_query', 'my_files_only' );


add_action( 'admin_enqueue_scripts', 'opsi_load_admin_style' );
function opsi_load_admin_style() {
	wp_enqueue_style( 'opsi_admin_css', get_template_directory_uri() . '/css/admin-style.css', false, '1.0.0' );
	wp_enqueue_script( 'opsi_admin_js', get_template_directory_uri() . '/js/admin-js.js', false, '1.0.0' );
}

  function loadcss() {
    wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
    wp_register_style('bootstrap_theme', get_template_directory_uri() . '/css/bootstrap-theme.min.css');
    wp_register_style('font_awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
    wp_register_style('genstyle', get_template_directory_uri() . '/style.css', array(), uniqid(), 'screen');
    wp_register_style('google-font','https://fonts.googleapis.com/css?family=Open+Sans:400,700,300,800', array(), false, 'screen');

    wp_enqueue_style( 'bootstrap' );
    wp_enqueue_style( 'bootstrap_theme' );
    wp_enqueue_style( 'font_awesome' );

	if ( is_post_type_archive( 'case' ) || is_tax( 'case_type' ) || is_tax( 'innovation-tag' ) || is_tax( 'country' ) || is_tax( 'innovation-badge' ) || is_tax( 'innovation-tag-opengov' ) || is_tax( 'innovation-badge-opengov' ) ) {
		wp_register_style('jve_css', get_template_directory_uri() . '/css/jquery-jvectormap-2.0.3.css', array(), false, 'screen');
		wp_enqueue_style( 'jve_css' );
	}

    wp_enqueue_style( 'genstyle' );
    wp_enqueue_style( 'google-font' );

    if ( is_page_template('templates/template-case-study-form-open-government.php') ) {
      $custom_css = "#acf-field_5ae7ab3b5dd80-, .acf-field-5bc60b5d239b4 {display: none;}";
    	wp_add_inline_style( 'genstyle', $custom_css );
    }

    // fancybox
    wp_register_style('fancybox_css', get_template_directory_uri() . '/css/jquery.fancybox.min.css', array(), false, 'screen');
    wp_enqueue_style( 'fancybox_css' );




  }

  function loadjs() {
    wp_enqueue_script('jquery');

	wp_enqueue_media();

    wp_register_script('bootstrap_script', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ));
    wp_enqueue_script('bootstrap_script');

    wp_register_script('matchHeight_script', get_template_directory_uri() . '/js/jquery.matchHeight-min.js', array( 'jquery' ));
    wp_enqueue_script('matchHeight_script');

    // fancybox

    wp_register_script('fancybox_js', get_template_directory_uri() . '/js/jquery.fancybox.min.js', array( 'jquery' ));
    wp_enqueue_script('fancybox_js');

    // steps

    wp_register_script('steps_js', get_template_directory_uri() . '/js/jquery.steps.min.js', array( 'jquery' ));
    wp_enqueue_script('steps_js');

	if ( is_post_type_archive( 'case' ) || is_tax( 'case_type' ) || is_tax( 'innovation-tag' ) || is_tax( 'country' ) || is_tax( 'innovation-badge' ) || is_tax( 'innovation-tag-opengov' ) || is_tax( 'innovation-badge-opengov' ) ) {
		// wp_register_script('google_charts_js', 'https://www.gstatic.com/charts/loader.js', array( 'jquery' ));
		// wp_enqueue_script('google_charts_js');
		wp_register_script('jve_js', get_template_directory_uri() . '/js/jquery-jvectormap-2.0.3.min.js', array( 'jquery' ));
		wp_enqueue_script('jve_js');
		wp_register_script('jvewm_js', get_template_directory_uri() . '/js/jquery-jvectormap-world-mill.js', array( 'jquery' ));
		wp_enqueue_script('jvewm_js');
	}


    // map
    // wp_register_script('gmap', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=falses');
    // wp_enqueue_script('gmap');

    // custom
    wp_register_script('custom_script', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), uniqid());
    wp_enqueue_script('custom_script');

  }

  add_action("wp_enqueue_scripts", "loadcss", 13);
  add_action("wp_enqueue_scripts", "loadjs", 14);





  // add ie (internet explorer) body class
  function ie_body_class($c) {
    global $is_IE;
    if ($is_IE == true) {
      $c[] = 'is_ie';
    }

    $user_agent = getenv("HTTP_USER_AGENT");

    if(strpos($user_agent, "Win") !== FALSE) {
      $c[] = "is_win";
    }
    elseif(strpos($user_agent, "Mac") !== FALSE) {
      $c[] = "is_mac";
    }

    return $c;
  }
  add_filter('body_class', 'ie_body_class');



  /*  MENU */

class My_Custom_Nav_Walker extends Walker_Nav_Menu {

   function start_lvl(&$output, $depth = 0, $args = array()) {
      $output .= "\n<div class=\"dropdown-menu\">\n<div class=\"container\">\n<ul class=\"dropdown-menu-inner\">\n";
   }

   public function end_lvl( &$output, $depth = 0, $args = array() ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );
        $output .= "$indent</ul></div></div>{$n}";
    }

   function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
       $item_html = '';
       parent::start_el($item_html, $item, $depth, $args);

       if ( $item->is_dropdown && $depth === 0 ) {
           $item_html = str_replace( '<a', '<a class="dropdown-toggle disabled" data-toggle="dropdown"', $item_html );
           $item_html = str_replace( '</a>', ' <b class="caret"></b></a> <span class="glyphicon glyphicon-chevron-down droptoggle"></span><span class="glyphicon glyphicon-chevron-up droptoggle"></span>', $item_html );
       }

       if ($item->description != '' && in_array('htmlmenu', $item->classes)) {
        $item_html = str_replace('</a>', '<span class="description">'.str_replace('\n', '<br />', __($item->description, 'opsi')).'</span></a>', $item_html);
      }

       $output .= $item_html;
    }

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
        if ( $element->current )
        $element->classes[] = 'active';

        $element->is_dropdown = !empty( $children_elements[$element->ID] );

        if ( $element->is_dropdown ) {
            if ( $depth === 0 ) {
                $element->classes[] = 'dropdown';
            } elseif ( $depth === 1 ) {
                // Extra level of dropdown menu,
                // as seen in http://twitter.github.com/bootstrap/components.html#dropdowns
                $element->classes[] = 'dropdown-submenu';
            }
        }

    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}

// Allow HTML descriptions in WordPress Menu
remove_filter( 'nav_menu_description', 'strip_tags' );
add_filter( 'wp_setup_nav_menu_item', 'nitro_wp_setup_nav_menu_item' );
function nitro_wp_setup_nav_menu_item( $menu_item ) {
    if (in_array('htmlmenu', $menu_item->classes)) {
      $menu_item->description = apply_filters( 'nav_menu_description', $menu_item->post_content );
    }
     return $menu_item;
}



  //Gets post cat slug and looks for single-[cat slug].php and applies it
  add_filter('single_template', 'getsinglebyslug');
  function getsinglebyslug ($the_template) {
    global $post;
    foreach( (array) get_the_category() as $cat ) {
      if ( file_exists(TEMPLATEPATH . "/single-{$cat->slug}.php") ) {
        return TEMPLATEPATH . "/single-{$cat->slug}.php";
      }
    }
    return $the_template;
  }



  // remove queries
  function _remove_script_version( $src ){
    $parts = explode( '.js?', $src );
    if (!empty($parts) && isset($parts[1]) && strpos($parts[0], 'maps') === FALSE) {
      return $parts[0].'.js';
    } else {
      return $src;
    }
  }
  add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );

  function _remove_style_version( $src ){
    $parts = explode( '.css?', $src );
    if (!empty($parts[1]) && strpos($parts[0], 'google') === FALSE) {
      return $parts[0].'.css';
    } else {
      return $src;
    }
  }

  add_filter( 'style_loader_src', '_remove_style_version', 15, 1 );


  // Breadcrumbs
function custom_breadcrumbs() {

    // Settings
    $separator          = '';
    $breadcrums_id      = 'breadcrumb';
    $breadcrums_class   = 'breadcrumb';
    $home_title         = '<i class="fa fa-home" aria-hidden="true"></i>';
    $prefix             = '';

    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';

    // Get the query & post information
    global $post,$wp_query;

    // Do not display on the homepage
    if ( !is_front_page() ) {

        // Build the breadcrums
        echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';

        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="'. __('Home', 'opsi') .'">' . $home_title . '</a></li>';


        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {

            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';

        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {

            // If post is a custom post type
            $post_type = get_post_type();

            // If it is a custom post type display name and link
            if($post_type != 'post') {

                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';

            }

            $custom_tax_name = get_queried_object()->name;
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';

        } else if ( is_single() ) {

            // If post is a custom post type
            $post_type = get_post_type();

            // If it is a custom post type display name and link
            if($post_type != 'post') {

                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';

            }

            // Get post category info
            $category = get_the_category();

            if(!empty($category)) {

                // Get last category post is in
                $category_val = array_values($category);
                $last_category = end($category_val);

                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);

                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat">'.$parents.'</li>';
                    $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                }

            }

            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {

                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;

            }

            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {

                echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

            } else {

                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

            }

        } else if ( is_category() ) {

            // Category page
            echo '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>';

        } else if ( is_page() ) {

            // Standard page
            if( $post->post_parent ){

                // If child page, get parents
                $anc = get_post_ancestors( $post->ID );

                // Get parents in the right order
                $anc = array_reverse($anc);

                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }

                // Display parent pages
                echo $parents;

                // Current page
                echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';

            } else {

                // Just display current page if not parents
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';

            }

        } else if ( is_tag() ) {

            // Tag page

            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;

            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';

        } elseif ( is_day() ) {

            // Day archive

            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

            // Month link
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';

            // Day display
            echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';

        } else if ( is_month() ) {

            // Month Archive

            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

            // Month display
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';

        } else if ( is_year() ) {

            // Display year archive
            echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';

        } else if ( is_author() ) {

            // Auhor archive

            // Get the author information
            global $author;
            $userdata = get_userdata( $author );

            // Display author name
            echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';

        } else if ( get_query_var('paged') ) {

            // Paginated archives
            echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';

        } else if ( is_search() ) {

            // Search results page
            echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';

        } elseif ( is_404() ) {

            // 404 page
            echo '<li>' . 'Error 404' . '</li>';
        }

        echo '</ul>';

    }

}





// Add the posts and pages columns filter. They can both use the same function.
add_filter('manage_posts_columns', 'nitro_add_post_admin_thumbnail_column', 2);
add_filter('manage_pages_columns', 'nitro_add_post_admin_thumbnail_column', 2);

// Add the column
function nitro_add_post_admin_thumbnail_column($nitro_columns){
	$nitro_columns['nitro_thumb'] = __('Featured Image');
	$nitro_columns['nitro_status'] = __('Status', 'opsi');
	return $nitro_columns;
}

// Let's manage Post and Page Admin Panel Columns
add_action('manage_posts_custom_column', 'nitro_show_post_thumbnail_column', 5, 2);
add_action('manage_pages_custom_column', 'nitro_show_post_thumbnail_column', 5, 2);

// Here we are grabbing featured-thumbnail size post thumbnail and displaying it
function nitro_show_post_thumbnail_column($nitro_columns, $nitro_id){
	switch($nitro_columns){
		case 'nitro_thumb':
			if( function_exists('the_post_thumbnail') ) {
				echo the_post_thumbnail( array(120,120) );
			}
			break;
		case 'nitro_status':
			echo get_post_status( $nitro_id );
			break;
	}
}


/* Change Excerpt length */
function nitro_excerpt($limit) {
    return wp_trim_words(get_the_excerpt(), $limit, '');
}


// init custom post type
function opsi_post_types() {

	/**
	* Create Projects Post Type
	*/

	$projects_args = array(

	    'supports'            => array( 'title', 'author', 'editor', 'thumbnail', 'excerpt', 'trackbacks', 'comments', 'custom-fields', 'revisions', 'post-formats'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-portfolio',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => array( 'with_front' => false, 'slug' => __( 'projects', 'opsi' ) ),
		'capability_type'     => 'post'

	);

	nitro_cpt_creator(__('Project', 'opsi'), __('Projects', 'opsi'), 'project', __( 'projects', 'opsi' ), 'opsi', 'dashicons-portfolio', 5, $projects_args);

	nitro_cpt_creator(__('Case Study', 'opsi'), __('Case Studies', 'opsi'), 'case', __( 'innovations', 'opsi' ) , 'opsi', 'dashicons-welcome-learn-more', 6);

	$countries_args = array(
		'hierarchical' => false
	);
	nitro_taxonomy_creator(__('Country', 'opsi'), __('Countries', 'opsi'), 'case', 'opsi', $countries_args);
	nitro_taxonomy_creator(__('Innovation Tag', 'opsi'), __('Innovation Tags', 'opsi'), 'case', 'opsi', $countries_args);
  nitro_taxonomy_creator(__('Innovation Tag OpenGov', 'opsi'), __('Innovation Tags OpenGov', 'opsi'), 'case', 'opsi', $countries_args);
	nitro_taxonomy_creator(__('Innovation Badge', 'opsi'), __('Innovation Badges', 'opsi'), 'case', 'opsi', $projects_args);
  nitro_taxonomy_creator(__('Innovation Badge OpenGov', 'opsi'), __('Innovation Badges OpenGov', 'opsi'), 'case', 'opsi', $projects_args);

	register_post_status( 'pending_deletion', array(
		'label'                     => _x( 'Pending Deletion', 'opsi' ),
		'public'                    => false,
		'exclude_from_search'       => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Pending Deletion <span class="count">(%s)</span>', 'Pending Deletion <span class="count">(%s)</span>' ),
	) );

	register_post_status( 'reviewed', array(
		'label'                     => _x( 'Reviewed – Not Currently Published', 'opsi' ),
		'public'                    => false,
		'exclude_from_search'       => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Reviewed <span class="count">(%s)</span>', 'Reviewed <span class="count">(%s)</span>' ),
	) );


}
add_action( 'init', 'opsi_post_types' );

// Register custom taxonomy for Case studies types
if ( ! function_exists( 'bs_case_type_taxonomy' ) ) {

// Register Custom Taxonomy
function bs_case_type_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Case types', 'Taxonomy General Name', 'opsi' ),
		'singular_name'              => _x( 'Case type', 'Taxonomy Singular Name', 'opsi' ),
		'menu_name'                  => __( 'Case type', 'opsi' ),
		'all_items'                  => __( 'All Items', 'opsi' ),
		'parent_item'                => __( 'Parent Item', 'opsi' ),
		'parent_item_colon'          => __( 'Parent Item:', 'opsi' ),
		'new_item_name'              => __( 'New Item Name', 'opsi' ),
		'add_new_item'               => __( 'Add New Item', 'opsi' ),
		'edit_item'                  => __( 'Edit Item', 'opsi' ),
		'update_item'                => __( 'Update Item', 'opsi' ),
		'view_item'                  => __( 'View Item', 'opsi' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'opsi' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'opsi' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'opsi' ),
		'popular_items'              => __( 'Popular Items', 'opsi' ),
		'search_items'               => __( 'Search Items', 'opsi' ),
		'not_found'                  => __( 'Not Found', 'opsi' ),
		'no_terms'                   => __( 'No items', 'opsi' ),
		'items_list'                 => __( 'Items list', 'opsi' ),
		'items_list_navigation'      => __( 'Items list navigation', 'opsi' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'case_type', array( 'case' ), $args );

}
add_action( 'init', 'bs_case_type_taxonomy', 0 );

}


add_action('admin_footer-post-new.php', 'opsi_append_post_status_list');
add_action('admin_footer-post.php', 'opsi_append_post_status_list');
function opsi_append_post_status_list(){
     global $post;
     $complete = '';
     $label = '';
     if($post->post_type == 'case'){

		  $complete = '';
          if($post->post_status == 'pending_deletion'){
               $complete = ' selected=\'selected\'';
               $label = '<span id="post-status-display"> '. __('Pending Deletion', 'opsi') .'</span>';
          }
		  echo '
		  <script>
		  jQuery(document).ready(function($){
			   $("select#post_status").append("<option value=\'pending_deletion\' '.$complete.'>'. __('Pending Deletion', 'opsi') .'</option>");
			   $(".misc-pub-section label").append("'.$label.'");
		  });
		  </script>
		  ';

		  $complete = '';
          if($post->post_status == 'reviewed'){
               $complete = ' selected=\'selected\'';
               $label = '<span id="post-status-display"> '. __('Reviewed', 'opsi') .'</span>';
          }
		  echo '
		  <script>
		  jQuery(document).ready(function($){
			   $("select#post_status").append("<option value=\'reviewed\' '.$complete.'>'. __('Reviewed', 'opsi') .'</option>");
			   $(".misc-pub-section label").append("'.$label.'");
		  });
		  </script>
		  ';

     }
}


function opsi_custom_status_add_in_quick_edit() {
	echo "<script>
	jQuery(document).ready( function() {
		jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"pending_deletion\">". __('Pending Deletion', 'opsi') ."</option>' );
		jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"reviewed\">". __('Reviewed', 'opsi') ."</option>' );
	});
	</script>";
}
add_action('admin_footer-edit.php','opsi_custom_status_add_in_quick_edit');

function opsi_display_archive_state( $states ) {
     global $post;
     $arg = get_query_var( 'post_status' );

     if($arg == 'pending_deletion'){
          if($post->post_status == 'pending_deletion'){
               //return array(__('Pending Deletion', 'opsi'));
               echo  ' - '.__('Pending Deletion', 'opsi');
          }
     }
     if($arg == 'reviewed'){
          if($post->post_status == 'reviewed'){
               //return array(__('Pending Deletion', 'opsi'));
               echo  ' - '.__('Reviewed', 'opsi');
          }
     }
    return $states;
}
add_filter( 'display_post_states', 'opsi_display_archive_state' );


// Register Webinars FAQ Post Type
function nitro_cpt_creator($singular_name, $plural_name, $post_type, $rewriteval, $textdomain, $icon, $position=25, $custom_args = array()) {

	$labels = array(
		'name'                => _x( $plural_name, 'Post Type General Name', $textdomain ),
		'singular_name'       => _x( $singular_name, 'Post Type Singular Name', $textdomain ),
		'menu_name'           => __( $plural_name, $textdomain ),
		'name_admin_bar'      => __( $plural_name, $textdomain ),
		'parent_item_colon'   => __( 'Parent '. $singular_name.':', $textdomain ),
		'all_items'           => __( 'All '.$plural_name, $textdomain ),
		'add_new_item'        => __( 'Add New '. $singular_name, $textdomain ),
		'add_new'             => __( 'Add '. $singular_name, $textdomain ),
		'new_item'            => __( 'New '. $singular_name, $textdomain ),
		'edit_item'           => __( 'Edit '. $singular_name, $textdomain ),
		'update_item'         => __( 'Update '. $singular_name, $textdomain ),
		'view_item'           => __( 'View '. $singular_name, $textdomain ),
		'search_items'        => __( 'Search '. $plural_name, $textdomain ),
		'not_found'           => __( 'Not found', $textdomain ),
		'not_found_in_trash'  => __( 'Not found in Trash', $textdomain ),
	);
	$args = array(
		'label'               => __( $textdomain, $textdomain ),
		'description'         => __( $plural_name.' entries', $textdomain ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields', 'comments', 'page-attributes', 'post-formats', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => $position,
		'menu_icon'           => $icon,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'rewrite'             => array( 'slug' => $rewriteval )
	);
	if (!empty($custom_args)) {
		$args = array_replace($args, $custom_args);
	}
	return (register_post_type( $post_type, $args ));


}


if ( ! function_exists( 'nitro_taxonomy_creator' ) ) {

  // Register Custom Taxonomy
  function nitro_taxonomy_creator($single, $plural, $post_type, $textdomain, $custom_args = array()) {

    $labels = array(
      'name'                       => _x( $plural, 'Taxonomy General Name', $textdomain ),
      'singular_name'              => _x( $single, 'Taxonomy Singular Name', $textdomain ),
      'menu_name'                  => __( $plural, $textdomain ),
      'all_items'                  => __( 'All '.$plural, $textdomain ),
      'parent_item'                => __( 'Parent '.$single, $textdomain ),
      'parent_item_colon'          => __( 'Parent '.$single.':', $textdomain ),
      'new_item_name'              => __( 'New '.$single, $textdomain ),
      'add_new_item'               => __( 'Add New '.$single, $textdomain ),
      'edit_item'                  => __( 'Edit '.$single, $textdomain ),
      'update_item'                => __( 'Update '.$single, $textdomain ),
      'view_item'                  => __( 'View '.$single, $textdomain ),
      'separate_items_with_commas' => __( 'Separate items with commas', $textdomain ),
      'add_or_remove_items'        => __( 'Add or remove items', $textdomain ),
      'choose_from_most_used'      => __( 'Choose from the most used', $textdomain ),
      'popular_items'              => __( 'Popular '.$plural, $textdomain ),
      'search_items'               => __( 'Search '.$plural, $textdomain ),
      'not_found'                  => __( 'Not Found', $textdomain ),
      'no_terms'                   => __( 'No items', $textdomain ),
      'items_list'                 => __( 'Items list', $textdomain ),
      'items_list_navigation'      => __( 'Items list navigation', $textdomain ),
    );
    $args = array(
      'labels'                     => $labels,
      'hierarchical'               => true,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
    );

    if (!empty($custom_args)) {
      $args = array_replace($args, $custom_args);
    }

    register_taxonomy( sanitize_title($single), array( $post_type ), $args );

  }

}


class mtekk_post_parents
{
	protected $version = '0.2.0';
	protected $full_name = 'Post Parents';
	protected $short_name = 'Post Parents';
	protected $access_level = 'manage_options';
	protected $identifier = 'mtekk_post_parents';
	protected $unique_prefix = 'mpp';
	protected $plugin_basename = 'post-parents/post_parents.php';
	/**
	 * mlba_video
	 *
	 * Class default constructor
	 */
	function __construct()
	{
		//We set the plugin basename here, could manually set it, but this is for demonstration purposes
		$this->plugin_basename = plugin_basename(__FILE__);
		add_action('add_meta_boxes', array($this, 'meta_boxes'));
	}
	/**
	 * Function that fires on the add_meta_boxes action
	 */
	function meta_boxes()
	{
		global $wp_post_types, $wp_taxonomies;
		//Loop through all of the post types in the array
		foreach($wp_post_types as $post_type)
		{
			if($post_type->name == 'project')
			{
				//Add our post parent metabox
				add_meta_box('postparentdiv', __('Parent', 'mtekk-post-parents'), array($this,'parent_meta_box'), $post_type->name, 'side', 'default');
			}
		}
	}
	/**
	 * This function outputs the post parent metabox
	 *
	 * @param WP_Post $post The post object for the post being edited
	 */
	function parent_meta_box($post)
	{
		//If we use the parent_id we can sneak in with WP's styling and post save routines
		wp_dropdown_pages(array(
      'post_type' => 'project',
			'name' => 'parent_id',
			'id' => 'parent_id',
			'echo' => 1,
			'show_option_none' => __( '&mdash; Select &mdash;' ),
			'option_none_value' => '0',
			'exclude' => $post->ID,
			'selected' => $post->post_parent)
		);
	}
}
$mtekk_post_parents = new mtekk_post_parents();


if ( ! function_exists( 'project_category' ) ) {

// Register Custom Taxonomy
function project_category() {

	$labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'opsi' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'opsi' ),
		'menu_name'                  => __( 'Categories', 'opsi' ),
		'all_items'                  => __( 'All Items', 'opsi' ),
		'parent_item'                => __( 'Parent Item', 'opsi' ),
		'parent_item_colon'          => __( 'Parent Item:', 'opsi' ),
		'new_item_name'              => __( 'New Item Name', 'opsi' ),
		'add_new_item'               => __( 'Add New Item', 'opsi' ),
		'edit_item'                  => __( 'Edit Item', 'opsi' ),
		'update_item'                => __( 'Update Item', 'opsi' ),
		'view_item'                  => __( 'View Item', 'opsi' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'opsi' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'opsi' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'opsi' ),
		'popular_items'              => __( 'Popular Items', 'opsi' ),
		'search_items'               => __( 'Search Items', 'opsi' ),
		'not_found'                  => __( 'Not Found', 'opsi' ),
		'no_terms'                   => __( 'No items', 'opsi' ),
		'items_list'                 => __( 'Items list', 'opsi' ),
		'items_list_navigation'      => __( 'Items list navigation', 'opsi' ),
	);
	$rewrite = array(
		'slug'                       => 'project-category',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'project_category', array( 'project' ), $args );

}
add_action( 'init', 'project_category', 0 );

}



if ( ! function_exists( 'project_tag' ) ) {

// Register Custom Taxonomy
function project_tag() {

	$labels = array(
		'name'                       => _x( 'Tags', 'Taxonomy General Name', 'opsi' ),
		'singular_name'              => _x( 'Tags', 'Taxonomy Singular Name', 'opsi' ),
		'menu_name'                  => __( 'Tags', 'opsi' ),
		'all_items'                  => __( 'All Items', 'opsi' ),
		'parent_item'                => __( 'Parent Item', 'opsi' ),
		'parent_item_colon'          => __( 'Parent Item:', 'opsi' ),
		'new_item_name'              => __( 'New Item Name', 'opsi' ),
		'add_new_item'               => __( 'Add New Item', 'opsi' ),
		'edit_item'                  => __( 'Edit Item', 'opsi' ),
		'update_item'                => __( 'Update Item', 'opsi' ),
		'view_item'                  => __( 'View Item', 'opsi' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'opsi' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'opsi' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'opsi' ),
		'popular_items'              => __( 'Popular Items', 'opsi' ),
		'search_items'               => __( 'Search Items', 'opsi' ),
		'not_found'                  => __( 'Not Found', 'opsi' ),
		'no_terms'                   => __( 'No items', 'opsi' ),
		'items_list'                 => __( 'Items list', 'opsi' ),
		'items_list_navigation'      => __( 'Items list navigation', 'opsi' ),
	);
	$rewrite = array(
		'slug'                       => 'project-tag',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'project_tag', array( 'project' ), $args );

}
add_action( 'init', 'project_tag', 0 );

}



add_action('print_media_templates', 'opsi_print_media_templates');

function opsi_print_media_templates() {
?>
<script type="text/html" id="tmpl-custom-gallery-setting">
    <label class="setting">
      <span><?php _e('Caption'); ?></span>
      <select data-setting="caption">
        <option value="no">No</option>
        <option value="yes">Yes</option>
      </select>
    </label>
</script>

<script>

    jQuery(document).ready(function()
    {
        _.extend(wp.media.galleryDefaults, {
        caption: 'no',
        });

        wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
        template: function(view){
          return wp.media.template('gallery-settings')(view)
               + wp.media.template('custom-gallery-setting')(view);
        },
        // this is function copies from WP core /wp-includes/js/media-views.js?ver=4.6.1
        update: function( key ) {
          var value = this.model.get( key ),
            $setting = this.$('[data-setting="' + key + '"]'),
            $buttons, $value;

          // Bail if we didn't find a matching setting.
          if ( ! $setting.length ) {
            return;
          }

          // Attempt to determine how the setting is rendered and update
          // the selected value.

          // Handle dropdowns.
          if ( $setting.is('select') ) {
            $value = $setting.find('[value="' + value + '"]');

            if ( $value.length ) {
              $setting.find('option').prop( 'selected', false );
              $value.prop( 'selected', true );
            } else {
              // If we can't find the desired value, record what *is* selected.
              this.model.set( key, $setting.find(':selected').val() );
            }

          // Handle button groups.
          } else if ( $setting.hasClass('button-group') ) {
            $buttons = $setting.find('button').removeClass('active');
            $buttons.filter( '[value="' + value + '"]' ).addClass('active');

          // Handle text inputs and textareas.
          } else if ( $setting.is('input[type="text"], textarea') ) {
            if ( ! $setting.is(':focus') ) {
              $setting.val( value );
            }
          // Handle checkboxes.
          } else if ( $setting.is('input[type="checkbox"]') ) {
            $setting.prop( 'checked', !! value && 'false' !== value );
          }
          // HERE the only modification I made
          else {
            $setting.val( value ); // treat any other input type same as text inputs
          }
          // end of that modification
        },
        });
    });

</script>
<?php
}

function nitro_gallery_shortcode_filter( $output = '', $atts, $instance ) {
	$return = $output; // fallback

	// retrieve content of your own gallery function
	$my_result = nitro_gallery_shortcode( $atts );

	// boolean false = empty, see http://php.net/empty
	if( !empty( $my_result ) ) {
		$return = $my_result;
	}

	return $return;
}

add_filter( 'post_gallery', 'nitro_gallery_shortcode_filter', 10, 3 );

function nitro_casestudystatus( $output = '', $atts, $instance ) {


	$atts = shortcode_atts( array(
		'id'         => ( ( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) > 0 ) ? intval( $_GET['edit'] ) : 0 ),
	), $atts, 'casestudystatus' );


	if ( $atts['id'] == 0 ) {
		return '<h3 class="status_header">'. __( 'Status', 'opsi' ) .'</h3>'.__( 'Not yet saved', 'opsi' );
	}
	if ( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) > 0 && !can_edit_acf_form( intval( $_GET['edit'] ) ) ) {
		return;
	}

	$post_status = get_post_status_object ( get_post_status( $atts['id'] ) );

	$status = $post_status->label;

	if ( !$post_status->name ) {
		return '<h3 class="status_header">'. __( 'Status', 'opsi' ) .'</h3>'.__( 'Not yet submitted', 'opsi' );
	}

	$last_save = get_the_modified_date( get_option('date_format') .', '. get_option('time_format'). ' a', $atts['id'] );

	if ( $post_status ) {
		return '<h3 class="status_header">'. __( 'Status', 'opsi' ) .'</h3><p>'.$status .'<br />'. __( 'Last saved:', 'opsi' ) .' '. $last_save .'</p>';
	}

	return;
}
add_shortcode( 'casestudystatus', 'nitro_casestudystatus', 100, 3 );


function nitro_gallery_shortcode( $attr ) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	/**
	 * Filters the default gallery shortcode output.
	 *
	 * If the filtered output isn't empty, it will be used instead of generating
	 * the default gallery template.
	 *
	 * @since 2.5.0
	 * @since 4.2.0 The `$instance` parameter was added.
	 *
	 * @see gallery_shortcode()
	 *
	 * @param string $output   The gallery output. Default empty.
	 * @param array  $attr     Attributes of the gallery shortcode.
	 * @param int    $instance Unique numeric ID of this gallery shortcode instance.
	 */
	$output = '';
  $galid = uniqid();

	$html5 = current_theme_supports( 'html5', 'gallery' );
	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => $html5 ? 'figure'     : 'dl',
		'icontag'    => $html5 ? 'div'        : 'dt',
		'captiontag' => $html5 ? 'figcaption' : 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => '',
		'caption'    => 'no'
	), $attr, 'gallery' );

	$id = intval( $atts['id'] );

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}
		return $output;
	}

	$itemtag = tag_escape( $atts['itemtag'] );
	$captiontag = tag_escape( $atts['captiontag'] );
	$caption    = tag_escape( $atts['caption'] );
	$icontag = tag_escape( $atts['icontag'] );
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) ) {
		$itemtag = 'dl';
	}
	if ( ! isset( $valid_tags[ $captiontag ] ) ) {
		$captiontag = 'dd';
	}
	if ( ! isset( $valid_tags[ $icontag ] ) ) {
		$icontag = 'dt';
	}

	$columns = intval( $atts['columns'] );
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = '';

	/**
	 * Filters whether to print default gallery styles.
	 *
	 * @since 3.1.0
	 *
	 * @param bool $print Whether to print default gallery styles.
	 *                    Defaults to false if the theme supports HTML5 galleries.
	 *                    Otherwise, defaults to true.
	 */
	if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>\n\t\t";
	}

	$size_class = sanitize_html_class( $atts['size'] );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

	/**
	 * Filters the default gallery shortcode CSS styles.
	 *
	 * @since 2.5.0
	 *
	 * @param string $gallery_style Default CSS styles and opening HTML div container
	 *                              for the gallery shortcode output.
	 */
	$output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {

		$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
		if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
			$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
		} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
			$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
		} else {
			$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
		}



		$image_meta  = wp_get_attachment_metadata( $id );


    $image_output = str_replace("<a", "<a data-fancybox='". $galid ."' data-caption='". wptexturize($attachment->post_excerpt) ."' ", $image_output);

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
		}
		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";


		if ( $captiontag && trim($attachment->post_excerpt) && $caption != 'no' ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
			$output .= '<br style="clear: both" />';
		}
	}

	if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
		$output .= "
			<br style='clear: both' />";
	}

	$output .= "
		</div>\n";

	return $output;
}


/****************************/
/*        BUDDYPRESS        */

function redirect2profile(){

	global $bp;

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if($_SERVER['REQUEST_URI'] == '/profile/' && is_plugin_active('buddypress/bp-loader.php') && is_user_logged_in()){
		global $current_user;
		wp_safe_redirect( $bp->loggedin_user->domain . '/profile/');
		exit();
	}
  // hide theme my login profile edit page
  if($_SERVER['REQUEST_URI'] == '/your-profile/' && is_plugin_active('buddypress/bp-loader.php') && is_user_logged_in()){
		global $current_user;
		wp_safe_redirect( $bp->loggedin_user->domain . '/profile/edit/');
		exit();
	}
  // redirect to login page if user is not logged in and tries to access the tml profile edit page
  if($_SERVER['REQUEST_URI'] == '/your-profile/' && is_plugin_active('buddypress/bp-loader.php') && !is_user_logged_in()){
		global $current_user;
		wp_safe_redirect( get_permalink(get_page_by_path('register')));
		exit();
	}


	// redirect to profile
	if($_SERVER['REQUEST_URI'] == '/my-profile/' && is_plugin_active('buddypress/bp-loader.php') && is_user_logged_in()){
		global $current_user;
		wp_safe_redirect( $bp->loggedin_user->domain );
		exit();
	}
	if($_SERVER['REQUEST_URI'] == '/my-profile/' && is_plugin_active('buddypress/bp-loader.php') && !is_user_logged_in()){
		global $current_user;
		wp_safe_redirect( get_permalink(get_page_by_path('register')));
		exit();
	}


	// redirect to innovations
	if($_SERVER['REQUEST_URI'] == '/my-innovations/' && is_plugin_active('buddypress/bp-loader.php') && is_user_logged_in()){
		global $current_user;
		wp_safe_redirect( $bp->loggedin_user->domain . '/innovations/');
		exit();
	}
	// redirect to login page if user is not logged in and tries to access the tml profile edit page
	if($_SERVER['REQUEST_URI'] == '/my-innovations/' && is_plugin_active('buddypress/bp-loader.php') && !is_user_logged_in()){
		global $current_user;
		wp_safe_redirect( get_permalink(get_page_by_path('register')));
		exit();
	}


 }
add_action('init', 'redirect2profile');




function opsi_tml_action_url( $url, $action, $instance ) {
	if ( 'register' == $action )
		$url = get_permalink(get_page_by_path('register'));
	return $url;
}
add_filter( 'tml_action_url', 'opsi_tml_action_url', 10, 3 );


function can_edit_acf_form( $post_id = 0, $user_id = 0, $allowed_statuses = array( 'draft', 'pending', 'archive', 'reviewed' ) ) {

	if ( intval( $user_id ) == 0 && get_current_user_id() > 0 ) {
		$user_id = get_current_user_id();
	}

	if ( intval( $post_id ) == 0 ) {
		global $post;

		if ( !empty( $post ) ) {
			$post_id = $post->ID;
		}
	}


	if ( intval( $post_id ) > 0 && intval( $user_id ) > 0 ) {

		$post_author = get_post_field( 'post_author', $post_id );
		$post_status = get_post_field( 'post_status', $post_id );

		if ( intval( $post_author ) == $user_id && ( in_array( $post_status, $allowed_statuses ) || $allowed_statuses[0] == 'any' ) ) {
			return true;
		}

	}




	return false;
}

function can_delete_cs( $post_id = 0, $user_id = 0 ) {

	if ( intval( $user_id ) == 0 && get_current_user_id() > 0 ) {
		$user_id = get_current_user_id();
	}

	if ( intval( $post_id ) == 0 ) {
		global $post;

		if ( !empty( $post ) ) {
			$post_id = $post->ID;
		}
	}


	if ( intval( $post_id ) > 0 && intval( $user_id ) > 0 ) {

		$post_author = get_post_field( 'post_author', $post_id );
		$post_status = get_post_field( 'post_status', $post_id );

		if ( intval( $post_author ) == $user_id ) {
			if ( $post_status == 'draft' ) {
				return 'delete';
			}
			if ( $post_status == 'pending' || $post_status == 'publish' || $post_status == 'reviewed' ) {
				return 'request';
			}
		}

	}

	return false;
}



/************** ADD Innovations / Case Study SubTab  START ************/

// add_action( 'bp_setup_nav', 'add_activity_subnav_tab', 100 );
function add_activity_subnav_tab() {

		global $bp;

		$user_domain = bp_displayed_user_domain() ? bp_displayed_user_domain() : bp_loggedin_user_domain();
		$activity_link = trailingslashit( $user_domain . $bp->activity->slug );

		$user_id = bp_displayed_user_id();
		$current_user_id = bp_loggedin_user_id();

		$count_args_owner = array(
			'post_type' => array ( 'case' ),
			'post_status' => array( 'any', 'archive', 'pending_deletion', 'reviewed' )
		);
		$count_args_guest = array(
			'post_type' => array ( 'case' ),
			'post_status' => array( 'publish' )
		);

		$all_posts = nitro_get_user_posts_count( $user_id, $count_args_owner );
		$published_posts = nitro_get_user_posts_count( $user_id, $count_args_guest );

		$count_inno = 0;

		if ( $user_id == $current_user_id ) {
			$count_inno = $all_posts;
		} else {
			$count_inno = $published_posts;
		}

        // add 'Innovations' sub-menu tab
        bp_core_new_subnav_item( array(
                'name' => __( 'Innovations', 'opsi' ) . ' ( '. $count_inno .' )',
                'slug' => 'innovations',
                'parent_url' => $activity_link,
                'parent_slug' => $bp->activity->slug,
                'screen_function' => 'nitro_my_innovations_screen',
                'position' => 120
                )
        );
}


function nitro_get_user_posts_count($user_id,$args ) {
    $args['author'] 		= $user_id;
    $args['fields'] 		= 'ids';
    $args['posts_per_page'] = -1;
    $ps = get_posts($args);
    return count($ps);
}


function opsi_get_terms_list ( $post_id, $taxonomy, $args = array() ) {

	$terms = wp_get_post_terms( $post_id, $taxonomy, $args );

	$out = '';

	if ( !empty ( $terms ) ) {

		$out .= '<div class="post_tags">';

		foreach ( $terms as $t ) {

            $out .= '<a href="'. get_term_link( $t->term_id, $taxonomy ) .'" title="'. $t->name .'" rel="tag">'. $t->name .'</a>';
		}

		$out .= '</div>';
	}

}


add_filter( 'facetwp_result_count', 'tweak_fwp_count', 10, 2 );
function tweak_fwp_count( $output, $params ) {

    return $params['total'];
}


// adds map geochart on case study archive top

function cs_geochart_map() {
	if ( !is_post_type_archive( 'case' ) ) { return; }
	?>
		<script type="text/javascript">
      google.charts.load('current', {
        'packages':['geochart'],
        // Note: you will need to get a mapsApiKey for your project.
        // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
        'mapsApiKey': '<?php echo get_field( 'google_maps_api_key', 'option' ); ?>'
      });
      google.charts.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable([
          ['Country', 'Innovations'],
		  <?php
			$wp_country_terms = get_terms( 'country' , array( 'hide_empty' => true, 'fields' => 'all' ) );

			if ( !empty( $wp_country_terms ) ) {
				foreach ( $wp_country_terms as $country_term ) {
					echo "['". $country_term->name ."', ". $country_term->count ."],\n\t";
				}
			}
		  ?>
        ]);

        var options = {
			colorAxis: {colors: ['#12d5e8', '#8133e7']},
			backgroundColor: '#D7FAFE',
			magnifyingGlass: {enable: true, zoomFactor: 7.5}
		};

        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

        chart.draw(data, options);
      }
    </script>
	<?php
}
// add_action( 'wp_footer', 'cs_geochart_map' );


// JS CONFLICT // DO NOT UNCOMMENT
// add_action( 'wp_head', 'mailchimp_pop_embed_js' );
function mailchimp_pop_embed_js() {
	?>

	<script type="text/javascript" src="//downloads.mailchimp.com/js/signup-forms/popup/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script><script type="text/javascript">require(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"mc.us13.list-manage.com","uuid":"57778163aacf3b56841696fe1","lid":"8445d592ef"}) })</script>

	<?php
}





function wp_count_uncached_posts( $type = 'post', $perm = '' ) {
	global $wpdb;
	if ( ! post_type_exists( $type ) ) {
		return new stdClass;
	}
	$cache_key = _count_posts_cache_key( $type, $perm );

	$query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = %s";

	$query .= ' GROUP BY post_status';

	$results = (array) $wpdb->get_results( $wpdb->prepare( $query, $type ), ARRAY_A );

	$counts = array();

	if ( !empty( $results ) ) {
		foreach( $results as $res ) {
			$counts[$res['post_status']] = $res['num_posts'];
		}

	} else {
		return false;
	}
	return $counts;

}


function opsi_admin_notice() {

  // Get Opsi pending Cases
  $args_opsi = array(
		'fields'		=> 'ids',
		'post_type'		=> 'case',
		'post_status'	=> 'pending',
    'tax_query' => array(
      array (
        'taxonomy' => 'case_type',
        'field' => 'slug',
        'terms' => 'opsi',
      ),
    ),
		'posts_per_page' => -1,
	);

	$query_opsi = new WP_Query( $args_opsi );
	if ( $query_opsi->post_count > 0 ) {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><a href="<?php admin_url(); ?>edit.php?post_status=pending&post_type=case&case_type=opsi"><strong><?php echo sprintf( __( 'There are %d pending OPSI Case Studies', 'opsi' ), $query_opsi->post_count ); ?></strong></a></p>
    </div>
    <?php
	}

  // Get OpenGov pending Cases
  $args_opengov = array(
		'fields'		=> 'ids',
		'post_type'		=> 'case',
		'post_status'	=> 'pending',
    'tax_query' => array(
      array (
        'taxonomy' => 'case_type',
        'field' => 'slug',
        'terms' => 'open-government',
      )
    ),
		'posts_per_page' => -1,
	);

	$query_opengov = new WP_Query( $args_opengov );
	if ( $query_opengov->post_count > 0 ) {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><a href="<?php admin_url(); ?>edit.php?post_status=pending&post_type=case&case_type=open-government"><strong><?php echo sprintf( __( 'There are %d pending Open Government Case Studies', 'opsi' ), $query_opengov->post_count ); ?></strong></a></p>
    </div>
    <?php
	}


	// Get the total number of users for the current query. I use (int) only for sanitize.
	$users_count = count( get_users( array( 'fields' => array( 'ID' ), 'role' => 'pending' ) ) );
	// Echo a string and the value
	if ( $users_count > 0 ) {
	?>
	<div class="notice notice-warning is-dismissible">
        <p><a href="<?php admin_url(); ?>users.php?role=pending"><strong><?php echo sprintf( __( 'There are %d Pending Users', 'opsi' ), $users_count ); ?></strong></a></p>
    </div>
	<?php
	}

}
add_action( 'admin_notices', 'opsi_admin_notice', 100 );

// force login for case study form
add_action( 'template_redirect', 'cs_form_template_redirect' );
function cs_form_template_redirect() {
	if( is_page( 'case-study-form' ) && !is_user_logged_in() ) {
		wp_redirect( wp_login_url( get_permalink( get_page_by_path( 'case-study-form' ) ) ) );
		die;
	}
}

// force login single member page
add_action( 'template_redirect', 'opsi_restrict_access_to_single_member_page' );
function opsi_restrict_access_to_single_member_page() {
	if( bp_is_user() && !is_user_logged_in() ) {
		global $bp;

		$current_action = $bp->current_action;
		if ( $current_action == 'just-me' ) {
			$current_action = '';
		}

		wp_redirect( wp_login_url( trailingslashit( trailingslashit( $bp->displayed_user->domain.$bp->current_component ).$current_action ) ) );
		die;
	}
}



// An action to perform when a case study is published
add_action( 'pending_to_publish', 'cs_on_publish_pending_post', 10, 1 );
function cs_on_publish_pending_post( $post ) {

	if ( 'case' === get_post_type() ) { // check the custom post type

		$cstitle   	= get_the_title( $post->ID );
		$cslink   	= get_the_permalink( $post->ID );

		$author_mail 		= get_the_author_meta( 'user_email', $post->post_author );
		$author_fname 		= get_the_author_meta( 'first_name', $post->post_author );
		$author_lname 		= get_the_author_meta( 'last_name', $post->post_author );
		$author_fullname 	= $author_fname.' '. $author_lname;

    // get subject and content based on case type
    $case_type = get_the_terms( $post->ID, 'case_type' );
    $case_type_slug = $case_type[0]->slug;
    if ( $case_type_slug == 'opsi' ) {
      //OPSI-OECD case type
      $subject = get_field( 'author_published_notification_subject', 'option' );
      $mail_content = get_field( 'author_published_notification_mail', 'option' );
    } else {
      //Open gov case type
      $subject = get_field( 'author_published_notification_subject_open_gov', 'option' );
      $mail_content = get_field( 'author_published_notification_mail_open_gov', 'option' );
    }

		$subject = get_field( 'author_published_notification_subject', 'option' );
		$body    = str_replace( array( '%authorname%', '%casestudylink%', '%casestudytitle%' ), array( $author_fullname, $cslink, $cstitle ), $mail_content );

		$headers = array('Content-Type: text/html; charset=UTF-8');

		wp_mail( $author_mail, $subject, $body, $headers );


		// create notification for buddypress

		bp_notifications_add_notification( array(
			'user_id'           => $post->post_author,
			'item_id'           => $post->ID,
			'component_name'    => 'innovations',
			'component_action'  => 'innovations_notification_action',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );



	}
}

// this gets the saved item id, compiles some data and then displays the notification
add_filter( 'bp_notifications_get_notifications_for_user', 'nitro_format_buddypress_notifications', 10, 7 );
function nitro_format_buddypress_notifications( $content, $item_id, $secondary_item_id, $total_items, $format = 'string', $action, $component ) {
	// New custom notifications
	if ( 'innovations_notification_action' === $action ) {

		$custom_title = get_the_title( $item_id );
		$custom_link  = get_the_permalink( $item_id );
		$custom_text = 'Congratulations! Your case study "'. $custom_title .'" has been published';
		// WordPress Toolbar
		if ( 'string' === $format ) {
			$return = apply_filters( 'innovations_notification_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_title ) . '">' . esc_html( $custom_text ) . '</a>', $custom_text, $custom_link );
		// Deprecated BuddyBar
		} else {
			$return = apply_filters( 'innovations_notification_filter', array(
				'text' => $custom_text,
				'link' => $custom_link
			), $custom_link, (int) $total_items, $custom_text, $custom_title );
		}

		return $return;

	}

}


add_filter( 'bp_notifications_get_registered_components', 'bp_filter_notifications_get_registered_components' );
function bp_filter_notifications_get_registered_components( $component_names = array() ) {

	if ( ! is_array( $component_names ) ) {
		$component_names = array();
	}

	array_push( $component_names, 'innovations' );

	return $component_names;
}




// create activity stream for the published Case Study
add_action( 'init', 'nitro_customize_page_tracking_args', 1000 );
function nitro_customize_page_tracking_args() {
    if ( ! bp_is_active( 'activity' ) ) {
        return;
    }
     add_post_type_support( 'case', 'buddypress-activity' );


    bp_activity_set_post_type_tracking_args( 'case', array(
        'action_id'                         => 'new_case',
        'bp_activity_admin_filter'          => __( 'Published a new case study', 'opsi' ),
        'bp_activity_front_filter'          => __( 'Case Study', 'opsi' ),
        'bp_activity_new_post'              => __( '%1$s wrote a new case study, <a href="%2$s">[Case Study]</a>', 'opsi' ),
        'bp_activity_new_post_ms'           => __( '%1$s wrote a new case study, <a href="%2$s">[Case Study]</a>, on the site %3$s', 'opsi' ),
        'contexts'                          => array( 'activity', 'member' ),
        'comment_action_id'                 => 'new_case_comment',
        'bp_activity_comments_admin_filter' => __( 'Commented on a case study', 'opsi' ),
        'bp_activity_comments_front_filter' => __( 'Case Study Comments', 'opsi' ),
        'bp_activity_new_comment'           => __( '%1$s commented on a <a href="%2$s">Case Study</a>', 'opsi' ),
        'bp_activity_new_comment_ms'        => __( '%1$s commented on a <a href="%2$s">Case Study</a>, on the site %3$s', 'opsi' ),
        'position'                          => 100,
    ) );
}



add_filter( 'bp_activity_custom_post_type_post_action', 'new_case_study_include_post_type_title', 10, 2 );
function new_case_study_include_post_type_title( $action, $activity ) {
	if ( empty( $activity->id ) ) {
		return $action;
	}

	if ( 'new_case' != $activity->type ) {
		return $action;
	}

	preg_match_all( '/<a.*?>([^>]*)<\/a>/', $action, $matches );

	if ( empty( $matches[1][1] ) || '[Case Study]' != $matches[1][1] ) {
		return $action;
	}

	$post_type_title = bp_activity_get_meta( $activity->id, 'post_title' );

	if ( empty( $post_type_title ) ) {

		switch_to_blog( $activity->item_id );

		$post_type_title = get_post_field( 'post_title', $activity->secondary_item_id );

		// We have a title save it in activity meta to avoid switching blogs too much
		if ( ! empty( $post_type_title ) ) {
			bp_activity_update_meta( $activity->id, 'post_title', $post_type_title );
		}

		restore_current_blog();
	}

	return str_replace( $matches[1][1], esc_html( $post_type_title ), $action );
}




add_filter( 'facetwp_sort_options', 'opsi_facetwp_sort_options', 10, 2 );
function opsi_facetwp_sort_options( $options, $params ) {
    $options['date_desc']['label'] = __( 'Date Submitted (Newest)', 'opsi' );
    $options['date_asc']['label'] = __( 'Date Submitted (Oldest)', 'opsi' );
    return $options;
}



add_action('pre_get_posts', 'opsi_add_archived_case_studies');
 function opsi_add_archived_case_studies($query) {

	if ( $query->is_main_query() ) {

		if ( $query->is_archive && ( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] == 'case' ) && isset( $_GET['archive'] ) && intval( $_GET['archive'] ) == 1 ) {
			$query->set( 'post_status', array('publish', 'archive') );
		}
	}
}

// list 10 innovations per page
add_action( 'pre_get_posts', 'opsi_case_study_archive_query' );
function opsi_case_study_archive_query( $query ){


    if( ! is_admin()
        && ( $query->is_post_type_archive( 'case' ) || $query->is_tax( 'innovation-tag' ) || $query->is_tax( 'innovation-badge' ) || $query->is_tax( 'country' )  )
        && $query->is_main_query() ){
            $query->set( 'posts_per_page', 10 );
    }
}


add_filter( 'facetwp_render_output', 'opsi_html_select_labels', 10, 2 );
function opsi_html_select_labels( $output, $params ) {
	if ( isset( $output['facets']['stage_of_innovation'] ) ) {
		$output['facets']['stage_of_innovation'] = html_entity_decode( $output['facets']['stage_of_innovation'] );
	}
    return $output;
}

// add all users in the case studies autor drop down in wp-admin


function all_users_for_cs( $query_args, $r ) {
	global $post;

	$screen = get_current_screen();

	if( $post->post_type == "case" && $screen->parent_base == 'edit' ) {
		unset( $query_args['role'] );
		unset( $query_args['who'] );
	}

	return $query_args;

}
add_filter( 'wp_dropdown_users_args', 'all_users_for_cs', 10, 2 );


function acf_textarea_admin_render( $field ) {

	if ( is_admin() ) {
		$screen = get_current_screen();

		if ( get_post_type() == 'case' && $screen->parent_base == 'edit' ) {
			$field['type'] 			= 'wysiwyg';
			$field['tabs'] 			= 'all';
			$field['toolbar'] 		= 'full';
			$field['media_upload'] 	= 1;
			$field['delay'] 		= 0;
		}
	}

	return $field;

}
add_filter( 'acf/load_field/type=textarea', 'acf_textarea_admin_render' );

function acf_special_chars_convert( $value, $post_id, $field ) {

	return html_entity_decode( $value );
}
add_filter('acf/load_value/type=textarea', 'acf_special_chars_convert', 10, 3);


function opsi_textarea_format_value( $value, $post_id, $field ) {


	return wpautop( $value );
}
add_filter('acf/format_value/type=textarea', 'opsi_textarea_format_value', 20, 3);



// Get single case template for Open Government type cases
function bs_single_case_open_gov_template($single_template) {
  global $post;

  // Check if it is a case post type
  if ($post->post_type == 'case') {

    $primary_type = get_field('primary_case_type', $post->ID);

    if( 'open-government' == $primary_type ) {
      $single_template = TEMPLATEPATH . '/single-case-open-gov.php';
    } elseif ( 'opsi' == $primary_type ) {
      $single_template = TEMPLATEPATH . '/single-case.php';
    }

  }

  return $single_template;
}
add_filter('single_template', 'bs_single_case_open_gov_template');

// Add custom capabilities to Case CPT
function bs_case_custom_capabilities( $args, $post_type ) {

    // Only target our specific post type
    if ( 'case' !== $post_type )
        return $args;

    // Change capabilities
    $args['capability_type'] = 'case';
    $args['map_meta_cap'] = true;

    return $args;
};
add_filter( 'register_post_type_args', 'bs_case_custom_capabilities', 10, 2 );


// Add a taxonomy filter for Case type
function bs_filter_case_by_case_type( $post_type, $which ) {

	// Apply this only on Case post type
	if ( 'case' !== $post_type )
		return;

	// A list of taxonomy slugs to filter by
	$taxonomies = array( 'manufacturer', 'model', 'transmission', 'doors', 'color' );

	// Retrieve taxonomy data
	$taxonomy_obj = get_taxonomy( 'case_type' );
	$taxonomy_name = $taxonomy_obj->labels->name;

	// Retrieve taxonomy terms
	$terms = get_terms( 'case_type' );

	// Display filter HTML
	echo "<select name='case_type' id='case_type' class='postform'>";
	echo '<option value="">'.__('Show All Case Types', 'opsi').'</option>';
	foreach ( $terms as $term ) {
		printf(
			'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
			$term->slug,
			( ( isset( $_GET['case_type'] ) && ( $_GET['case_type'] == $term->slug ) ) ? ' selected="selected"' : '' ),
			$term->name,
			$term->count
		);
	}
	echo '</select>';

}
add_action( 'restrict_manage_posts', 'bs_filter_case_by_case_type' , 10, 2);


// Redirect case archive page to OPSI Case type archive page
function bs_redirect_case_archive() {
  if( is_post_type_archive( 'case' ) ) {
    wp_redirect( get_term_link( 'opsi', 'case_type' ), 301 );
    exit;
  }
}
add_action( 'template_redirect', 'bs_redirect_case_archive' );


add_shortcode( 'opsi-clustered-provisions', 'opsi_clustered_provisions' );
function opsi_clustered_provisions( $atts ) {
	$a = shortcode_atts( array(
		'col_w' => '4',
	), $atts );
	$output = '';

	$terms = get_terms(
		array(
			'taxonomy' => 'clustered-provisions',
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'     => 'archive_page',
					'value'   => '',
					'compare' => '!='
				)
			)
		)
	);

	if ( !empty( $terms ) ) {
		$output .= '<div class="clustered-provisions row wpb_column vc_column_container vc_col-sm-12">';
		foreach ( $terms as $term ) {
			$archive_page = get_term_meta( $term->term_id, 'archive_page', true );
			$archive_page = is_array( $archive_page ) ? current( $archive_page ) : $archive_page;
			$archive_page_url = get_permalink( $archive_page );
			$output .= sprintf( '<div class="col-md-%s col-sm-%s clustered-provision"><a href="%s">%s</a></div>', $a['col_w'], $a['col_w'], $archive_page_url, $term->name );
		}
		$output .= '</div>';
	}

	return $output;
}
add_shortcode( 'opsi-how-do-i', 'opsi_how_do_i' );
function opsi_how_do_i( $atts ) {
	$a = shortcode_atts( array(
		'suffix' => '?'
	), $atts );
	$output = '';

	$terms = get_terms(
		array(
			'taxonomy' => 'how-do-i',
			'hide_empty' => false,
			'orderby' => 'ID',
			'meta_query' => array(
				array(
					'key'     => 'archive_page',
					'value'   => '',
					'compare' => '!='
				)
			)
		)
	);

	if ( !empty( $terms ) ) {
		$output .= '<ul class="how-do-i-tags link-list">';
		foreach ( $terms as $term ) {
			$archive_page = get_term_meta( $term->term_id, 'archive_page', true );
			$archive_page = is_array( $archive_page ) ? current( $archive_page ) : $archive_page;
			$archive_page_url = get_permalink( $archive_page );
			$output .= sprintf( '<li class="how-do-i-tag"><a href="%s">%s%s</a></li>', $archive_page_url, $term->name, $a['suffix'] );
		}
		$output .= '</ul>';
	}

	return $output;
}

add_shortcode( 'opsi-clustered-provision-archive', 'opsi_clustered_provision_archive' );
function opsi_clustered_provision_archive( $atts ) {
	$a = shortcode_atts( array(
		'tag' => '',
		'title' => 'Toolkits',
		'col_w' => '6'
	), $atts );
	$output = '<div class="taxonomy-archive clustered-provision">';

	if ( !empty( $a['tag'] ) ) {
		if ( term_exists( $a['tag'], 'clustered-provisions' ) ) {

			$args = array(
				'post_type' => 'toolkit',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'tax_query' => array(
					array(
						'taxonomy' => 'clustered-provisions',
						'field' => 'slug',
						'terms' => $a['tag']
					),
					array(
						'taxonomy' => 'discipline-or-practice',
						'field' => 'slug',
						'terms' => 'open-government'
					)
				)
			);

			$posts = get_posts( $args );

			if ( !empty( $posts ) ) {

				if ( !empty( $a['title'] ) ) {
					$output .= sprintf( '<h2><strong>%s</strong></h2>', $a['title'] );
				}

				$i = 0;
				foreach ( $posts as $post ) {
					$mod = $i % 2;
					if ( !$mod ) {
						$output .= '<div class="row wpb_column vc_column_container vc_col-sm-12">';
					}

					$url = get_permalink( $post->ID );
					$publisher = wp_get_post_terms( $post->ID, 'toolkit-publisher' );
					$img = sprintf(
						'<a href="%s">%s</a>',
						$url,
						get_the_post_thumbnail( $post->ID, 'thumbnail' )
					);
					$content = sprintf(
						'<div class="title"><a href="%s">%s</a></div><div class="publisher">%s</div><div class="description">%s</div>',
						$url,
						$post->post_title,
						is_array( $publisher ) ? $publisher[0]->name : '',
						get_post_meta( $post->ID, 'description', true )
					);
					$output .= sprintf(
						'<div class="col-md-%s col-sm-%s toolkit"><div class="col-md-4 col-sm-4 img">%s</div><div class="col-md-8 col-sm-8 body">%s</div></div>',
						$a['col_w'],
						$a['col_w'],
						$img,
						$content
					);

					if ( $mod ){
						$output .= '</div>';
					}
					$i++;
				}
			}
		}
	}

	$output .= '</div>';

	return $output;
}

add_shortcode( 'opsi-how-do-i-archive', 'opsi_how_do_i_archive' );
function opsi_how_do_i_archive( $atts ) {
	$a = shortcode_atts( array(
		'tag' => '',
		'title' => 'Toolkits',
		'col_w' => '6'
	), $atts );

	$output = '<div class="taxonomy-archive how-do-i">';

	if ( !empty( $a['tag'] ) ) {
		if ( term_exists( $a['tag'], 'how-do-i' ) ) {

			$args = array(
				'post_type' => 'toolkit',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'tax_query' => array(
					array(
						'taxonomy' => 'how-do-i',
						'field' => 'slug',
						'terms' => $a['tag']
					),
					array(
						'taxonomy' => 'discipline-or-practice',
						'field' => 'slug',
						'terms' => 'open-government'
					)
				)
			);

			$posts = get_posts( $args );

			if ( !empty( $posts ) ) {

				if ( !empty( $a['title'] ) ) {
					$output .= sprintf( '<h2><strong>%s</strong></h2>', $a['title'] );
				}

				$i = 0;
				foreach ( $posts as $post ) {
					$mod = $i % 2;
					if ( !$mod ) {
						$output .= '<div class="row wpb_column vc_column_container vc_col-sm-12">';
					}

					$url = get_permalink( $post->ID );
					$publisher = wp_get_post_terms( $post->ID, 'toolkit-publisher' );
					$img = sprintf(
						'<a href="%s">%s</a>',
						$url,
						get_the_post_thumbnail( $post->ID, 'thumbnail' )
					);
					$content = sprintf(
						'<div class="title"><a href="%s">%s</a></div><div class="publisher">%s</div><div class="description">%s</div>',
						$url,
						$post->post_title,
						is_array( $publisher ) ? $publisher[0]->name : '',
						get_post_meta( $post->ID, 'description', true )
					);
					$output .= sprintf(
						'<div class="col-md-%s col-sm-%s toolkit"><div class="col-md-4 col-sm-4 img">%s</div><div class="col-md-8 col-sm-8 body">%s</div></div>',
						$a['col_w'],
						$a['col_w'],
						$img,
						$content
					);

					if ( $mod ){
						$output .= '</div>';
					}
					$i++;
				}
			}
		}
	}

	$output .= '</div>';

	return $output;
}

add_action( 'opsi-before-guide-title', 'bs_add_subguide_banner' );
function bs_add_subguide_banner( $is_subpage ) {
	if ( $is_subpage ) {
		$image = get_the_post_thumbnail( get_the_ID(), 'full', array( 'alt' => 'OECD Opengovernment' ) );
		if ( $image ) {
			printf( '<div class="before-guide-title">%s</div>', $image );
		}
	}
}

// Hide Analitify menu item for OpenGov Admin
function bs_remove_menu_pages() {
  $user = wp_get_current_user();
  $roles = ( array ) $user->roles;
  if ( in_array( 'open-gov-admin', $roles ) ) {
    remove_menu_page( 'analytify-dashboard' );
  }
}
add_action( 'admin_init', 'bs_remove_menu_pages' );

// Add custom capabilities to Case studies taxonomies
function bs_add_custom_capabilities_to_case_taxonomies( $args, $taxonomy, $object_type ) {

  $taxonomies_array = array(
    'innovation-tag-opengov',
    'innovation-badge-opengov',
  );

  // Only target the case taxonomies
  if ( !in_array($taxonomy, $taxonomies_array) )
    return $args;

  // Set our custom capabilities
  $args["capabilities"]["manage_terms"] = 'manage_case_terms';
  $args["capabilities"]["edit_terms"] = 'edit_case_terms';
  $args["capabilities"]["delete_terms"] = 'delete_case_terms';
  $args["capabilities"]["assign_terms"] = 'assign_case_terms';

  return $args;
}
add_filter( 'register_taxonomy_args', 'bs_add_custom_capabilities_to_case_taxonomies', 10, 3);

// allow posts preview for post author
function bs_allow_preview_for_author( $posts ) {

  $current_user_id = get_current_user_id();
  $current_user_data = get_userdata($current_user_id);
  $current_user_role = $current_user_data->roles;
  $author_id = $posts[0]->post_author;

  // if ( is_singular('case') ) {
    print_r('singular case');
    if(intval($current_user_id) == intval($author_id) || $current_user_role[0] == 'open-gov-admin' ){
      print_r('current author');
      $posts[0]->post_status = 'publish';
    }
  // }

  return $posts;
}
// add_filter( 'posts_results', 'bs_allow_preview_for_author' );

// Auto-populate name, email and organizations for new OpenGov case
function bs_autopopulate_owner_fields_for_opengov_cases( $post_id, $post, $update ) {

  // If this is an update return (the function run only if the post is a new post)
  if ( $update )
    return;

  // if it is not a case post with OpenGov case type return
  $case_type = get_the_terms( $post_id, 'case_type' );
  if ( 'case' != $post->post_type && 'open-government' != $case_type[0]->slug )
    return;

  // get author name, email and organization
  $post_author_id = $post->post_author;
  $user_data = get_userdata( $post_author_id );
  $current_user_email = $user_data->data->user_email;
  $current_user_name = $user_data->data->display_name;
  $current_user_organization = bp_get_profile_field_data('field=Current organisation&user_id='.$post_author_id);

  // update fields
  update_field( 'personal_details_cs_user_email', $current_user_email, $post_id );
  update_field( 'personal_details_cs_user_name', $current_user_name, $post_id );
  update_field( 'personal_details_cs_user_organization', $current_user_organization, $post_id );

}
add_action( 'save_post', 'bs_autopopulate_owner_fields_for_opengov_cases', 9999, 3 );
