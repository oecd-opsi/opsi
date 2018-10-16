<?php
  if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
?>


  <?php
    $image_upload   = get_sub_field('image_upload');
    $overlay_color  = get_sub_field('overlay_color');
    $stretch_image  = get_sub_field('stretch_image');
    $image_height   = get_sub_field('image_height');
    $gradient       = '';
    
    if ($overlay_color != '') {
      $gradient = 'background: rgba(255,255,255,0);
        background: -moz-radial-gradient(center, ellipse cover, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 40%, '. $overlay_color .' 100%);
        background: -webkit-radial-gradient(center, ellipse cover, rgba(255,255,255,0) 0%,rgba(255,255,255,0) 40%,'. $overlay_color .' 100%);
        background: radial-gradient(ellipse at center, rgba(255,255,255,0) 0%,rgba(255,255,255,0) 40%,'. $overlay_color .' 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'rgba(255,255,255,0)\', endColorstr=\''. $overlay_color .'\',GradientType=1 );';
  
      $gradient = str_replace("\n", '', $gradient);
      $gradient = str_replace("\t", '', $gradient);
    }
  ?>
  
  <div class="col-md-<?php the_sub_field('column_width'); ?> layout_<?php echo get_row_layout(); ?> ">
    <div class="fullwidth percenth image_upload height<?php echo $image_height; ?> <?php echo ($stretch_image === true ? 'stretch_image' : ''); ?>" style="background-image: url('<?php echo $image_upload['url']; ?>');">
    
    <div class="overlay_color" <?php echo ($gradient != '' ? 'style="'. $gradient .'"' : ''); ?>></div>
    </div>  
  </div>