<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0">
    <title><?php wp_title('-',true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <meta NAME="ROBOTS" CONTENT="INDEX,FOLLOW">


    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_stylesheet_directory_uri() ?>/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_stylesheet_directory_uri() ?>/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <?php wp_head(); ?>

  </head>
  <body itemscope itemtype="http://schema.org/WebPage" id="opsibody" <?php body_class(); ?>>

  <div class="mainwrapper clearfix">
    <div id="content_wrap">

      <?php if (trim(strip_tags(get_field('top_header_line', 'option'))) != '') { ?>
        <div class="top_bar_msg text-center">
          <div class="container">
            <div class="row">
              <div class="col-md-12"><?php echo get_field('top_header_line', 'option'); ?></div>
            </div>
          </div>
        </div>
      <?php } ?>

      <div class="headertop navbar_wrap alwaysfixed notfixed ">
        <div class="container">
          <div class="row menurow">
            <div class="col-md-12 mainmenu menucol" role="navigation">

              <?php
                  $menuargs = array(
                    'theme_location'  => 'primary',
                    'menu'            => 'Primary Menu',
                    'container'       => '',
                    'container_class' => '',
                    'container_id'    => '',
                    'menu_class'      => 'nav navbar-nav',
                    'menu_id'         => 'primary-menu',
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'before'          => '',
                    'after'           => '',
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '<div class="primary_nav_class" id="primary_nav"><ul id="%1$s" class="%2$s">%3$s</ul></div>',
                    'depth'           => 0,
                    'walker'          => new My_Custom_Nav_Walker()
                  );

                  $mobilemenuargs = array(
                    'theme_location'  => 'mobile',
                    'menu'            => 'Mobile Extra Menu',
                  );

                ?>


                <div class="top_wrap clearfix">

                  <div class="translate inlineb pull-right">
                    <?php echo do_shortcode('[google-translator]'); ?> <!-- <i class="fa fa-chevron-down" aria-hidden="true"></i> -->
                  </div>

                  <div class="search inlineb  pull-right">
                    <?php get_search_form(); ?>
                  </div>
                </div>

                <div class="menuwrapper clearfix">
                  <nav class="navbar navbar-default">

                      <!-- Brand and toggle get grouped for better mobile display -->
                      <div class="navbar-header">

                        <a class="navbar-brand" href="<?php echo site_url(); ?>" title="<?php bloginfo('name'); ?>">
                          <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/observatory-of-public-sector-innovation-logo.png" alt="Observatory of Public Sector Innovation" width="368" height="36" class="logo_img" />
                        </a>

                        <?php wp_nav_menu($mobilemenuargs); ?>

                        <button type="button" class="navbar-toggle pull-right collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                          <span class="sr-only">Toggle navigation</span>
                          <span class="icon-bar top-bar"></span>
                          <span class="icon-bar middle-bar"></span>
                          <span class="icon-bar bottom-bar"></span>
                        </button>
                      </div>

                      <div class="mobile_search_form">
                        <?php get_search_form(); ?>
                      </div>

                      <!-- Collect the nav links, forms, and other content for toggling -->
                      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <?php wp_nav_menu($menuargs); ?>
                      </div><!-- /.navbar-collapse -->

                  </nav>
                </div>



            </div>
          </div>
        </div>
      </div>
      <?php if (!is_front_page()) { ?>
        <div class="topgradient clearfix"></div>
      <?php } ?>
      <div class="main_cont_wrap clearfix">
        <div class="container">
          <div class="row">
            <div class="col-md-12 hidden-xs">
              <?php if (!is_front_page() && function_exists( 'bp_is_blog_page' ) && bp_is_blog_page() ) { ?>
              <div class="nitrobreadcrumb">
                <?php // custom_breadcrumbs(); ?>
                <?php
                if ( function_exists( 'bcn_display' ) ) {
	                bcn_display();
                }
                ?>
              </div>
              <?php } ?>
            </div>

        <?php
          global $post;
          if ($post) {
            $layout = get_post_meta($post->ID, 'layout', true);
          } else {
            $layout = '';
          }

          // always sidebar for buddypress
          if (function_exists( 'bp_is_blog_page' ) && ! bp_is_blog_page() ) {
            $layout = '';
          }
          // but not on registration
          if ( is_page('register') || ( is_singular() && get_post_type( $post ) == 'case' ) || ( is_singular() && get_post_type( $post ) == 'covid_response' ) ) {
            $layout = 'fullpage';
          }

          if (is_singular( array('post') )) {
            if ( is_active_sidebar( 'singleblog' ) && $layout != 'fullpage') { ?>
              <div class="col-sm-3 col-sm-push-9"><div class="sidewrap"><?php dynamic_sidebar( 'singleblog' ); ?></div></div>
            <?php }
          } elseif ( is_active_sidebar( 'sidebar_case_study' ) && ( is_post_type_archive( 'case' ) || is_tax( [ 'case_type', 'innovation-tag', 'country', 'innovation-badge', 'innovation-tag-opengov', 'innovation-badge-opengov'] ) ) ) { ?>
            <div class="col-sm-3 col-sm-push-9">
				<div class="sidewrap sidewrap_csfilters">
					<div class="cs_sidebar_wrap">
            <?php
            $term_id = get_queried_object()->term_id;
            $term = get_term( $term_id, 'case_type' );
            $count_cases = $term->count;
             ?>
						<?php echo __( 'Total cases:', 'opsi' ); ?> <span class="cs_counter"><?php echo $count_cases; ?></span><br />
						<?php echo __( 'Search results:', 'opsi' ); ?> <span class="cs_counter"><?php echo facetwp_display( 'counts' ); ?></span>
					</div>
					<?php
					$form_page_setting =
						is_tax( 'case_type' ) && 'open-government' == get_queried_object()->slug ||
						is_tax( 'innovation-tag-opengov' ) ||
						is_tax( 'innovation-badge-opengov' ) ?
						'case_study_form_page_open_gov' :
						'case_study_form_page';
					$case_study_form_page = get_field( $form_page_setting, 'option' );
					if ( ! empty( $case_study_form_page ) ) {
						$case_study_form_page_url = get_permalink( $case_study_form_page );
						if ( ! empty( $case_study_form_page_url ) ) {
						?>
						<a class="button btn btn-default btn-block big covid-add-response-button" href="<?php echo $case_study_form_page_url; ?>"><?php echo __( 'Add an Innovation', 'opsi' ); ?></a>
						<?php
				        }
			        }
					?>
					<h2><?php echo __( 'Filter innovations:', 'opsi' ); ?></h2>
					<?php dynamic_sidebar( 'sidebar_case_study' ); ?>
					<button class="button btn btn-default btn-block big reset-filters-button" onclick="FWP.reset()"><?php echo __( 'Clear All Filters', 'opsi' ); ?></button>
				</div>
        <?php
          $taxonomy = get_queried_object();
          $tax_term_slug = $taxonomy->slug;
          if ( 'open-government' == $tax_term_slug ) {
            dynamic_sidebar( 'sidebar_case_study_opengov_brand' );
          } else {
            ?></div><?php
          }
        ?>

	        <?php
        }
        elseif ( get_queried_object_id() == get_field( 'covid_response_form_thank_you_page_submit', 'options' ) ) {
        ?>
	          <div class="single_img_wrap col-md-12 covid-banner">
	            <img src="/wp-content/uploads/2020/04/OPSI-Covid19-Tracker-banner.jpg" class="attachment-blog size-blog wp-post-image" alt="OPSI COVID-19 Innovative Response Tracker">
              </div>
	        <?php
        }
        elseif ( is_active_sidebar( 'sidebar_covid_response_archive' ) && ( is_post_type_archive( 'covid_response' ) || is_tax( [ 'response-tag', 'response-badge' ] ) ) ) { ?>

	          <div class="single_img_wrap col-md-12 covid-banner">
		          <img src="/wp-content/uploads/2020/04/OPSI-Covid19-Tracker-banner.jpg" class="attachment-blog size-blog wp-post-image" alt="OPSI COVID-19 Innovative Response Tracker">
	          </div>

	          <div class="col-sm-3 col-sm-push-9">
		          <div class="sidewrap sidewrap_csfilters">
			          <div class="cs_sidebar_wrap">
				          <?php echo __( 'Total responses:', 'opsi' ); ?> <span
					          class="cs_counter"><?php echo wp_count_uncached_posts( 'covid_response' )['publish']; ?></span><br/>
				          <?php echo __( 'Search results:', 'opsi' ); ?> <span
					          class="cs_counter"><?php echo facetwp_display( 'counts' ); ?></span>
			          </div>
			          <?php
			          $covid_form_page = get_field( 'covid_response_form_page', 'option' );
			          if ( ! empty( $covid_form_page ) ) {
				          $covid_form_page_url = get_permalink( $covid_form_page );
				          if ( ! empty( $covid_form_page_url ) ) {
					          ?>
					          <a class="button btn btn-default btn-block big covid-add-response-button" href="<?php echo $covid_form_page_url; ?>"><?php echo __( 'Add a Response', 'opsi' ); ?></a>
					          <?php
				          }
			          }
			          $export_url = get_field( 'export_covid_responses_url', 'option' );
			          if ( ! empty( $export_url ) ) {
				          ?>
				          <a class="button btn btn-default btn-block big covid-export-button" href="<?php echo $export_url . '&time=' . time(); ?>"><?php echo __( 'Export All Data', 'opsi' ); ?></a>
				          <?php
			          }
			          ?>
			          <h2><?php echo __( 'Filter Responses:', 'opsi' ); ?></h2>
			          <?php dynamic_sidebar( 'sidebar_covid_response_archive' ); ?>
			          <button class="button btn btn-default btn-block big reset-filters-button"
			                  onclick="FWP.reset()"><?php echo __( 'Clear All Filters', 'opsi' ); ?></button>
		          </div>
	          </div>

		          <?php
          } elseif ( is_singular('pat_submission') ) {
          ?>
  	          <div class="single_img_wrap col-md-12 covid-banner">
  	            <img src="" class="attachment-blog size-blog wp-post-image" alt="">
                </div>
  	        <?php
          } elseif ( is_active_sidebar( 'sidebar' ) && $layout != 'fullpage' && !(is_home() && !is_front_page()) && bp_is_blog_page()) { ?>
            <div class="col-sm-3 col-sm-push-9"><div class="sidewrap"><?php dynamic_sidebar( 'sidebar' ); ?></div>
          <?php
          } elseif ( is_home() && !is_front_page()) {
            if ( is_active_sidebar( 'blog' ) && $layout != 'fullpage') { ?>
            <div class="col-sm-3 col-sm-push-9"><div class="sidewrap"><?php dynamic_sidebar( 'blog' ); ?></div></div>
            <?php }
          } elseif (!bp_is_blog_page()) {
            if ( is_active_sidebar( 'buddypress' ) && $layout != 'fullpage') { ?>
            <div class="col-sm-3 col-sm-push-9"><div class="sidewrap"><?php dynamic_sidebar( 'buddypress' ); ?></div></div>
            <?php }
          }
        ?>
