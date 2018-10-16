<?php get_header();

    global $post;
    
    $has_sidebar = 3;
    if ($post) {
      $layout = get_field('layout', $post->ID);
      if(!($layout != 'fullpage' && is_active_sidebar( 'buddypress' ))) {
        $has_sidebar = 0;
      }
    }
    
  ?>



    <div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo ($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">

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
              </div>

              <div class="entry-content"><?php the_content(); ?></div>
              

          </article>
      <?php // comments_template(); ?>
      <?php endwhile; ?>
      
    </div>

  <?php wp_reset_query(); ?>


<?php get_footer(); ?>