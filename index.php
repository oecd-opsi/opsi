<?php 
  get_header();

  global $post;
  $title_element = '';
  if ( is_home() && !is_front_page()) {
    $title_element = '<h1 class="entry-title blog-title">'. get_the_title(get_option('page_for_posts')) .'</h1>';
  }
  $has_sidebar = 0;
  $layout = '';
  if ($post) {
    $layout = get_post_meta($post->ID, 'layout', true);
    if($layout != 'fullpage' && is_active_sidebar( 'sidebar' )) {
      $has_sidebar = 3;
    }
  }
  
?>

  


  <div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo ($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">
  
  <?php echo $title_element; ?>
    
    <div class="row post_list">

      <?php 
        while ( have_posts() ) {
          the_post();
          get_template_part( 'content', 'blog' );
        }
        
        $pagination = get_the_posts_pagination( array(
          'mid_size' => 2,
          'prev_text' => '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
          'next_text' => '<i class="fa fa-chevron-right" aria-hidden="true"></i>'
        ) );
        
        
      ?>
      
    </div>
    <div class="row pagination_wrap">
      <div class="col-md-12">
        <?php echo $pagination; ?>
      </div>
    </div>
  </div>

  <?php wp_reset_query(); ?>


<?php get_footer(); ?>