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
  <body itemscope itemtype="http://schema.org/WebPage" <?php body_class(); ?>>

  <div class="mainwrapper clearfix">
    <div id="content_wrap" class="to-test-if-this-initializes">

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
              <div class="nitrobreadcrumb">

                <!-- special breadcrumb handling for toolkit templates - not as dynamic as would be ideal -->
                <span property="itemListElement" typeof="ListItem">
                  <a property="item" typeof="WebPage" title="Go to Observatory of Public Sector Innovation." href="/" class="home"><span property="name"><i class="fa fa-home"></i></span></a><meta property="position" content="1">
                </span> &gt;
                <span property="itemListElement" typeof="ListItem"><span property="name">
                  <a property="item" typeof="WebPage" title="All toolkits" href="/toolkit-navigator/" class="home">Toolkits</a><meta property="position" content="1">
                </span><meta property="position" content="2"></span>
                &gt;
                <span property="itemListElement" typeof="ListItem"><span property="name">
                  <?php the_title(); ?>
                </span><meta property="position" content="2"></span>
              </div>
            </div>

            </div>

        <?php
          global $post;
          if ($post) {
            $layout = get_post_meta($post->ID, 'layout', true);
          } else {
            $layout = '';
          }

          // always sidebar for buddypress
          if (!bp_is_blog_page()) {
            $layout = '';
          }
          // but not on registration
          if (is_page('register')) {
            $layout = 'fullpage';
          }



        ?>
