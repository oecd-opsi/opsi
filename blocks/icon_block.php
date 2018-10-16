<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <div class="col-sm-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?> ">
  
    <div class="row">
    <?php 
      $icon = get_sub_field('icon'); 
      $link = get_sub_field('link');
      
      if (!empty($icon)) {
        ?>
        <div class="col-md-5 col-xs-3 text-center">
          <?php 
            if ($link) {
              echo '<a href="'. get_permalink($link) .'" title='. get_the_title($link) .'>';
            }
          ?>
          <img 
            src="<?php echo $icon['sizes']['medium']; ?>" 
            width="<?php echo $icon['sizes']['medium-width']; ?>" 
            alt="<?php echo $icon['title']; ?>" 
          />
          <?php 
            if ($link) {
              echo '</a>';
            }
          ?>
        </div>
        <?php
      }
    ?>
        <div class="col-md-<?php echo (!empty($icon) ? '7' : '12'); ?> col-xs-<?php echo (!empty($icon) ? '9' : '12'); ?>">
          <h2 class="icon_header">
          <?php 
            if ($link) {
              echo '<a href="'. get_permalink($link) .'" title='. get_the_title($link) .'>';
            }
          ?>
          <?php the_sub_field('header'); ?>
          <?php 
            if ($link) {
              echo '</a>';
            }
          ?>
          </h2>
          <div class="icon_description"><?php the_sub_field('main_text'); ?></div>
        </div>
    </div>
  </div>
  <!-- icon block column end -->