<?php
	get_header();

	global $post;
	$title_element = '';

	$has_sidebar = 0;
	$layout = '';

	if( is_active_sidebar( 'sidebar_case_study' )) {
		$has_sidebar = 3;
	}

?>




<div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo ($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">

	<div class="cs_top_filters">
		<div class="row">
			<div class="col-sm-10">
				<?php echo facetwp_display( 'facet', 'search' ); ?>
			</div>
			<!-- <div class="col-sm-3">
				<div class="switchwrap switchwrap_archive text-right">
					<span><?php echo __( 'Show Archives', 'opsi' ); ?>&nbsp;</span>
					<label class="switch">
					  <input type="checkbox" id="archivetoggle" value="1" <?php echo ( isset( $_GET['archive'] ) && intval( $_GET['archive'] ) == 1 ? 'checked="checked"' : '' ); ?> />
					  <span class="slider"></span>
					</label>
				</div>
			</div> -->
			<div class="col-sm-2">
				<div class="hidemaptoggle">
					<i class="fa fa-chevron-down" aria-hidden="true"></i> <?php echo __( 'Hide Map', 'opsi' ); ?>
				</div>
			</div>

		</div>
	</div>
	<div class="cs_top_map hoverzoom-">
		<div id="regions_div" style="width: 100%; height: 500px;"></div>
		<div id="map-bar">
			<div class="meter_label"><?php echo __( 'Innovations:', 'opsi' ); ?></div>
			<div class="min">0</div>
			<div class="rule"><div id="curs"></div></div>
			<div class="max"></div>
		</div>

	</div>
	<div class="cs_pager_sorter cs_sidebar_wrap">
		<div class="row">
			<div class="col-sm-4">
				<?php echo __( 'Cases found:', 'opsi' ); ?> <span class="cs_counter"><?php echo facetwp_display( 'counts' ); ?></span>
			</div>
			<div class="col-sm-4">
				<span class="strong"><?php echo __( 'Per page:', 'opsi' ); ?></span> <span class="cs_counter"><?php echo facetwp_display( 'per_page' ); ?></span>
			</div>
			<div class="col-sm-4">
				<span class="strong"><?php echo __( 'Sorted by:', 'opsi' ); ?></span> <span class="cs_counter"><?php echo facetwp_display( 'sort' ); ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				An additional 395 case studies are available in the case study <a href="https://oecd-opsi.org/case-study-archive/" target="_blank">archive</a>.
			</div>
		</div>
	</div>

	<div class="facetwp-template">

		<?php echo $title_element; ?>

		<div class="row post_list">

			<?php
			while ( have_posts() ) {
			  the_post();
			  get_template_part( 'content', 'case' );
			}

			$pagination = get_the_posts_pagination( array(
			  'mid_size' => 2,
			  'prev_text' => '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
			  'next_text' => '<i class="fa fa-chevron-right" aria-hidden="true"></i>'
			) );


			?>

		</div>
		<div class="row pagination_wrap">
			<div class="col-md-12">
				<?php echo facetwp_display( 'pager' ); ?>
			</div>
		</div>

		<?php
		$disclaimer_setting = 'opsi' == get_queried_object()->slug ? 'case_study_disclaimer_page' : 'case_study_disclaimer_page_open_gov';
		$case_study_disclaimer_page = get_field( $disclaimer_setting, 'option' );
		if ( ! empty( $case_study_disclaimer_page ) ) {
			?>
			<div class="hb_inner text-left">
				<?php echo get_the_content( null, false, $case_study_disclaimer_page ); ?>
			</div>
			<?php
		}
		?>

	</div>
</div>

<?php
$term_id = get_queried_object()->term_id;
if ( 893 == $term_id ) {
	?>
	<script>
	(function($) {
		$(document).on('facetwp-loaded', function() {
			var levelGovFilter = $('aside._level_of_government'),
					government_check = $('div.facetwp-checkbox[data-value="government"]');
			levelGovFilter.hide()
			if (government_check.hasClass('checked')) {
				levelGovFilter.show();
			}
		});
	})(jQuery);
	</script>
	<?php
} ?>


<?php wp_reset_query(); ?>

<?php get_footer(); ?>
