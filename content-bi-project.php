<?php
	if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly

	$country = wp_get_post_terms( get_the_ID(), 'country' );
    $iso = get_field( 'iso_code', $country[0] );

  ?>
<div class="col-md-12 case_col">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="row">
			<div class="col-md-9 col-sm-8">
				<h2 class="article-title h4"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			</div>
			<div class="col-md-3 col-sm-4">
				<div class="pull-right country_flag">
					<a href="<?php echo get_post_type_archive_link( 'bi-project' ); ?>?_countries=<?php echo $country[0]->slug; ?>" title="<?php echo __( 'All BI project by:', 'opsi' ); ?> <?php echo $country[0]->name; ?>" class="blacklink" >
						<?php echo $country[0]->name; ?>
					</a>
					<a href="<?php echo get_post_type_archive_link( 'bi-project' ); ?>?_countries=<?php echo $country[0]->slug; ?>">
						<img src="<?php echo get_stylesheet_directory_uri().'/images/flags/'.$iso.'.png'; ?>" width="96" height="96" alt="<?php echo $country[0]->name; ?>" class="cs_flag" />
					</a>
				</div>
			</div>
		</div>

		<hr class="divline">

		<div class="row">
			<div class="col-md-3 col-sm-4">
				<div class="post_tags_wrap">
					<div class="post_tags">

						<?php
						$project_statuses = get_the_terms( $postid, 'bi-project-status' );
					  $project_status = $project_statuses[0]->name;
						 ?>
						<p class="bi-project-status"><?php echo $project_status ?> project</p>

						<div>
							<strong>Unit </strong><br>
							<?php
							$unit = get_field( 'who_is_behind_the_project_unit', get_the_ID() );
							echo $unit->post_title;
							 ?>
						</div>

						<div>
							<strong>Institution </strong><br>
							<?php
							$institutions = get_the_terms( $unit->ID, 'bi-institution' );
							echo $institutions[0]->name;
							 ?>
						</div>

						<?php
						// Methodology terms
						if ( !empty( get_field( 'methods_methodology' ) ) ) {

							$terms = get_field( 'methods_methodology' );

							$terms_list = '<div><strong>Methodology </strong><br>';
              foreach ( $terms as $term ) {
                $terms_list .= $term->name . ', ';
              }
              $terms_list = rtrim( $terms_list, ', ' );

							echo $terms_list . '</div>';
						}
						// Policy area terms
						if ( !empty( get_field( 'keywords_policy_area' ) ) ) {

							$terms = get_field( 'keywords_policy_area' );

							$terms_list = '<div><strong>Policy area </strong><br>';
              foreach ( $terms as $term ) {
                $terms_list .= $term->name . ', ';
              }
              $terms_list = rtrim( $terms_list, ', ' );

							echo $terms_list . '</div>';
						}
						// Topic terms
						if ( !empty( get_field( 'keywords_topic' ) ) ) {

							$terms = get_field( 'keywords_topic' );

							$terms_list = '<div><strong>Topic </strong><br>';
              foreach ( $terms as $term ) {
                $terms_list .= $term->name . ', ';
              }
              $terms_list = rtrim( $terms_list, ', ' );

							echo $terms_list . '</div>';
						}
						// Topic terms
						if ( get_field('keywords_behavioural_tool_wrapper_include_this_in_the_public_version') == 1 && !empty( get_field( 'keywords_behavioural_tool_wrapper_behavioural_tool' ) ) ) {

							$terms = get_field( 'keywords_behavioural_tool_wrapper_behavioural_tool' );

							$terms_list = '<div><strong>Behavioural Tool </strong><br>';
              foreach ( $terms as $term ) {
                $terms_list .= $term->name . ', ';
              }
              $terms_list = rtrim( $terms_list, ', ' );

							echo $terms_list . '</div>';
						}
						?>
					</div>
				</div>
			</div>
			<div class="col-md-9 col-sm-8">
				<?php
					$project_description = get_field( 'who_is_behind_the_project_project_description' );
					if ( $project_description != '' ) { ?>
						<p class="csp"><?php echo $project_description; ?></p>
					<?php } ?>
					<p class="read_more">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="button btn btn-default btn-sm">
							<?php echo __( 'Read BI project', 'opsi' ); ?>
							<i class="fa fa-chevron-right" aria-hidden="true"></i>
						</a>
					</p>
			</div>
		</div>
    </article>
</div>
