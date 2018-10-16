<?php
/**
 * Accordion Archives widget class
 *
 * @since 2.8.0
 */
class WP_Widget_Archives_Accordion extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'widget_accordion_archive', 'description' => __( 'A yearly archive of your site&#8217;s Posts in an accordion.') );
		parent::__construct('accordion_archives', __('Archives Accordion'), $widget_ops);
	}
	function widget( $args, $instance ) {
		extract($args);
    
    global $wpdb;
    
    // get years posts count
    /** Grab the years that contain published posts, and a count of how many */
    $query = $wpdb->prepare('
        SELECT YEAR(%1$s.post_date) AS `year`, count(%1$s.ID) as `posts`
        FROM %1$s
        WHERE %1$s.post_type IN ("post")
        AND %1$s.post_status IN ("publish")
        GROUP BY YEAR(%1$s.post_date)
        ORDER BY %1$s.post_date',
        array_fill(0, 7, $wpdb->posts)
    );
    $results = $wpdb->get_results($query);
    
    $yearscount  = array();
    
    if(!empty($results)) {
      foreach($results as $result) {
        $yearscount[$result->year] = $result->posts;
      }
    }    
    
    // get motnhs posts count
    $query = $wpdb->prepare('
        SELECT LPAD(MONTH(%1$s.post_date), 2, "0") AS `month`, YEAR(%1$s.post_date) AS `year`, count(%1$s.ID) as `posts`
        FROM %1$s
        WHERE %1$s.post_type IN ("post")
        AND %1$s.post_status IN ("publish")
        GROUP BY YEAR(%1$s.post_date), MONTH(%1$s.post_date)
        ORDER BY %1$s.post_date',
        array_fill(0, 9, $wpdb->posts)
    );
    $results = $wpdb->get_results($query);
    
    $monthscount  = array();
    
    if(!empty($results)) {
      foreach($results as $result) {
        $monthscount[$result->year.$result->month] = $result->posts;
      }
    }

    wp_reset_query();
    
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty($instance['title'] ) ? __( 'Accordion Archives' ) : $instance['title'], $instance, $this->id_base );
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
?>
		<ul>
<?php
		/**
		 * Filter the arguments for the Archives widget.
		 *
		 * @since 2.8.0
		 *
		 * @see wp_get_archives()
		 *
		 * @param array $args An array of Archives option arguments.
		 */
		$archives = strip_tags(wp_get_archives( apply_filters( 'widget_accordion_archives_args', array(
			'type'            => 'monthly',
			'format'          => 'custom',
			'echo'            => 0,
			'after'           => ','
		) ) ) );
		$archives = explode(',', $archives);
		$months = array();
		$years = array();
		// Grab our years first
		foreach ($archives as $archive) {
			$archive = explode(' ', $archive);
			if (isset($archive[1])) {
				array_push($years, $archive[1]);
			}
		}
		$years = array_values(array_unique($years));
		$i = 0;
		foreach ($years as $year) {
		?><li class="archive-accordion-year"><a href="<?php echo get_year_link($year); ?>"><?php
			echo $year;
			?></a> <span class="archive_count">(<?php echo $yearscount[$year]; ?>)</span> <i class="fa fa-chevron-down" aria-hidden="true"></i><ul><?php
			foreach ($archives as $archive) {
				$archive = explode(' ', $archive);
				if (!empty($archive[1]) && $archive[1] == $year) {
					echo '<li class="archive-accordion-month">
          <i class="fa fa-level-up" aria-hidden="true"></i> 
          <a href="' . 
					// Get the archive link
					get_month_link($year, date("m", strtotime($archive[0] . '-' . $year))) . 
					'">' . trim($archive[0]) . '</a> <span class="archive_count">('. $monthscount[$year.date("m", strtotime($archive[0] . '-' . $year))] .')</span></li>';
				}
			}
			?></ul><?php
		?></li><?php
		}
?>
		</ul>
<?php
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}
function register_accordion_archive_widget() {
    register_widget( 'WP_Widget_Archives_Accordion' );
}
add_action( 'widgets_init', 'register_accordion_archive_widget' );