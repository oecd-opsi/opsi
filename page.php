<?php get_header();

    global $post;

    $has_sidebar = 0;
    $layout = get_post_meta($post->ID, 'layout', true);
    if($layout != 'fullpage' && is_active_sidebar( 'sidebar' )) {
      $has_sidebar = 3;
    }

  ?>



    <div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo ($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">

      <?php
      if( is_page( 26297 )) {
        ?>
        <div class="row">
          <div class="col-sm-12">
            <div class="bi-section-false-tabs">
              <a href="/behavioural-science/">About Behavioural Insights (BI)</a>
              <a href="/bi-units">BI Units</a>
              <a href="/bi-projects">BI Projects</a>
            </div>
          </div>          
        </div>
        <?php
      }
       ?>

  <?php while ( have_posts() ) : the_post(); $postid = get_the_ID(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                  <!-- Display featured image in right-aligned floating div -->
              <div class="single_img_wrap <?php echo (!has_post_thumbnail() ? 'noimg' : ''); ?>">
                <?php
                  if ( has_post_thumbnail()) {
                    $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'large');
                    $img_info =  wp_get_attachment_metadata( get_post_thumbnail_id(get_the_ID()) );

                    echo '
                    <a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" class="featuredimglink fancybox" >';
                    echo get_the_post_thumbnail(get_the_ID(), 'blog');
                    echo '</a>';

                    if ($img_info['image_meta']['caption'] != '') {
                      echo '<p>'. $img_info['image_meta']['caption'] .'</p>';
                    }
                  }
                ?>
                <?php
                  if (!is_front_page()) {
                    echo (get_field('hide_social_sharing') === true ? '' : wpfai_social());
                  }
                ?>
              </div>
              <?php if (get_field('hide_page_title') !== true) { ?>
              <h1 class="entry-title <?php echo (get_field('hide_social_sharing') === true ? 'nosocial' : ''); ?>"><?php the_title(); ?></h1>
              <?php } ?>
              <?php if (get_field('subheader') !='' ) { ?>
                <h2 class="entry-subtitle"><?php echo get_field('subheader'); ?></h2>
              <?php } ?>
              <div class="entry-content"><?php the_content(); ?></div>

              <?php get_template_part( 'blocks' ); ?>
          </article>
      <?php // comments_template(); ?>
      <?php endwhile; ?>

    </div>

  <?php wp_reset_query(); ?>


<?php get_footer(); ?>
