<?php

add_filter( 'facetwp_facet_types', 'post_types_filter_facet_init');

function post_types_filter_facet_init ( $facet_types ) {
    $facet_types['post_status'] = new FacetWP_Facet_Post_Status();
    return $facet_types;
}



class FacetWP_Facet_Post_Status
{

    function __construct() {
        $this->label = __( 'Post Status', 'fwp' );
    }


    /**
     * Load the available choices
     */
    function load_values( $params ) {
        global $wpdb;

        $facet = $params['facet'];
        $from_clause = $wpdb->prefix . 'facetwp_index f';
        $where_clause = $params['where_clause'];

        // Post Status setting
        $post_status = $facet['post_status'];
		
		echo '<pre>';
var_dump($post_status);
echo '</pre>';

        $from_clause = apply_filters( 'facetwp_facet_from', $from_clause, $facet );
        $where_clause = apply_filters( 'facetwp_facet_where', $where_clause, $facet );

        $sql = "
        SELECT f.facet_value, f.facet_display_value, f.term_id, f.parent_id, f.depth, COUNT(DISTINCT f.post_id) AS counter
        FROM $from_clause
        WHERE f.facet_name = '{$facet['name']}' $where_clause
        GROUP BY f.facet_value
        ORDER BY f.depth, counter DESC, f.facet_display_value ASC
        ";

        return $wpdb->get_results( $sql, ARRAY_A );
    }


    /**
     * Generate the output HTML
     */
    function render( $params ) {

        $output = '';
		echo '<pre>';
var_dump($facet);
echo '</pre>';
        $facet = $params['facet'];
        $values = (array) $params['values'];
        $selected_values = (array) $params['selected_values'];

        $key = 0;
        foreach ( $values as $key => $result ) {
            $selected = in_array( $result['facet_value'], $selected_values ) ? ' checked' : '';
            $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
            $output .= '<div class="facetwp-checkbox' . $selected . '" data-value="' . esc_attr( $result['facet_value'] ) . '">';
            $output .= esc_html( $result['facet_display_value'] ) . ' <span class="facetwp-counter">(' . $result['counter'] . ')</span>';
            $output .= '</div>';
        }

        return $output;
    }


    /**
     * Return array of post IDs matching the selected values
     * using the wp_facetwp_index table
     */
    function filter_posts( $params ) {
        global $wpdb;

        $output = array();
        $facet = $params['facet'];
        $selected_values = $params['selected_values'];

        $sql = $wpdb->prepare( "SELECT DISTINCT post_id
            FROM {$wpdb->prefix}facetwp_index
            WHERE facet_name = %s",
            $facet['name']
        );

        foreach ( $selected_values as $key => $value ) {
            $selected_values = implode( "','", $selected_values );
            $output = facetwp_sql( $sql . " AND facet_value IN ('$selected_values')", $facet );
        }

        return $output;
    }


    /**
     * Load and save facet settings
     */
    function admin_scripts() {
?>
<script>
(function($) {
    wp.hooks.addAction('facetwp/load/post_status', function($this, obj) {
        $this.find('.facet-post_status').val(obj.post_status);
    });

    wp.hooks.addFilter('facetwp/save/post_status', function(obj, $this) {
        obj['post_status'] = $this.find('.facet-post_status').val();
        return obj;
    });
})(jQuery);
</script>
<?php
    }


    /**
     * Parse the facet selections + other front-facing handlers
     */
    function front_scripts() {
?>
<script>
(function($) {
    wp.hooks.addAction('facetwp/refresh/post_status', function($this, facet_name) {
        $this.find('.facet-post_status').val(obj.post_status);
    });

    wp.hooks.addFilter('facetwp/selections/post_status', function(output, params) {
        obj['post_status'] = $this.find('.facet-post_status').val();
		return obj;
    });

})(jQuery);
</script>
<?php
    }


    /**
     * Admin settings HTML
     */
    function settings_html() {
		
		$get_post_stati = get_post_stati(array());		
?>
        <div class="facetwp-row">
			<div>
                <?php _e('Post Statuses', 'fwp'); ?>:
			</div>
			<div>
        
				<p>Available post statuses:</p>
				<?php foreach ( $get_post_stati as $s )  { if ( $s == 'publish' ) continue; ?>
				<?php echo $s; ?><br />
				<?php } ?>
				<br />
				<input type="text" class="facet-post_status" value="" />
			</div>
		</div>

<?php
    }
}