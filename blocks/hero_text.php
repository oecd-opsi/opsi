<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?> ">
    <span class="pretext"><?php the_sub_field('pretext'); ?></span>
    <span class="maintext"><?php the_sub_field('maintext'); ?></span>
    <span class="aftertext"><?php the_sub_field('aftertext'); ?></span>
  </div>
  <!-- hero text column end -->