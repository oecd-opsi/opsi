<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  
  
  $link = $link_title = '';

  if (get_sub_field('external_link') != '') {
    $link = get_sub_field('external_link');
  } else {
    $link_post = get_sub_field('link');
    if ($link_post) {
      $link = get_the_permalink($link_post->ID);
      $link_title = $link_post->post_title;
    }
  }
  
?>

  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?>">
    <div class="btn_wrap text-<?php the_sub_field('align'); ?>">
      <a href="<?php echo $link; ?>" title="<?php echo $link_title; ?>" class="text-center btn btn-<?php the_sub_field('style'); ?> btn-<?php the_sub_field('size'); ?>" role="button"><?php the_sub_field('text'); ?></a>
    </div>
  </div>