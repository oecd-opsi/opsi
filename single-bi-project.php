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
	$embargo_date = $fields['who_is_behind_the_project']['embargo_date']['text'];
  $today_date = date('Ymd');
  $embargo = false;
  if( $embargo_date > $today_date  ) { $embargo == true; }

  $project_statuses = get_the_terms( $postid, 'bi-project-status' );
  $project_status = $project_statuses[0]->name;

	// echo '<pre>'.print_r($fields, true).'</pre>';

?>
	<div class="row">
		<div class="col-md-9">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if (get_field('hide_page_title') !== true) : ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
        <p class="bi-project-status"><?php echo $project_status ?> project</p>
        <?php endif; ?>

				<div class="sliding_panel open">

					<h2 class="active">General Information</h2>
          <div class="panel_content">

            <h3>Project description</h3>
            <div class="csp"><?php echo $fields['who_is_behind_the_project']['project_description']['text']; ?></div>

				  </div>

				</div>

        <div class="sliding_panel">

          <?php if(  $project_status == 'Pre-registration' ): ?>
            <h2 class="active">Analysis Plan</h2>
          <?php else: ?>
            <h2 class="active">Detailed information</h2>
          <?php endif; ?>
          <div class="panel_content" style="display:none;">

            <?php if(  $project_status == 'Completed' ): ?>
              <h3>Final report: Is there a final report presenting the results and conclusions of this project?</h3>
              <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['final_report_is_there_a_final_report_presenting_the_results_and_conclusions_of_this_project']['text']['label']; ?></div>

              <?php if( $fields['hypothesis_outcomes_and_analysis']['final_report_is_there_a_final_report_presenting_the_results_and_conclusions_of_this_project']['text']['label'] == 'Yes' ) : ?>
                <?php if( $fields['hypothesis_outcomes_and_analysis']['final_report_file_wrapper']['text']['please_attach_your_report_here_or_add_a_link_to_the_report']['label'] == 'Attach a file' ) {
                  $final_report_url = $fields['hypothesis_outcomes_and_analysis']['final_report_file_wrapper']['text']['report_file'];
                } else {
                  $final_report_url = $fields['hypothesis_outcomes_and_analysis']['final_report_file_wrapper']['text']['link_to_the_report'];
                }?>
                <h3>Final report</h3>
                <div class="csp"><a href="<?php echo $final_report_url ?>" target="_blank">See the final report</a></div>
              <?php endif; ?>
            <?php endif; ?>

            <?php if( $fields['hypothesis_outcomes_and_analysis']['pre-analysis_plan_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['hypothesis_outcomes_and_analysis']['pre-analysis_plan_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
            <h3>Pre-analysis plan: Is there a pre-analysis plan associated with this registration?</h3>
            <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['pre-analysis_plan_wrapper']['text']['pre-analysis_plan:_is_there_a_pre-analysis_plan_associated_with_this_registration']; ?></div>
              <?php if(
                $fields['hypothesis_outcomes_and_analysis']['pre-analysis_file_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['hypothesis_outcomes_and_analysis']['pre-analysis_file_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
                <?php if ($fields['hypothesis_outcomes_and_analysis']['pre-analysis_file_wrapper']['text']['please_attach_your_pre-analysis_plan']['text'] != '' )  : ?>
                  <div class="csp"><a href="<?php echo $fields['hypothesis_outcomes_and_analysis']['pre-analysis_file_wrapper']['text'][' please_attach_your_pre-analysis_plan']['text'] ?>" target="_blank">See the Pre-analysis plan</a></div>
                <?php endif; ?>
              <?php endif; ?>
            <?php endif; ?>

            <?php if( $project_status == 'Pre-registration'): ?>
              <?php if( $fields['hypothesis_outcomes_and_analysis']['hypothesis_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['hypothesis_outcomes_and_analysis']['hypothesis_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
                <h3>Hypothesis</h3>
                <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['hypothesis_wrapper']['text']['hypothesis']; ?></div>
              <?php endif; ?>


              <?php if( $fields['hypothesis_outcomes_and_analysis']['test_hypothesis_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['hypothesis_outcomes_and_analysis']['test_hypothesis_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
                <h3>How hypothesis will be tested</h3>
                <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['test_hypothesis_wrapper']['text']['how_will_your_hypothesis_be_tested']; ?></div>
              <?php endif; ?>

              <?php if( $fields['hypothesis_outcomes_and_analysis']['dependent_variables_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['hypothesis_outcomes_and_analysis']['dependent_variables_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
                <h3>Dependent variables</h3>
                <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['dependent_variables_wrapper']['text']['dependent_variables']; ?></div>
              <?php endif; ?>

              <?php if( $fields['hypothesis_outcomes_and_analysis']['analyses_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['hypothesis_outcomes_and_analysis']['analyses_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
                <h3>Analyses</h3>
                <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['analyses_wrapper']['text']['analyses']; ?></div>
              <?php endif; ?>

              <?php if( $fields['hypothesis_outcomes_and_analysis']['sample_size_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['hypothesis_outcomes_and_analysis']['sample_size_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
                <h3>Sample Size. How many observations will be collected or what will determine sample size?</h3>
                <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['sample_size_wrapper']['text']['sample_size_how_many_observations_will_be_collected_or_what_will_determine_sample_size']; ?></div>
              <?php endif; ?>

              <?php if( $fields['hypothesis_outcomes_and_analysis']['power_analysis_was_a_power_analysis_conducted_prior_to_data_collection']['text']['label'] != '' ): ?>
                <h3>Power analysis. Was a power analysis conducted prior to data collection?</h3>
                <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['power_analysis_was_a_power_analysis_conducted_prior_to_data_collection']['text'] ?></div>
              <?php endif; ?>

              <?php if( $fields['hypothesis_outcomes_and_analysis']['third_party_implement_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['hypothesis_outcomes_and_analysis']['third_party_implement_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
                <h3>Does a third party implement the intervention or is this a collaboration with another team?</h3>
                <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['third_party_implement_wrapper']['text']['does_a_third_party_implement_the_intervention_or_is_this_a_collaboration_with_another_team']; ?></div>
              <?php endif; ?>

              <?php if(  $fields['hypothesis_outcomes_and_analysis']['data_exclusion']['text'] != '' ): ?>
                <h3>Data Exclusion</h3>
                <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['data_exclusion']['text'] ?></div>
              <?php endif; ?>

              <?php if( $fields['hypothesis_outcomes_and_analysis']['treatment_of_missing_data']['text'] != '' ): ?>
                <h3>Treatment of Missing Data</h3>
                <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['treatment_of_missing_data']['text'] ?></div>
              <?php endif; ?>

              <?php if( $fields['hypothesis_outcomes_and_analysis']['analysis_codescript']['text'] != '' ): ?>
                <h3>Analysis Code/Script</h3>
                <div class="csp"><a href="<?php echo $fields['hypothesis_outcomes_and_analysis']['analysis_codescript']['text'] ?>" target="_blank"><?php echo $fields['hypothesis_outcomes_and_analysis']['analysis_codescript']['text'] ?></a></div>
              <?php endif; ?>

              <?php if( $fields['hypothesis_outcomes_and_analysis']['post-commitment_adjustments']['text'] != '' ): ?>
                <h3>Post-Commitment Adjustments</h3>
                <div class="csp"><?php echo $fields['hypothesis_outcomes_and_analysis']['post-commitment_adjustments']['text'] ?></div>
              <?php endif; ?>
            <?php endif; ?>

            <?php if(  $project_status == 'Pre-registration' ): ?>
              <?php if( $fields['hypothesis_outcomes_and_analysis']['external_link']['text'] != '' ): ?>
                <h3>External link</h3>
                <div class="csp"><a href="<?php echo $fields['hypothesis_outcomes_and_analysis']['external_link']['text'] ?>" target="_blank"><?php echo $fields['hypothesis_outcomes_and_analysis']['external_link']['text'] ?></a></div>
              <?php endif; ?>
            <?php endif; ?>

				  </div>

				</div>

        <?php if(  $project_status == 'Completed' ): ?>
          <div class="sliding_panel">

  					<h2>Additional information</h2>

  					<!-- <div class="panel_content" <?php //echo ( $oi == 0 ? '' : 'style="display: none;"' ); ?>> -->
            <div class="panel_content" style="display:none;">

              <?php if( $fields['additional_information']['third_party_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['additional_information']['third_party_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
                <h3>Does a third party implement the intervention or is this a collaboration with another team?</h3>
                <div class="csp"><?php echo $fields['additional_information']['third_party_wrapper']['text']['does_a_third_party_implement_the_intervention_or_is_this_a_collaboration_with_another_team']; ?></div>
              <?php endif; ?>

              <?php if( $fields['additional_information']['analysis_codescript']['text'] != '' ): ?>
                <h3>Analysis Code/Script</h3>
                <div class="csp"><?php echo $fields['additional_information']['analysis_codescript']['text'] ?></div>
              <?php endif; ?>

              <?php if( $project_status == 'Pre-registration'): ?>
                <?php if( $fields['additional_information']['additional_documentation']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['additional_information']['additional_documentation']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
                  <h3>Additional Documentation</h3>
                  <div class="csp"><?php
                  foreach ($fields['additional_information']['additional_documentation']['text']['files'] as $file) {
                    echo '<p><a href="'.$file['file']['url'].'">'.$file['file']['title'].'</a></p>';
                  }
                  ?></div>
                <?php endif; ?>
              <?php endif; ?>

  				  </div>

  				</div>
        <?php endif; ?>

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
            <p class="bi-project-city"><?php echo $fields['who_is_behind_the_project']['city']['text'] ?></p>
					</div>
					<?php } ?>
				</div>
			</div>

			<div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">

						<h4>Who is behind the project?</h4>

            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Institution: </span>
              <span class="strong"><?php echo get_term( $fields['who_is_behind_the_project']['institution']['text'] )->name ?></span>
            </div>
            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Team: </span>
              <span class="strong"><?php echo get_the_title( $fields['who_is_behind_the_project']['team']['text'] ) ?></span>
            </div>
            <?php if( $fields['who_is_behind_the_project']['authors_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['who_is_behind_the_project']['authors_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
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

						<h4>Methods</h4>

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

            <?php if( $fields['methods']['could_you_self-grade_the_strength_of_the_evidence_generated_by_this_study']['text'] > 0 ): ?>
              <div class="sidebar-generic-wrapper">
                <span class="sidebar_label">Could you self-grade the strength of the evidence generated by this study?</span>
                <span class="strong"><?php echo $fields['methods']['could_you_self-grade_the_strength_of_the_evidence_generated_by_this_study']['text'] ?></span>
              </div>
            <?php endif; ?>

            <?php if(  $project_status == 'Pre-registration' ): ?>
              <div class="sidebar-generic-wrapper">
                <span class="sidebar_label">Data collection: Have any data been collected for this project already?</span>
                <span class="strong"><?php echo $fields['methods']['data_collection:_have_any_data_been_collected_for_this_project_already']['text'] ?></span>
              </div>
            <?php endif; ?>

            <?php if( $fields['methods']['date_of_start_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['methods']['date_of_start_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
              <span class="sidebar_label">Date of start: </span>
              <span class="strong"><?php echo $fields['methods']['date_of_start_wrapper']['text']['date_of_start']; ?></span>
            <?php endif; ?>

					</div>
				</div>
			</div>

      <div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">

						<h4>What is the project about?</h4>

            <div class="sidebar-generic-wrapper">
              <span class="sidebar_label">Policy Area: </span>
              <?php
              $policy_list = '';
              foreach ( $fields['who_is_behind_the_project']['policy_area']['text'] as $policy_item ) {
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
              foreach ( $fields['who_is_behind_the_project']['topic']['text'] as $topic_item ) {
                $topic_list .= $topic_item->name . ', ';
              }
              $topic_list = rtrim( $topic_list, ', ' );
              ?>
              <span class="strong"><?php echo $topic_list ?></span>
            </div>

            <?php if( $fields['who_is_behind_the_project']['behavioural_tool_wrapper']['text']['include_this_in_the_public_version'] == 'Yes' || ( $fields['who_is_behind_the_project']['behavioural_tool_wrapper']['text']['include_this_in_the_public_version'] == 'Hide until embargo date' && !$embargo ) ) : ?>
              <div class="sidebar-generic-wrapper">
                <span class="sidebar_label">Behavioural Tool: </span>
                <?php
                $btool_list = '';
                foreach ( $fields['who_is_behind_the_project']['behavioural_tool_wrapper']['text']['behavioural_tool'] as $btool_item ) {
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
