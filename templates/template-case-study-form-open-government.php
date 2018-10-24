<?php
/*
Template Name: Open Government Case study form
Template Post Type: page
*/

/*
** ACF filter to customize Open Government form
*/
// give value to case type taxonomy field
function bs_case_study_form_taxonomy_field($field) {
	$field['value'] = 891;
  return $field;
}
add_filter('acf/prepare_field/key=field_5bc60b5d239b4', 'bs_case_study_form_taxonomy_field', 20);

// Hide fields
function bs_case_study_open_gov_hide_fields($field) {

  $fields_hidden = array(
		'public_employee_involved',
		'public_official_involved',
		'cs_user_name',
		'cs_user_organization',
		'cs_user_email',
		'tel',
	);
  if( in_array( $field['name'], $fields_hidden ) ) return false;

	return $field;
}
add_filter('acf/load_field', 'bs_case_study_open_gov_hide_fields', 20);

// Innovation owner group field
function bs_case_study_form_innovation_owner($field) {
	return;
}
add_filter( 'acf/load_field/key=field_5ae7ab3b5dd80', 'bs_case_study_form_innovation_owner', 20 );

// Level of government field
function bs_case_study_form_level_of_government($field) {
	$field['conditional_logic'] = '[
			[
					{
							"field": "field_5bc9fb9ae7390",
							"operator": "==",
							"value": "government"
					}
			]
	]';
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae73ab6c5ac5', 'bs_case_study_form_level_of_government', 20 );

// Lesson learned field
function bs_case_study_form_lesson_learned($field) {
	$field['instructions'] = __('What lessons from your experience would you like to share with others like you? Where there any pitfalls to avoid (maximum 4000 characters)?', 'opsi');
	$field['maxlength'] = 4000;
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae78006a1e79', 'bs_case_study_form_lesson_learned', 20 );

// Short and Simple Explanation field
function bs_case_study_form_short_and_simple_explanation($field) {
	$field['instructions'] = __('This explanation should describe your innovation in <strong>three short and simple sentences</strong>. The explanation will be the first description people will see searching for innovations in the case study repository.  They will use this description to decide if they would like to read more about your innovation.

The explanation should:
<ul class="dotted">
<li>use clear and succinct language</li>
<li>compel the reader to continue reading about the innovation</li>
<li>be able to be understood by someone who knows nothing about the sector or the innovation</li>
<li>set the context for other professionals who are reading the case study</li>
<li>use simple, not sector-specific terminology (no idioms, slang, or domain-specific "buzz" words)</li>
</ul>

The explanation should describe:
<ul class="dotted">
<li>why the innovation was developed or the problem/opportunity being addressed</li>
<li>what the innovation is and who it benefitted</li>
<li>why it is innovative</li>
</ul>
<p><a data-fancybox data-src="#short_explanation_sample_open_gov" data-options=\'{"touch" : false}\' href="javascript:;">Click here for examples of a Short Explanation</a></p>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5b35686af240c', 'bs_case_study_form_short_and_simple_explanation', 20 );

// Innovation Overview field
function bs_case_study_form_executive_summary($field) {
	$field['instructions'] = __('The Innovation Overview is an overview of the project and outcomes. If you\'ve already written a report about your project, this could also be your abstract or one-pager. This should summarize all of the information for the innovation at a high level. You will have the opportunity to elaborate on some of the details later in the submission. In approximately 3-4 paragraphs (maximum 5,000 characters), please tell us:

<ul class="dotted">
<li>What problem the innovation solves or what opportunity was taken advantage of</li>
<li>What the innovation is</li>
<li>Objectives or goals of the innovation</li>
<li>Who benefited from the innovation</li>
<li>How is the innovation envisioned for the future? For example, how will it be institutionalised in its current context? How will it scale even bigger?</li>
</ul>

If applicable, you may also wish to include:

<ul class="dotted">
<li>How a course of action was determined</li>
<li>Methods or tools used to implement the project</li>
<li>A description of another innovation you were inspired by</li>
</ul>
<p><a data-fancybox data-src="#executive_summary_exampleopen_gov" data-options=\'{"touch" : false}\' href="javascript:;">Click here for example of an Innovation Overview</a></p>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae778f9224b4', 'bs_case_study_form_executive_summary', 20 );

// What Makes Your Project Innovative? field
function bs_case_study_form_what_makes_your_project_innovative($field) {
	$field['instructions'] = __('We\'re eager to hear about how your project is innovative.

In approximately 1-2 paragraphs (maximum 1,000 characters), tell us how your innovation is:

<ul class="dotted">
<li>Different, unique or more innovative than the what\'s been tried previously in your organisation, other organisations, or other countries</li>
</ul>

<p><a data-fancybox data-src="#what_makes_your_project_innovative_sample_open_gov" data-options=\'{"touch" : false}\' href="javascript:;">Click here for example of a "What Makes Your Project Innovative?"</a></p>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5b34fd004fc4f', 'bs_case_study_form_what_makes_your_project_innovative', 20 );

// Innovation Status Text field
function bs_case_study_form_status_description($field) {
	$field['instructions'] = __('<p><a data-fancybox data-src="#innovation_status_sample_open_gov" data-options=\'{"touch" : false}\' href="javascript:;">Click here for example of a Status Description</a></p>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae77cd92bcf8', 'bs_case_study_form_status_description', 20 );

// Collaborations & Partnerships field
function bs_case_study_form_collaboration_partnerships($field) {
	$field['instructions'] = __('What Collaborators or Partners were involved in the innovation process? Describe what each brought to the table and why it was important to the innovation (maximum 500 characters).

These may include:

<ul class="dotted">
<li>Citizens</li>
<li>Government officials</li>
<li>Civil society organisations</li>
<li>Companies</li>
</ul>
<p><a data-fancybox data-src="#collaboration_and_partnerships_description_sample_open_gov" data-options=\'{"touch" : false}\' href="javascript:;">Click here for example for Collaboration & Partnerships</a></p>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae77ec1e2a8f', 'bs_case_study_form_collaboration_partnerships', 20 );

// Users, Stakeholders & Beneficiaries field
function bs_case_study_form_users_stakeholders_beneficiaries($field) {
	$field['instructions'] = __('Describe each of the users, stakeholder and/or beneficiaries for the innovation and how each group was affected (maximum 500 characters).

These may also include:

<ul class="dotted">
<li>Citizens</li>
<li>Government officials</li>
<li>Civil society organisations</li>
<li>Companies</li>
</ul>
<p><a data-fancybox data-src="#users_stakeholders_and_beneficiaries_sample_open_gov" data-options=\'{"touch" : false}\' href="javascript:;">Click here for example for Users, Stakeholders and Beneficiaries</a></p>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae77f20e2a90', 'bs_case_study_form_users_stakeholders_beneficiaries', 20 );

// Results, Outcomes & Impacts field
function bs_case_study_form_results_impact($field) {
	$field['instructions'] = __('Describe the results, outcomes and Impacts of the innovation (maximum 1,000 characters).

<ul class="dotted">
<li>What results and impacts have been observed from the innovation so far?</li>
<li>How have the results and impacts been measured (e.g., methodologies used)?</li>
<li>What results and impacts do you expect in the future?</li>
<li>To the extent possible, please indicate the tangible or numeric results.</li>
</ul>
<p><a data-fancybox data-src="#results_outcomes_and_impact_sample_open_gov" data-options=\'{"touch" : false}\' href="javascript:;">Click here for example Results, Outcomes & Impact</a></p>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae77e7fe2a8e', 'bs_case_study_form_results_impact', 20 );

// Challenges and Failures field
function bs_case_study_form_challenges($field) {
	$field['label'] = __('Challenges', 'opsi');
	$field['instructions'] = __('Describe what challenges have been faces, and potentially what failures have occurred (maximum 1,000 characters).

<ul class="dotted">
<li>What challenges have been encountered?</li>
<li>What failures have been encountered along the way (e.g., structural failures or significant setbacks)?</li>
<li>And how, if at all, have those challenges and/or failures been responded to?</li>
</ul>
<p><a data-fancybox data-src="#challenges_sample_open_gov" data-options=\'{"touch" : false}\' href="javascript:;">Click here for example of Challenges</a></p>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae77fdda1e78', 'bs_case_study_form_challenges', 20 );

// Conditions for Success field
function bs_case_study_form_conditions_to_success($field) {
	$field['instructions'] = __('What conditions do you think are necessary for the success of an innovation such as this (maximum 1,000 characters)?

Conditions for success may include:

<ul class="dotted">
<li>Supporting infrastructure and services</li>
<li>Policy and rules</li>
<li>Leadership and guidance</li>
<li>Human and financial resources</li>
<li>Personal values and motivation</li>
</ul>
<p><a data-fancybox data-src="#conditions_for_success_sample_open_gov" data-options=\'{"touch" : false}\' href="javascript:;">Click here for example of Conditions for Success</a></p>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae78030a1e7a', 'bs_case_study_form_conditions_to_success', 20 );

// Replication field
function bs_case_study_form_potential_to_be_replicated($field) {
	$field['instructions'] = __('Has the innovation been replicated to address similar problems? If so, how? In your opinion, what is the potential for the innovation to be further replicated in the future? (maximum 1,000 characters)

You may wish to discuss how the innovation has already been used by others, as well as how you believe your innovation could be used by others in the future. These others may be in:

<ul class="dotted">
<li>Other organisations</li>
<li>Within your organisation</li>
<li>Larger or smaller agencies, organisations or governments</li>
</ul>
<p><a data-fancybox data-src="#replication_sample_open_gov" data-options=\'{"touch" : false}\' href="javascript:;">Click here for example of Replication</a></p>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae78083a1e7b', 'bs_case_study_form_potential_to_be_replicated', 20 );

// Save and Submit placeholder field
function bs_case_study_form_save_submit_placeholder($field) {
	$field['message'] = __('<div class="col-md-12 layout_hero_block "><div class="hb_inner text-left">
	Using the “save” option will save the data you have entered and allow you to return to data entry immediately or at a later time. You can click on previously completed sections in the sidebar to navigate back to them in order if you wish to revise your entry. If you have any problems, please contact us at <a href="mailto:opengov@oecd.org" title="contact OPSI">opengov@oecd.org</a> (include a screenshot if possible).

	<div class="text-center inlinep removebr formbuttons">
	<a class="button btn btn-default big goback" title="Back" href="#step-8"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a><a class="button btn btn-info big saveform" title="Save" href="#step-9">Save <i class="fa fa-floppy-o" aria-hidden="true"></i></a><a class="button btn btn-default big submitform" id="submitcasestudy" title="Submit" href="#step-6">Submit <i class="fa fa-check-square-o" aria-hidden="true"></i></a> <span class="acf-spinner"></span>
	</div>
	</div>
	</div>', 'opsi');
	return $field;
}
add_filter( 'acf/load_field/key=field_5b060ee64a0cf', 'bs_case_study_form_save_submit_placeholder', 20 );
add_filter( 'acf/load_field/key=field_5b0622244034e', 'bs_case_study_form_save_submit_placeholder', 20 );
add_filter( 'acf/load_field/key=field_5b0622474034f', 'bs_case_study_form_save_submit_placeholder', 20 );
add_filter( 'acf/load_field/key=field_5b06226840350', 'bs_case_study_form_save_submit_placeholder', 20 );
add_filter( 'acf/load_field/key=field_5b3acfe818042', 'bs_case_study_form_save_submit_placeholder', 20 );
add_filter( 'acf/load_field/key=field_5b06228240351', 'bs_case_study_form_save_submit_placeholder', 20 );
add_filter( 'acf/load_field/key=field_5b356c93dbd0d', 'bs_case_study_form_save_submit_placeholder', 20 );
add_filter( 'acf/load_field/key=field_5b356c93dbd0d', 'bs_case_study_form_save_submit_placeholder', 20 );
add_filter( 'acf/load_field/key=field_5b06229e40352', 'bs_case_study_form_save_submit_placeholder', 20 );

// Innovation status questions fields
function bs_case_study_form_innovation_status_fields($field) {
	return;
}
add_filter( 'acf/load_field/key=field_5ae77bca2bcf7', 'bs_case_study_form_innovation_status_fields', 20 );
add_filter( 'acf/load_field/key=field_5ae77cd92bcf8', 'bs_case_study_form_innovation_status_fields', 20 );

// Innovation tags field
function bs_case_study_form_innovation_tag( $args, $field, $post_id ) {

  // modify args
  $args['meta_query'] = array(
		'relation'		=> 'AND',
		array(
			'key'			=> 'belonging_case_study',
			'value'			=> 'open_gov',
			'compare'		=> '=='
		)
	);

  // return
  return $args;
}
add_filter('acf/fields/taxonomy/query/key=field_5ae779ff224b6', 'bs_case_study_form_innovation_tag', 20, 3);


	acf_form_head();
	get_header();

	?>
	<div class="open-government-branding-container row">
		<div class="col-sm-12">
			<?php dynamic_sidebar('sidebar_case_study_open_gov_left') ?>
		</div>
	</div>
	<?php

    global $post, $bp;

	if ( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) > 0 && !can_edit_acf_form( intval( $_GET['edit'] ) ) ) {
		?>
		<div class="col-sm-12">
			<div class="alert alert-warning text-center">
				<h3><?php echo __( 'Sorry, you cannot edit a case study that was submitted by someone else or a case study that has already been published. If you need to make changes to a published case study, please contact the OPSI team at', 'opsi' ); ?> <a href="mailto:opsi@oecd.org">opsi@oecd.org</a></h3>
			</div>

			<br />
			<a href="<?php echo $bp->loggedin_user->domain . 'innovations/'; ?>" title="<?php echo __( 'Back', 'opsi' ); ?>" class="button btn btn-default flipicon">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>  <?php echo __( 'Back', 'opsi' ); ?>
			</a>

		</div>
		<?php
		get_footer();
		return;
	}


	if ( isset( $_GET['delete'] ) && intval( $_GET['delete'] ) > 0 ) {

		$can_delete_cs = can_delete_cs( intval( $_GET['delete'] ) );

		if ( !can_delete_cs( intval( $_GET['delete'] ) ) ) {
			?>
			<div class="col-sm-12">
				<div class="alert alert-warning text-center">
					<h3><strong>Error!</strong> <?php echo __( 'You can not delete this case study.', 'opsi' ); ?></h3>
				</div>
			</div>
			<?php
			get_footer();
			return;
		} else {
			if ( isset( $_GET['confirm'] ) && intval( $_GET['confirm'] ) == 1 ) {

				if ( $can_delete_cs == 'delete' ) {
					wp_delete_post( intval( $_GET['delete'] ) );
				}

				if ( $can_delete_cs == 'request' ) {
					 wp_update_post( array( 'ID' => intval( $_GET['delete'] ), 'post_status' => 'pending_deletion' ) );
				}
				wp_safe_redirect( get_bloginfo('url') . '/members/'. $current_user->user_login . '/profile/');
				exit();

			} else {
			?>
				<div class="col-sm-12">
					<div class="alert alert-warning text-center">
						<h3><a href="<?php echo get_permalink( $post->ID ); ?>?delete=<?php echo intval( $_GET['delete'] ); ?>&confirm=1"><?php echo __( 'Please confirm deleting this case study by clicking here.', 'opsi' ); ?></a></h3>
					</div>
				</div>
			<?php
			}
		}
		get_footer();
		return;
	}

    $has_sidebar = 0;
    $layout = get_post_meta($post->ID, 'layout', true);
    if($layout != 'fullpage' && is_active_sidebar( 'sidebar' )) {
      $has_sidebar = 3;
    }

  ?>


	<div class="col-sm-3 dont-col-sm-push--9">
		<ul id="acf_steps">
		</ul>

		<?php if ( is_active_sidebar( 'casestudyformsidebar' )) { dynamic_sidebar( 'casestudyformsidebar' ); } ?>
	</div>
    <div class="col-sm-9 dont-col-sm-pull--3">

  <?php while ( have_posts() ) : the_post(); $postid = get_the_ID(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div id="formtop"></div>
			<div id="case_form" class="stepform">
            <?php

				$group = get_page_by_title('Case Studies', OBJECT, 'acf-field-group');

				$form_params = array(
					'field_groups' 	=> array( $group->ID ),
					'new_post'		=> array(
						'post_type'		=> 'case',
						'post_status'	=> 'draft',
						'post_author'	=> get_current_user_id(),
						'post_content' => true,
						'post_title' => true,
					),
					'submit_value'		=> __( 'Create a new case study', 'opsi' ),
					'post_id'			=> 'new_post',
					'form' 				=> true,
					'updated_message' 	=> '<span class="alert alert-success updatedalert" role="alert">'. __("Innovation submission saved on", 'acf') .' '. date_i18n( get_option('date_format') .' '.get_option('time_format') ) .'</span>',
					//'return' => '',
					'html_before_fields' => '<input type="hidden" id="csf_action" name="csf_action" value="" style="display:none;"><input type="hidden" id="form_step_field" name="form_step" value="0" style="display:none;">',
				);

				if ( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) > 0 ) {

					$form_params['post_id'] 		= $_GET['edit'];
					$form_params['new_post'] 		= false;
					$form_params['submit_value'] 	= __( 'Save your case study', 'opsi' );


				}

				acf_form( $form_params );


			?>
			</div>
          </article>
      <?php // comments_template(); ?>
      <?php endwhile; ?>

	  <?php echo get_options_acf_fields_by_group_key(); ?>

    </div>

  <?php wp_reset_query(); ?>


<?php get_footer(); ?>
