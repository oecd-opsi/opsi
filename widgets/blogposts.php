<?php
/**
 * Accordion Archives widget class
 *
 * @since 2.8.0
 */
class WP_Widget_Blog_Posts extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'widget_blogposts', 'description' => __( 'Display latest blogposts') );
		parent::__construct('blog_posts', __('Blog Posts'), $widget_ops);
	}
	function widget( $args, $instance ) {
		extract($args);
    
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty($instance['title'] ) ? __( 'Blog Posts' ) : $instance['title'], $instance, $this->id_base );
		$numofposts = apply_filters( 'blogposts_numofposts', empty($instance['numofposts'] ) ? 5 : $instance['numofposts'], $instance, $this->id_base );

    // The Query
    
    $args = array(
      'post_type'       => 'post',
      'post_status'     => 'publish',
      'posts_per_page'  => $numofposts
    );
    $the_query = new WP_Query( $args );
    
    // The Loop
    if ( $the_query->have_posts() ) {
      
      echo $before_widget;
      if ( $title ) {
        echo $before_title . $title . $after_title;
      }
      
      echo '<ul class="blogposts">';
      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        echo '
          <li class="blogpost">
            <div class="row no-gutter">
              <div class="col-md-3">';
            
            
            if (has_post_thumbnail()) {
              echo '<a href="' . get_the_permalink() . '" title="'. get_the_title() .'">';
              the_post_thumbnail('tiny');
              echo '</a>';
            } else {
              $image_attributes = wp_get_attachment_image_src(139, 'tiny');
              echo '
                <a href="' . get_the_permalink() . '" title="'. get_the_title() .'">
                  <img src="'. $image_attributes[0] .'" alt="no image" width="'. $image_attributes[1] .'" height="'. $image_attributes[2] .'" />
                </a>
                ';
            }
            
        echo '</div>
            
              <div class="col-md-9">
                <h4><a href="' . get_the_permalink() . '" title="'. get_the_title() .'">'. get_the_title() .'</a></h4>
                <p class="blogdate">
                  '. get_the_date() .'
                </p>
              </div>
          </li>';
      }
      echo '</ul>';
      
      /* Restore original Post Data */
      wp_reset_postdata();
      wp_reset_query();
		echo $after_widget;
    }
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'numofposts' => '5') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['numofposts'] = strip_tags($new_instance['numofposts']);
		return $instance;
	}
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'numofposts' => '5') );
		$title      = strip_tags($instance['title']);
		$numofposts = strip_tags($instance['numofposts']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('numofposts'); ?>"><?php _e('Number of Posts:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('numofposts'); ?>" name="<?php echo $this->get_field_name('numofposts'); ?>" type="number" value="<?php echo esc_attr($numofposts); ?>" /></p>
<?php
	}
}
function register_blog_posts_widget() {
    register_widget( 'WP_Widget_Blog_Posts' );
}
add_action( 'widgets_init', 'register_blog_posts_widget' );