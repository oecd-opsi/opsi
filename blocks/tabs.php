<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?>">
  <?php 
    
    $tab_content = get_sub_field('tab_content');
    $tcid = 'ac_'.uniqid();
    $colid = 'col_'.uniqid();
    
    if (!empty($tab_content)) {
      ?>
      
      <ul class="nav nav-tabs nav-justified" id="<?php echo $tcid; ?>" role="tablist">
      
      <?php
      $i = 0;
      foreach ($tab_content as $tc) {
        $i++;
        ?>
          <li role="presentation" class="<?php if ($i==1) { ?>active<?php } ?>">
            <a href="#<?php echo $tcid.$i; ?>" aria-controls="<?php echo $tcid.$i; ?>" role="tab" data-toggle="tab">
              <?php echo $tc['header']; ?>
            </a>
          </li>        
        <?php
      }
      ?>
      
      </ul>
      
      <!-- Tab panes -->
      <div class="tab-content">
      <?php
      $i = 0;
      foreach ($tab_content as $tc) {
        $i++;
        ?>
          <div role="tabpanel" class="tab-pane <?php if ($i==1) { ?>active<?php } ?>" id="<?php echo $tcid.$i; ?>">
            <?php echo $tc['text']; ?>
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