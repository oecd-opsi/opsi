<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?> text-center">
      <?php echo wpfai_social(); ?>
  </div>
  <!-- header column end -->