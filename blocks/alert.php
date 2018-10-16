<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?>">
    <div class="alert alert-<?php the_sub_field('style'); ?> <?php echo (get_sub_field('dismiss') ? 'alert-dismissable' : ''); ?>">
      <?php echo (get_sub_field('dismiss') ? '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' : ''); ?>
      <?php echo get_sub_field('text'); ?>
    </div>
  </div>