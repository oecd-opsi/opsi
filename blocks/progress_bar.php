<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?>">
  <?php 
    
    $bar = get_sub_field('bar');

    
    if (!empty($bar)) {
      ?>
      
      <div class="bar-group">
      
      <?php
      $i = 0;
      foreach ($bar as $b) {
        $i++;
        ?>
        
        <?php echo $b['text']; ?>
        <div class="progress">
          <div class="progress-bar progress-bar-<?php echo $b['color']; ?>" role="progressbar" aria-valuenow="<?php echo $b['percentage']; ?>"
          aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $b['percentage']; ?>%; <?php echo ($b['custom_color'] != '' ? 'background-color: '. $b['custom_color'] .';' : ''); ?>">
            <span class="pc"><?php echo $b['percentage']; ?>%</span>
          </div>
        </div>
        
        <?php
      }
      ?>
      
      </div>
      
      <?php
    }
      
  ?>
  </div>
  <!-- free text column end -->