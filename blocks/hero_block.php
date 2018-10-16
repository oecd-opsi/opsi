<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <?php 
    if (get_sub_field('remove_column_open') === false) {
  ?>
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?> ">
  <?php } ?>
    
    <div class="hb_inner">
      <h3><?php the_sub_field('maintext'); ?></h3>
      <h4 class="subheader"><?php the_sub_field('subtext'); ?></h4>
      
      <?php 
        $buttonlink1    = get_sub_field('buttonlink1');
        $buttonlink2    = get_sub_field('buttonlink2');
        $button_color_1 = get_sub_field('button_color_1');
        $button_color_2 = get_sub_field('button_color_2');

        if ($buttonlink1) {
          echo '
            <a href="'. get_permalink($buttonlink1->ID) .'" title="'. $buttonlink1->post_title .'" class="button btn '. ($button_color_1 ? 'btn-'.$button_color_1 : '') .' '. (!$buttonlink2 ? 'big' : '') .'">
              '. get_sub_field('buttontext1') .' <i class="fa '. get_sub_field('button_icon_1') .'" aria-hidden="true"></i></a>';
        }
        
        if ($buttonlink2) {
          echo '
            <a href="'. get_permalink($buttonlink2->ID) .'" title="'. $buttonlink2->post_title .'" class="button btn '. ($button_color_2 ? 'btn-'.$button_color_2 : '') .' '. (!$buttonlink1 ? 'big' : '') .'">
              '. get_sub_field('buttontext2') .' <i class="fa '. get_sub_field('button_icon_2') .'" aria-hidden="true"></i></a>';
        }
      ?>
    </div>
  
  <?php 
    if (get_sub_field('remove_column_close') === false) {
  ?>
  </div>
  <?php } ?>