<?php get_header();

    global $post;

    $has_sidebar = 0;
	$layout = 'fullpage';

?>

<div class="col-md-12">
  <div class="single_img_wrap covid-banner">
    <img src="/wp-content/uploads/2020/04/OPSI-Covid19-Tracker-banner.jpg" class="attachment-blog size-blog wp-post-image" alt="OPSI COVID-19 Innovative Response Tracker">
  </div>
</div>

    <div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo ($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">

<?php while ( have_posts() ) : the_post(); $postid = get_the_ID();


	$upload_images 	= get_field( 'materials_and_submission_upload_images' );
	$upload_files 	= get_field( 'materials_and_submission_upload_files' );

	$country = wp_get_post_terms( $postid, 'country' );
	$iso = false;
	if ( $country ) {
		$iso = get_field( 'iso_code', $country[0] );
	}

	$fields = get_all_acf_fields_by_group_key( 'group_5e8cae67ed9a2', false );
	$texts = get_textarea_acf_fields_by_group_key( 'group_5e8cae67ed9a2', true );

	// echo '<pre>'.print_r($fields, true).'</pre>';

	$textsloop = $texts;
	// unset( $textsloop['personal_details'] );
	// unset( $textsloop['organisation_details'] );
	// unset( $textsloop['describing_the_innovation'] );
	// unset( $textsloop['questionnaire_feedback_miscellaneous'] );


?>
	<div class="row">
		<div class="col-md-9">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( isset( $upload_images[0]['image'] ) ) { ?>


				<div class="single_img_wrap <?php echo (!isset( $upload_images[0]['image'] ) ? 'noimg' : ''); ?>">
                <?php

					echo '
					<a href="' . $upload_images[0]['image']['sizes']['large'] . '" title="' . the_title_attribute('echo=0') . '" class="featuredimglink fancybox" >';
					echo '<img src="'.$upload_images[0]['image']['sizes']['blog'].'" alt="'.$upload_images[0]['image']['title'].'" width="'. $upload_images[0]['image']['sizes']['blog-width'] .'" height="'. $upload_images[0]['image']['sizes']['blog-height'] .'"" />';
					echo '</a>';

					if ($upload_images[0]['image']['caption'] != '') {
						echo '<p>'. $upload_images[0]['image']['caption'] .'</p>';
					}
					echo ( get_field('hide_social_sharing') === true ? '' : wpfai_social() );
                ?>
				</div>
				<?php } ?>

				<?php
					if ( !isset( $upload_images[0]['image'] ) ) { ?>

						<div class="social_sharing_wrap pull-right">
							<?php echo ( get_field('hide_social_sharing') === true ? '' : wpfai_social() ); ?>
						</div>

					<?php
					}
				?>
				<?php if (get_field('hide_page_title') !== true) { ?>
				<h1 class="entry-title <?php echo ( !empty( $badges ) ? 'pull-left' : ''); ?>"><?php the_title(); ?></h1>
				<?php } ?>

				<div class="entry-content"><?php the_content(); ?></div>

        <div class="sliding_panel open">
          <h2 class="active"><?php echo $fields['information_about_the_response']['label'] ?></h2>
          <div class="panel_content">

            <h3><?php echo __( 'What is the innovative response?', 'opsi' ); ?></h3>
            <div class="csp"><?php echo $fields['information_about_the_response']['innovative_response_description']['text'] ?></div>

            <h3><?php echo __( 'What specific issue is this solution intended to address? What is the anticipated or expected impact?', 'opsi' ); ?></h3>
            <div class="csp"><?php echo $fields['information_about_the_response']['specific_issue_addresed']['text'] ?></div>

            <?php if ( $fields['information_about_the_response']['organisations_involved']['text'] != '' ) { ?>
              <h3><?php echo __( 'Organisations/institutions involved', 'opsi' ); ?></h3>
              <div class="csp"><?php echo $fields['information_about_the_response']['organisations_involved']['text'] ?></div>
            <?php } ?>

            <?php if ( $fields['information_about_the_response']['potential_issues']['text'] != '' ) { ?>
              <h3><?php echo __( 'Potential issues', 'opsi' ); ?></h3>
              <div class="csp"><?php echo $fields['information_about_the_response']['potential_issues']['text'] ?></div>
            <?php } ?>

          </div>
        </div>

      </article>
      <?php comments_template(); ?>


		</div>
		<div class="col-md-3 case_study_sidebar">


			<div class="cs_sidebar_wrap">
				<div class="row">
					<?php if ( $iso != '' ) { ?>
					<div class="col-xs-4">
						<a href="<?php echo get_post_type_archive_link( 'covid_response' ); ?>?_countries=<?php echo $country[0]->slug; ?>" title="<?php echo __( 'All innovations by:', 'opsi' ); ?> <?php echo $country[0]->name; ?>" class="blacklink" target="_blank" >
							<img src="<?php echo get_stylesheet_directory_uri().'/images/flags/'.$iso.'.png'; ?>" width="96" height="96" alt="<?php echo $country[0]->name; ?>" class="cs_flag" />
						</a>
					</div>
					<?php } ?>
					<?php if ( !empty( $country ) ) { ?>
					<div class="col-xs-8 cs_country_name">
						<h4>
							<a href="<?php echo get_post_type_archive_link( 'covid_response' ); ?>?_countries=<?php echo $country[0]->slug; ?>" title="<?php echo __( 'All innovations by:', 'opsi' ); ?> <?php echo $country[0]->name; ?>" class="blacklink" target="_blank" >
								<?php echo $country[0]->name; ?>
						</h4></a>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">

					<?php if ( $fields['information_about_the_response']['primary_url']['text'] != '' ) { ?>
						<div class="website_wrap">
							<span class="sidebar_label"><?php echo __( 'Relevant URL(s):', 'opsi' ); ?></span>
							<span class="strong truncate">
								<a href="<?php echo $fields['information_about_the_response']['primary_url']['text']; ?>" title="<?php echo $fields['information_about_the_response']['innovative_response_description']['text']; ?>" target="_blank" >
									<?php echo $fields['information_about_the_response']['primary_url']['text']; ?>
								</a>
							</span>
						</div>
					<?php } ?>
					<?php if ( $fields['information_about_the_response']['other_urls']['text'] != '' ) { ?>
						<div class="website_wrap additional">
							<span class="strong truncate">
								<a href="<?php echo $fields['information_about_the_response']['other_urls']['text']; ?>" title="<?php echo __( 'additional URL', 'opsi' ); ?>" target="_blank" >
									<?php echo $fields['information_about_the_response']['other_urls']['text']; ?>
								</a>
							</span>
						</div>
					<?php } ?>


					<?php if ( !empty( $fields['information_about_the_response']['levels_of_government']['text'] ) ) { ?>
						<div class="log_wrap">
							<span class="sidebar_label"><?php echo __( 'Level(s) of government:', 'opsi' ); ?></span>
              <?php $gov_levels = $fields['information_about_the_response']['levels_of_government']['text']; ?>
              <ul>
              <?php
              foreach ($gov_levels as $value) {
                echo '<li class="strong">' . $value . '</li>';
              }
              ?>
              </ul>
						</div>
					<?php } ?>
        </div>
      </div>
    </div>

          <?php if ( !empty( $fields['information_about_the_response']['general_issues_addressed']['text'] ) ) { ?>
    			<div class="cs_sidebar_wrap">
    				<div class="row">
    					<div class="col-xs-12">
    						<h4 class="status_label"><?php echo __( 'Issues being addressed:', 'opsi' ); ?></h4>
    						<ul class="nospace">
    						<?php foreach( $fields['information_about_the_response']['general_issues_addressed']['text'] as $issue ) { ?>
    								<li><span class="strong"><?php echo $issue; ?></span></li>
    						<?php } ?>
    						</ul>
    					</div>
    				</div>
    			</div>
    			<?php } ?>

          <?php if ( !empty( $fields['information_about_the_response']['email_public']['text'] ) ) { ?>
    			<div class="cs_sidebar_wrap">
    				<div class="row">
    					<div class="col-xs-12">
    						<h4 class="status_label"><?php echo __( 'Response contact:', 'opsi' ); ?></h4>
                <span class="strong truncate">
  								<a href="mailto:<?php echo $fields['information_about_the_response']['email_public']['text']; ?>" target="_blank" >
  									<?php echo $fields['information_about_the_response']['email_public']['text']; ?>
  								</a>
  							</span>
    					</div>
    				</div>
    			</div>
    			<?php } ?>

      <?php
      if( !empty( get_the_terms( get_the_ID(), 'response-tag' ) ) ) { ?>
        <div class="post_tags_wrap">
  				<div class="post_tags">
  					<h4><?php echo __( 'Response tags:', 'opsi' ); ?></h4>
  					<?php echo get_the_term_list( get_the_ID(), 'response-tag' ); ?>
  				</div>
        </div>
      <?php } ?>

			<?php  if ( !empty( $upload_images ) ) { ?>
			<div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">

						<h4 class="gallery_label"><?php echo __( 'Media:', 'opsi' ); ?></h4>
						<div class="gallery_wrap">
							<?php
							$g = 0;
							foreach ( $upload_images as $upimg ) {
								if ( ! is_array( $upimg ) ) {
									continue;
								}

								echo '<a href="' . $upimg['image']['sizes']['large'] . '" title="'.$upimg['image']['title'].'" class="featuredimglinked fancybox" data-fancybox="gallery" >';
								echo '<img src="'.$upimg['image']['sizes']['tiny'].'" alt="'.$upimg['image']['title'].'" width="'. $upimg['image']['sizes']['tiny-width'] .'" height="'. $upimg['image']['sizes']['tiny-height'] .'"" />';
								echo '</a>';


							}

							?>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>

			<?php if ( !empty( $upload_files ) ) { ?>
			<div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">

						<h4 class="files_label"><?php echo __( 'Files:', 'opsi' ); ?></h4>
						<div class="files_wrap">
							<ul class="nodots nospace">
							<?php

							foreach ( $upload_files as $file ) {
								if ( ! is_array( $file ) ) {
									continue;
								}

								$icon  = '<i class="fa fa-file-archive-o" aria-hidden="true"></i>';
								if ( $file['file']['subtype'] = 'pdf' ) {
									$icon  = '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>';
								}

								echo '
								<li>
									<a href="' . $file['file']['url'] . '" title="'.$file['file']['title'].'" target="_blank" >
										'. $icon .' '.$file['file']['title'].'
									</a><small class="help-block">'. $file['file']['description'] .'</small>
								</li>
								';


							}

							?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>

			<div class="cs_post_meta">
				<div class="row">
				  <div class="col-xs-12">
					<span class="strong make-block">
						<?php echo __( 'Date submitted:', 'opsi' ); ?>
					</span>
					<span class="make-block">
					<?php echo get_the_date(); ?>
					</span>
				  </div>

				</div>
			</div>

      <div class="cs_post_meta">
        <div class="row">
				  <div class="col-xs-12">
            <?php
            echo  bpmts_get_report_button( array(
      				'item_id'    => get_the_ID(),
      				'item_type'  => 'covid_response',
      				'context'    => 'covid_response',
      				'context_id' => get_the_ID(),
      			) );
            ?>
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
