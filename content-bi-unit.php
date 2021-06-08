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
					<a href="<?php echo get_post_type_archive_link( 'bi-unit' ); ?>?_countries=<?php echo $country[0]->slug; ?>" title="<?php echo __( 'All BI unit by:', 'opsi' ); ?> <?php echo $country[0]->name; ?>" class="blacklink" >
						<?php echo $country[0]->name; ?>
					</a>
					<a href="<?php echo get_post_type_archive_link( 'bi-unit' ); ?>?_countries=<?php echo $country[0]->slug; ?>">
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
						// Policy area terms
						if ( !empty( get_field( 'activities_which_of_the_following_policy_areas_has_your_unit_been_involved_in' ) ) ) {

							$terms = get_field( 'activities_which_of_the_following_policy_areas_has_your_unit_been_involved_in' );

							$terms_list = '<div><strong>Policy area </strong><br>';
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

					$team_situated = get_field( 'your_team_where_is_your_team_situated' );
					if ( $team_situated != '' ) { ?>
						<p class="csp">The team is <?php echo $team_situated['label']; ?></p>
					<?php } ?>

					<?php
					$people_number = get_field( 'your_team_how_many_people_including_yourself_apply_behavioral_science_in_your_team' );
					if ( $people_number != '' ) { ?>
						<p class="csp">Number of people applying behavioral science in the team: <?php echo $people_number; ?></p>
					<?php } ?>

					<p class="read_more">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="button btn btn-info btn-sm">
							<?php echo __( 'Read BI Unit', 'opsi' ); ?>
							<i class="fa fa-chevron-right" aria-hidden="true"></i>
						</a>
					</p>
			</div>
		</div>
    </article>
</div>
