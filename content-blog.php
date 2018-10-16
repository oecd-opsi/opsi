<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  
      $taxonomy = 'category';
      if (get_post_type() == 'project') {
        $taxonomy = 'project_category';
      }
      
  ?>
  <div class="col-md-4 col-sm-6 post_col">

  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="pb_img">
      <a href="<?php the_permalink(); ?>" class="pb_more_img_link" title="<?php the_title(); ?>">
        <?php if (has_post_thumbnail()) {
          the_post_thumbnail('blog_thumb');
        } else {
          $image_attributes = wp_get_attachment_image_src(139, 'blog_thumb');
          echo '<img src="'. $image_attributes[0] .'" alt="no image" width="'. $image_attributes[1] .'" height="'. $image_attributes[2] .'" />';
        }
        ?>
      </a>
      <?php echo wpfai_social(); ?>
    </div>
    
    <div class="post_meta">
      <p class="author">
        <i class="fa fa-pencil" aria-hidden="true"></i> <?php the_author_posts_link(); ?>
      </p>
      <p class="author">
        <i class="fa fa-calendar" aria-hidden="true"></i> <?php echo get_the_date(); ?>
      </p>
    </div>
    
    <h3 class="post_title">
      <span class="pb_title_in">
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
      </span>
    </h3>
    
    <div class="post_categories">
      <i class="fa fa-folder-open-o" aria-hidden="true"></i> <?php echo get_the_term_list(get_the_ID(), $taxonomy, '', ', ', ''); ?>
    </div>
    
    <div class="pb_excerpt"><?php  echo wp_trim_words(get_the_excerpt(), 35); ?></div>

    <div class="post_more">
      <a href="<?php the_permalink(); ?>" class="btn btn-info btn-sm pb_more_link" title="<?php the_title(); ?>">
        <?php echo __('Read more', 'opsi'); ?> <i class="fa fa-angle-right" aria-hidden="true"></i>
      </a>
    </div>
    
    <div class="post_tags">
      <?php echo get_the_tag_list(); ?>
    </div>
    
  </article>


  
  </div>