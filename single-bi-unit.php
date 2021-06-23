<?php get_header();

  global $post;

  $has_sidebar = 0;
	$layout = 'fullpage';

?>



<div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo ($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">

<?php while ( have_posts() ) : the_post(); $postid = get_the_ID();

	$country = wp_get_post_terms( $postid, 'country' );
	$iso = false;
	if ( $country ) {
		$iso = get_field( 'iso_code', $country[0] );
	}

	$fields = get_all_acf_fields_by_group_key( 'group_60ad5fddd151e', false );

	// echo '<pre>'.print_r($fields, true).'</pre>';

?>
	<div class="row">
		<div class="col-md-9">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if (get_field('hide_page_title') !== true) : ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
        <?php endif; ?>

				<div class="sliding_panel open">

					<h2 class="active">General Information</h2>
          <div class="panel_content">

            <h3>Organization's name</h3>
            <div class="csp"><?php echo $fields['general_information']['your_organizations_name']['text']->name; ?></div>

            <?php if( $fields['general_information']['your_teamorganizations_website']['text'] != '' ): ?>
              <h3>Organization's website</h3>
              <div class="csp"><a href="<?php echo $fields['general_information']['your_teamorganizations_website']['text']; ?>" target="_blank"><?php echo $fields['general_information']['your_teamorganizations_website']['text']; ?></a></div>
            <?php endif; ?>

            <?php if( $fields['general_information']['your_first_and_last_name']['text'] ): ?>
              <h3>Contact person</h3>
              <div class="csp"><?php echo $fields['general_information']['your_first_and_last_name']['text']; ?><?php if( $fields['general_information']['your_job_title']['text'] != '' ): ?> - <?php echo $fields['general_information']['your_job_title']['text']; ?><?php endif; ?></div>
            <?php endif; ?>

				  </div>

				</div>

        <div class="sliding_panel">

          <h2 class="active">The Team</h2>
          <div class="panel_content" style="display:none;">

            <?php if( $fields['your_team'][' do_you_work_with_governments_on_applying_bi_to_public_policy']['text']['label'] != '' ): ?>
              <div class="csp">Does the team works with government(s) on applying BI to public policy? <?php echo $fields['your_team']['do_you_work_with_governments_on_applying_bi_to_public_policy']['text']['label']; ?></div>
            <?php endif; ?>

            <?php if( $fields['your_team']['are_you_part_of']['text']['label'] != '' ): ?>
              <div class="csp">The team is part of <?php echo $fields['your_team']['where_is_your_team_situated']['text']['label']; ?></div>
            <?php endif; ?>

            <div class="csp"><strong>Number of people that apply behavioral science in the team:</strong> <?php echo $fields['your_team']['how_many_people_including_yourself_apply_behavioral_science_in_your_team']['text']; ?></div>

            <?php if( $fields['your_team']['approximately_when_was_your_team_created_at_your_organization_or_if_it’s_just_you_when_was_your_role_created']['text'] != '' ): ?>
              <div class="csp"><strong>Approximate date of team creation: </strong><?php echo $fields['your_team']['approximately_when_was_your_team_created_at_your_organization_or_if_it’s_just_you_when_was_your_role_created']['text']; ?></div>
            <?php endif; ?>

				  </div>

				</div>

        <div class="sliding_panel">

					<h2>Activities</h2>

					<!-- <div class="panel_content" <?php //echo ( $oi == 0 ? '' : 'style="display: none;"' ); ?>> -->
          <div class="panel_content" style="display:none;">

            <?php
            $activities_list = '';
            foreach ( $fields['activities']['which_of_the_following_activities_has_your_unit_been_involved_in']['text'] as $activity ) {
              $activities_list .= $activity['label'] . ', ';
            }
            $activities_list = rtrim( $activities_list, ', ' );
            ?>
            <h3>Activities in which the unit has been involved</h3>
            <div class="csp"><?php echo $activities_list ?></div>

            <?php
            $policy_list = '';
            foreach ( $fields['activities']['which_of_the_following_policy_areas_has_your_unit_been_involved_in']['text'] as $policy_item ) {
              $policy_list .= $policy_item->name . ', ';
            }
            $policy_list = rtrim( $policy_list, ', ' );
            ?>
            <h3>Policy areas</h3>
            <div class="csp"><?php echo $policy_list ?></div>

            <?php
            $measurement_list = '';
            foreach ( $fields['activities']['how_do_you_measure_if_your_interventions_have_the_desired_effect']['text'] as $measurement_item ) {
              $measurement_list .= $measurement_item['label'] . ', ';
            }
            $measurement_list = rtrim( $measurement_list, ', ' );
            ?>
            <h3>Tools used to measure the effect of intervention</h3>
            <div class="csp"><?php echo $measurement_list ?></div>

            <?php if( $fields['activities']['how_many_experiments_field_experiments_laboratory_experiments_ab_tests_or_multi-armed_bandits_has_your_team_run_in_the_last_twelve_months']['text'] != '' ): ?>
              <h3>Number of experiments in the last twelve months</h3>
              <div class="csp"><?php echo $fields['activities']['how_many_experiments_field_experiments_laboratory_experiments_ab_tests_or_multi-armed_bandits_has_your_team_run_in_the_last_twelve_months']['text'] ?></div>
            <?php endif; ?>

				  </div>

				</div>

      </article>

		</div>

		<div class="col-md-3 case_study_sidebar">

			<div class="cs_sidebar_wrap">
				<div class="row">
					<?php if ( $iso != '' ) { ?>
					<div class="col-xs-4">
						<a href="<?php echo get_post_type_archive_link( 'bi-project' ); ?>?_countries=<?php echo $country[0]->slug; ?>" title="<?php echo __( 'All BI project by:', 'opsi' ); ?> <?php echo $country[0]->name; ?>" class="blacklink" target="_blank" >
							<img src="<?php echo get_stylesheet_directory_uri().'/images/flags/'.$iso.'.png'; ?>" width="96" height="96" alt="<?php echo $country[0]->name; ?>" class="cs_flag" />
						</a>
					</div>
					<?php } ?>
					<?php if ( !empty( $country ) ) { ?>
					<div class="col-xs-8 cs_country_name">
						<h4>
							<a href="<?php echo get_post_type_archive_link( 'bi-project' ); ?>?_countries=<?php echo $country[0]->slug; ?>" title="<?php echo __( 'All BI project by:', 'opsi' ); ?> <?php echo $country[0]->name; ?>" class="blacklink" target="_blank" >
							<?php echo $country[0]->name; ?></a>
            </h4>
            <p class="bi-project-city"><?php echo $fields['general_information']['your_city']['text'] ?></p>
					</div>
					<?php } ?>
				</div>
			</div>

			<div class="cs_post_meta">
				<div class="row">
				  <div class="col-xs-12">
					<span class="strong make-block">
						<?php echo __( 'Date published:', 'opsi' ); ?>
					</span>
					<span class="make-block">
					<?php echo get_the_date(); ?>
					</span>
				  </div>

				</div>
			</div>
			<?php if ( !is_user_logged_in() ) { ?>
			<div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">
						<?php echo do_shortcode('[joinorlogin]'); ?>
					</div>
				</div>
			</div>
			<?php } ?>



		</div>
	</div>
	<?php endwhile; ?>
  </div>

  <?php wp_reset_query(); ?>


<?php get_footer(); ?>
