<?php
	get_header();

	global $post;
	$title_element = '';

	$has_sidebar = 0;
	$layout = '';

	if( is_active_sidebar( 'sidebar_bi_project' )) {
		$has_sidebar = 3;
	}

?>

<div class="col-sm-12">
	<div class="bi-section-false-tabs">
		<a href="#">About Behavioural Insights (BI)</a>
		<a href="/bi-units">BI Units</a>
		<a href="/bi-projects">BI Projects</a>
	</div>
</div>

<div class="col-sm-12">
	<div class="cs_top_filters">
		<div class="row">
			<div class="col-sm-10">
				<?php echo facetwp_display( 'facet', 'search' ); ?>
			</div>

			<div class="col-sm-2">
				<div class="hidemaptoggle">
					<i class="fa fa-chevron-down" aria-hidden="true"></i> <?php echo __( 'Hide Map', 'opsi' ); ?>
				</div>
			</div>

		</div>
	</div>

	<div class="cs_top_map hoverzoom-">
		<div id="regions_div" style="width: 100%; height: 500px;">
			<div id="bi-modal"></div>
		</div>
		<div id="map-bar">
			<div class="meter_label"><?php echo __( 'BI projects:', 'opsi' ); ?></div>
			<div class="min">0</div>
			<div class="rule"><div id="curs"></div></div>
			<div class="max"></div>
		</div>
	</div>

	<div class="cs_pager_sorter cs_sidebar_wrap">
		<div class="row">
			<div class="col-sm-4">
				<?php echo __( 'BI projects found:', 'opsi' ); ?> <span class="cs_counter"><?php echo facetwp_display( 'counts' ); ?></span>
			</div>
			<div class="col-sm-4">
				<span class="strong"><?php echo __( 'Per page:', 'opsi' ); ?></span> <span class="cs_counter"><?php echo facetwp_display( 'per_page' ); ?></span>
			</div>
			<div class="col-sm-4">
				<span class="strong"><?php echo __( 'Sorted by:', 'opsi' ); ?></span> <span class="cs_counter"><?php echo facetwp_display( 'sort' ); ?></span>
			</div>
		</div>
	</div>
</div>

<div class="col-sm-3 col-sm-push-9">
	<div class="sidewrap sidewrap_csfilters">
		<div class="">
			<a href="/bi-completed-project-form/" class="bi-to-form-btn button btn btn-warning btn-md ">Add a project here</a>
			<a href="/bi-pre-registration-form/" class="bi-to-form-btn button btn btn-warning btn-md ">Pre-register a project here</a>
		</div>
		<div class="cs_sidebar_wrap">
			<?php echo __( 'Total projects:', 'opsi' ); ?> <span class="cs_counter"><?php echo wp_count_uncached_posts( 'bi-project' )['publish']; ?></span><br />
			<?php echo __( 'Search results:', 'opsi' ); ?> <span class="cs_counter"><?php echo facetwp_display( 'counts' ); ?></span>
		</div>
		<?php
		$bi_project_form_page = get_field( 'bi_project_form_page', 'option' );
		if ( ! empty( $bi_project_form_page ) ) {
			$bi_project_form_page_url = get_permalink( $bi_project_form_page );
			if ( ! empty( $bi_project_form_page_url ) ) {
			?>
			<a class="button btn btn-default btn-block big covid-add-response-button" href="<?php echo $bi_project_form_page_url; ?>"><?php echo __( 'Add a Pre-registration', 'opsi' ); ?></a>
			<?php
			}
		}
		?>

		<h2><?php echo __( 'Filter projects:', 'opsi' ); ?></h2>
		<?php dynamic_sidebar( 'sidebar_bi_project' ); ?>
		<button class="button btn btn-default btn-block big reset-filters-button" onclick="FWP.reset()"><?php echo __( 'Clear All Filters', 'opsi' ); ?></button>
	</div>
</div>

<div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo ($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">

	<div class="facetwp-template">

		<?php echo $title_element; ?>

		<div class="row post_list">

			<?php
			while ( have_posts() ) {
			  the_post();
			  get_template_part( 'content', 'bi-project' );
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
	</div>
</div>

<?php wp_reset_query(); ?>

<?php get_footer(); ?>
