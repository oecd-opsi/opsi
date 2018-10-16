<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?>">
  <?php 
    
    $images = get_sub_field('images');
    $arrows = get_sub_field('arrows');
    $dots   = get_sub_field('dots');
    $sid    = uniqid();

    
    if (!empty($images)) {
      ?>
      
      <div 
        id="<?php echo $sid; ?>" 
        class="carousel slide percenth height<?php echo get_sub_field('height'); ?> <?php echo (get_sub_field('captionback') ? 'captionback' : ''); ?> <?php echo get_sub_field('width'); ?>" 
        <?php echo (get_sub_field('autoplay') ? 'data-ride="carousel"' : ''); ?>
        <?php echo (get_sub_field('interval') ? 'data-interval="'. get_sub_field('interval') .'000"' : ''); ?>
        <?php echo (get_sub_field('pause') > 0 ? 'data-pause="hover"' : 'data-pause="null"'); ?>
      >
      
      <?php
      if ($dots) {
        ?>
        <ol class="carousel-indicators">
        <?php
        $i = 0;
        foreach ($images as $img) {
          ?>
            <li data-target="#<?php echo $sid; ?>" data-slide-to="<?php echo $i; ?>" class="<?php echo ($i == 0 ? 'active' : ''); ?>"></li>
          <?php
          $i++;
        }
        ?>
        </ol>
      <?php
      }
      ?>
      
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
        
        <?php
        $i = 0;
        foreach ($images as $img) {
          $img_array = wp_get_attachment_image_src($img['image_file'], 'full');
          ?>
          <div class="item <?php echo ($i == 0 ? 'active' : ''); ?>" style="background-image: url('<?php echo $img_array[0]; ?>');">
            <div class="carousel-caption">
              <?php echo $img['text']; ?>
            </div>
          </div>
          
          <?php
          $i++;
        }
        ?>
        </div>
        
        
        <?php if ($arrows) { ?>
          <!-- Controls -->
          <a class="left carousel-control" href="#<?php echo $sid; ?>" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#<?php echo $sid; ?>" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        
        <?php } ?>
        
      </div>
      
      <?php
    }
      
  ?>
  </div>
  <!-- free text column end -->