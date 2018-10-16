<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?>">
      <<?php the_sub_field('header_type'); ?> class="text-<?php the_sub_field('align'); ?>">
        <?php the_sub_field('header_text'); ?>
      </<?php the_sub_field('header_type'); ?>>
  </div>
  <!-- header column end -->