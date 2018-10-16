<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?> ">
  
    <?php
      $cols             = 12 / get_sub_field('column_per_row');
      $categories       = get_sub_field('categories');
      $cat_exc          = get_sub_field('categories_exc');
      $project_cats     = get_sub_field('project_categories');
      $project_cats_exc = get_sub_field('project_categories_exc');
      $color            = get_sub_field('color');
      $limit            = get_sub_field('limit');
      $block_post_type  = get_sub_field('block_post_type');
      
      $args = array(
        'post_type'         => $block_post_type,
        'status'            => 'publish',
        'posts_per_page'    => $limit
      );
      
      if ($block_post_type == 'post') {
        $args['category__in']     = $categories;
        $args['category__not_in'] = $cat_exc;
      }
      if ($block_post_type == 'project') {
        if (!empty($project_cats)) {
          $args['tax_query'][] = array(
            'taxonomy' => 'project_category',
            'field'    => 'term_id',
            'terms'    => $project_cats,
            'operator' => 'IN',
          );
        }
        if (!empty($project_cats_exc)) {
          $args['tax_query'][] = array(
              'taxonomy' => 'project_category',
              'field'    => 'term_id',
              'terms'    => $project_cats_exc,
              'operator' => 'NOT IN',
          );
        }
        if (!empty($args['tax_query'])) {
          $args['tax_query']['relation'] = 'AND';
        }
      }
      $posts_array = get_posts( $args );
      
      if ($posts_array) {
        ?>
        
        <?php if (get_sub_field('header_text') != '') { ?>
          <div class="layout_header <?php echo $color; ?> toggle_collapse">
            <<?php the_sub_field('header_type'); ?>>
              <?php the_sub_field('header_text'); ?>
            </<?php the_sub_field('header_type'); ?>>
          </div>
        <?php } ?>
        
        <div class="row <?php echo $color; ?> collapse-xs">

        <?php
        foreach ( $posts_array as $post ) {
          setup_postdata( $post ); ?>
          <div class="col-md-<?php echo $cols; ?> col-sm-6 post_block_col">
            <h3 class="pb_title">
              <span class="pb_title_in">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
              </span>
            </h3>
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
              <div class="pb_more">
                <a href="<?php the_permalink(); ?>" class="pb_more_link" title="<?php the_title(); ?>">
                  <?php echo __('Read more', 'opsi'); ?> <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>
            <div class="pb_excerpt"><?php echo wp_trim_words( $post->post_content, 14, '' ); ?></div>            
          </div>
        <?php 
        } 
        wp_reset_postdata();
		wp_reset_query();
        ?>
        </div>
        <?php
      }
    ?>
  
  </div>