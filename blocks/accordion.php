<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  ?>
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?>">
  <?php 
    
    $accordion_content = get_sub_field('accordion_content');
    $acid = 'ac_'.uniqid();
    $colid = 'col_'.uniqid();
    
    if (!empty($accordion_content)) {
      ?>
      
      <div class="panel-group" id="<?php echo $acid; ?>">
      
      <?php
      $i = 0;
      foreach ($accordion_content as $ac) {
        $i++;
        ?>
        
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#<?php echo $acid; ?>" href="#<?php echo $colid.$i; ?>" class="<?php if ($i==1) { ?>open<?php } ?>">
                <?php echo $ac['header']; ?>
              </a>
            </h4>
          </div>
          <div id="<?php echo $colid.$i; ?>" class="panel-collapse collapse <?php if ($i==1) { ?>in<?php } ?>">
            <div class="panel-body">
              <?php echo $ac['text']; ?>
            </div>
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