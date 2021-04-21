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

	$fields = get_all_acf_fields_by_group_key( 'group_607ea4d00a9ed', false );
	// $texts = get_textarea_acf_fields_by_group_key( 'group_607ea4d00a9ed', true );

	// echo '<pre>'.print_r($fields, true).'</pre>';

?>
	<div class="row">
		<div class="col-md-9">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if (get_field('hide_page_title') !== true) : ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
        <?php endif; ?>

				<?php // echo $fields['hypothesis_outcomes_and_analysis']['project_description']['text']; ?>

				<div class="sliding_panel open">

					<h2 class="active">What will the project test and how? – Hypothesis, outcomes and analysis</h2>
          <div class="panel_content">

            <h3>Project description</h3>
            <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['project_description']['text']; ?></div>

            <?php if( $fields['hypothesis_outcomes_and_analysis']['dependent_variables_wrapper']['text']['include_this_in_the_public_version'] == 1 ) : ?>
            <h3>Dependent variables</h3>
            <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['dependent_variables_wrapper']['text']['dependent_variables']; ?></div>
            <?php endif; ?>

            <?php if( $fields['hypothesis_outcomes_and_analysis']['hypothesis_wrapper']['text']['include_this_in_the_public_version'] == 1 ) : ?>
            <h3>Hypothesis</h3>
            <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['hypothesis_wrapper']['text']['hypothesis']; ?></div>
            <?php endif; ?>

            <?php if( $fields['hypothesis_outcomes_and_analysis']['test_hypothesis_wrapper']['text']['include_this_in_the_public_version'] == 1 ) : ?>
            <h3>How hypothesis will be tested</h3>
            <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['test_hypothesis_wrapper']['text']['how_will_your_hypothesis_be_tested']; ?></div>
            <?php endif; ?>

            <?php if( $fields['hypothesis_outcomes_and_analysis']['analyses_wrapper']['text']['include_this_in_the_public_version'] == 1 ) : ?>
            <h3>Analyses</h3>
            <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['analyses_wrapper']['text']['analyses']; ?></div>
            <?php endif; ?>

				  </div>

				</div>

        <div class="sliding_panel">

					<h2>When and how will data be collected?</h2>

					<!-- <div class="panel_content" <?php //echo ( $oi == 0 ? '' : 'style="display: none;"' ); ?>> -->
          <div class="panel_content" style="display:none;">

            <h3>Data collection: Have any data been collected for this project already?</h3>
            <div class="csp"><?php echo $fields['data_collection']['data_collection_have_any_data_been_collected_for_this_project_already']['text']['label'] ?></div>

            <?php if( $fields['data_collection']['date_of_start_wrapper']['text']['include_this_in_the_public_version'] == 1 ) : ?>
            <h3>Date of start</h3>
            <div class="csp"><?php echo $fields['data_collection']['date_of_start_wrapper']['text']['date_of_start']; ?></div>
            <?php endif; ?>

            <?php if( $fields['data_collection']['pre-analysis_plan_wrapper']['text']['include_this_in_the_public_version'] == 1 ) : ?>
            <h3>Pre-analysis plan: Is there a pre-analysis plan associated with this registration?</h3>
            <div class="csp"><?php echo $fields['data_collection']['pre-analysis_plan_wrapper']['text']['pre-analysis_plan']; ?></div>
            <?php endif; ?>

            <?php if( $fields['data_collection']['sample_size_wrapper']['text']['include_this_in_the_public_version'] == 1 ) : ?>
            <h3>Sample Size. How many observations will be collected or what will determine sample size?</h3>
            <div class="csp"><?php echo $fields['data_collection']['sample_size_wrapper']['text']['sample_size_how_many_observations_will_be_collected_or_what_will_determine_sample_size']; ?></div>
            <?php endif; ?>

            <h3>Power analysis. Was a power analysis conducted prior to data collection?</h3>
            <div class="csp"><?php echo $fields['data_collection']['power_analysis_was_a_power_analysis_conducted_prior_to_data_collection']['text']['label'] ?></div>

            <?php if( $fields['data_collection']['third_party_implement_wrapper']['text']['include_this_in_the_public_version'] == 1 ) : ?>
            <h3>Does a third party implement the intervention or is this a collaboration with another team?</h3>
            <div class="csp"><?php echo $fields['data_collection']['third_party_implement_wrapper']['text']['does_a_third_party_implement_the_intervention_or_is_this_a_collaboration_with_another_team']; ?></div>
            <?php endif; ?>

				  </div>

				</div>

        <div class="sliding_panel">

					<h2>Is there some additional information you can include? - Additional information and links</h2>

					<!-- <div class="panel_content" <?php //echo ( $oi == 0 ? '' : 'style="display: none;"' ); ?>> -->
          <div class="panel_content" style="display:none;">

            <h3>Data Exclusion</h3>
            <div class="csp"><?php echo $fields['additional_information_and_links']['data_exclusion']['text'] ?></div>

            <h3>Treatment of Missing Data</h3>
            <div class="csp"><?php echo $fields['additional_information_and_links']['treatment_of_missing_data']['text'] ?></div>

            <h3>Analysis Code/Script</h3>
            <div class="csp"><?php echo $fields['additional_information_and_links']['analysis_codescript']['text'] ?></div>

            <h3>Post-Commitment Adjustments</h3>
            <div class="csp"><?php echo $fields['additional_information_and_links']['post-commitment_adjustments']['text'] ?></div>

            <h3>External link</h3>
            <div class="csp"><?php echo $fields['additional_information_and_links']['external_link']['text'] ?></div>

            <?php if( $fields['additional_information_and_links']['additional_documentation']['text']['include_this_in_the_public_version'] == 1 ) : ?>
              <h3>Additional Documentation</h3>
              <div class="csp"><?php
              foreach ($fields['additional_information_and_links']['additional_documentation']['text']['files'] as $file) {
                echo '<p><a href="'.$file['file']['url'].'">'.$file['file']['title'].'</a></p>';
              }
              ?></div>
            <?php endif; ?>

				  </div>

				</div>

        <div class="sliding_panel">

					<h2>Collaboration and reviewing</h2>

					<!-- <div class="panel_content" <?php //echo ( $oi == 0 ? '' : 'style="display: none;"' ); ?>> -->
          <div class="panel_content" style="display:none;">

            <h3>Would you be open for collaboration on this project with another team?</h3>
            <div class="csp"><?php echo $fields['collaboration_and_reviewing']['would_you_be_open_for_collaboration_on_this_project_with_another_team']['text'] ?></div>

            <h3>Would you like this project to be reviewed by an expert or receive feedback from a member of the BI community?</h3>
            <div class="csp"><?php echo $fields['collaboration_and_reviewing']['would_you_like_this_project_to_be_reviewed_by_an_expert_or_receive_feedback_from_a_member_of_the_bi_community']['text'] ?></div>

				  </div>

				</div>

      </article>
      <?php //comments_template(); ?>


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
							<?php echo $country[0]->name; ?>
						</h4></a>
					</div>
					<?php } ?>
				</div>
			</div>

			<div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">

						<h4>Who is behind the project?</h4>

            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Team: </span>
              <span class="strong"><?php echo $fields['who_is_behind_the_project']['team']['text'] ?></span>
            </div>
            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Institution: </span>
              <span class="strong"><?php echo $fields['who_is_behind_the_project']['institution']['text'] ?></span>
            </div>
            <?php if( $fields['who_is_behind_the_project']['authors_wrapper']['text']['include_this_in_the_public_version'] == 1 ) : ?>
            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Authors: </span>
              <span class="strong"><?php echo $fields['who_is_behind_the_project']['authors_wrapper']['text']['authors'] ?></span>
            </div>
            <?php endif; ?>

					</div>
				</div>
			</div>

      <div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">

						<h4>How are the hypothesis tested?- Methods</h4>

            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Methodology: </span>
              <?php
              $method_list = '';
              foreach ( $fields['methods']['methodology']['text'] as $method_item ) {
                $method_list .= $method_item->name . ', ';
              }
              $method_list = rtrim( $method_list, ', ' );
              ?>
              <span class="strong"><?php echo $method_list ?></span>
            </div>
            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Could you self-grade the strength of the evidence generated by this study?</span>
              <span class="strong"><?php echo $fields['methods']['could_you_self-grade_the_strength_of_the_evidence_generated_by_this_study']['text'] ?></span>
            </div>

					</div>
				</div>
			</div>

      <div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">

						<h4>What is the project about? – Keywords (Policy topics and Behavioural tools)</h4>

            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Policy Area: </span>
              <?php
              $policy_list = '';
              foreach ( $fields['keywords']['policy_area']['text'] as $policy_item ) {
                $policy_list .= $policy_item->name . ', ';
              }
              $policy_list = rtrim( $policy_list, ', ' );
              ?>
              <span class="strong"><?php echo $policy_list ?></span>
            </div>
            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Topic: </span>
              <?php
              $topic_list = '';
              foreach ( $fields['keywords']['topic']['text'] as $topic_item ) {
                $topic_list .= $topic_item->name . ', ';
              }
              $topic_list = rtrim( $topic_list, ', ' );
              ?>
              <span class="strong"><?php echo $topic_list ?></span>
            </div>
            <?php if( $fields['keywords']['behavioural_tool_wrapper']['text']['include_this_in_the_public_version'] == 1 ) : ?>
            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Behavioural Tool: </span>
              <?php
              $btool_list = '';
              foreach ( $fields['keywords']['behavioural_tool_wrapper']['text']['behavioural_tool'] as $btool_item ) {
                $btool_list .= $btool_item->name . ', ';
              }
              $btool_list = rtrim( $btool_list, ', ' );
              ?>
              <span class="strong"><?php echo $btool_list ?></span>
            </div>
            <?php endif; ?>

					</div>
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
