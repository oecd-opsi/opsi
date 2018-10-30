<?php get_header();

    global $post;

    $has_sidebar = 0;
	$layout = 'fullpage';

?>



    <div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo ($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">

<?php while ( have_posts() ) : the_post(); $postid = get_the_ID();

    $userid = get_the_author_meta('ID');
    $job = xprofile_get_field_data( 'Job Title', $userid);
    $name = xprofile_get_field_data( 'Name', $userid);

	$upload_images 	= get_field( 'materials_&_short_explanation_photo_and_video_upload_images' );
	$upload_files 	= get_field( 'materials_&_short_explanation_photo_and_video_upload_supporting_files' );
	$other_url 		= get_field( 'materials_&_short_explanation_photo_and_video_other_related_url' );



	$country = wp_get_post_terms( $postid, 'country' );
	$badges = wp_get_post_terms( $postid, 'innovation-badge' );
	$iso = false;
	if ( $country ) {
		$iso = get_field( 'iso_code', $country[0] );
	}

	$fields = get_all_acf_fields_by_group_key( 'group_5ae729d53fd82', false );
	$texts = get_textarea_acf_fields_by_group_key( 'group_5ae729d53fd82', true );

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

				<?php if ( isset( $upload_images[0] ) ) { ?>


				<div class="single_img_wrap <?php echo (!isset( $upload_images[0] ) ? 'noimg' : ''); ?>">
                <?php

					echo '
					<a href="' . $upload_images[0]['sizes']['large'] . '" title="' . the_title_attribute('echo=0') . '" class="featuredimglink fancybox" >';
					echo '<img src="'.$upload_images[0]['sizes']['blog'].'" alt="'.$upload_images[0]['title'].'" width="'. $upload_images[0]['sizes']['blog-width'] .'" height="'. $upload_images[0]['sizes']['blog-height'] .'"" />';
					echo '</a>';

					if ($upload_images[0]['caption'] != '') {
						echo '<p>'. $upload_images[0]['caption'] .'</p>';
					}
					echo ( get_field('hide_social_sharing') === true ? '' : wpfai_social() );
                ?>
				</div>
				<?php } ?>

				<?php
					if ( !isset( $upload_images[0] ) ) { ?>

						<div class="social_sharing_wrap pull-right">
							<?php echo ( get_field('hide_social_sharing') === true ? '' : wpfai_social() ); ?>
						</div>

					<?php
					}
				?>
				<?php if (get_field('hide_page_title') !== true) { ?>
				<h1 class="entry-title <?php echo ( !empty( $badges ) ? 'pull-left' : ''); ?>"><?php the_title(); ?></h1>
				<?php } ?>

				<?php
					if ( !empty( $badges ) ) {
						echo '<div class="tooltips_wrap">';
						foreach ( $badges as $badge ) {

							$icon = get_field( 'icon', $badge );

							echo '
								<span class="tooltips">
									<a href="'. get_post_type_archive_link( 'case' ) .'?_recognition='. $badge->slug .'" title="'. $badge->name .' '.__( 'innovations', 'opsi' ) .'" target="_blank">
										<img src="'. $icon['url'] .'" width="'. $icon['width'] .'" height="'. $icon['height'] .'" alt="'. $badge->name .'" />
									</a>
									<span>'. $badge->name . ( $badge->description != '' ? '<br /><small>'. $badge->description : '') .'</small></span>
								</span>';

						}
						echo '</div>';
						echo '<div class="clearfix padding20"></div>';
					}
				?>
				<div class="entry-content"><?php the_content(); ?></div>

				<?php if ( $fields['describing_the_innovation']['short_and_simple_explanation'] != '' ) { ?>
					<div class="csp"><?php echo $fields['describing_the_innovation']['short_and_simple_explanation']['text']; ?></div>
				<?php } ?>

				<?php
					$ii = 0; // inner index
					$oi = 0; // outter index
					foreach ( $textsloop as $tareas ) {

						if ( $tareas['display_order'] == -2 ) { continue; }



					?>

						<div class="sliding_panel <?php echo ( $oi == 0 ? 'open' : '' ); ?>">
							<h2 class="<?php echo ( $oi == 0 ? 'active' : '' ); ?>"><?php echo $tareas['label']; ?></h2>
								<div class="panel_content" <?php echo ( $oi == 0 ? '' : 'style="display: none;"' ); ?>>


					<?php

									unset( $tareas['label'] ); // remove the label and loop
									foreach( $tareas as $tarea ) { ?>

									<?php if ( isset( $tarea['text'] ) && $tarea['text'] != '' ) {
												if ( $tarea['display_order'] == -2 ) { continue; }
									?>
										<?php if ( $tarea['label'] != '_removed' ) { ?>
											<h3><?php echo  $tarea['label']; ?></h3>
										<?php } ?>
										<div class="csp"><?php echo  $tarea['text']; ?></div>
									<?php } ?>
								<?php
									$ii++;
								}

								?>
							</div>
						</div>
				<?php
						$ii = 0;
						$oi++;
					}

					$video1 = get_field( 'materials_&_short_explanation_photo_and_video_video_url_1' );
					$video2 = get_field( 'materials_&_short_explanation_photo_and_video_video_url_2' );
					$video3 = get_field( 'materials_&_short_explanation_photo_and_video_video_url_3' );
					?>
					<div class="video_wrap">
					<?php
						if ( $video1 ) { echo '<h3>'. __( 'Project Pitch', 'opsi' ) .'</h3><div class="single_vid">'.wp_oembed_get( $video1 ).'</div>'; }
						if ( $video2 || $video3 ) { echo '<h3>'. __( 'Supporting Videos', 'opsi' ) .'</h3>'; }
						if ( $video2 ) { echo '<div class="single_vid">'.wp_oembed_get( $video2 ).'</div>'; }
						if ( $video3 ) { echo '<div class="single_vid">'.wp_oembed_get( $video3 ).'</div>'; }
					?>
					</div>
          </article>
      <?php comments_template(); ?>


		</div>
		<div class="col-md-3 case_study_sidebar">


			<div class="cs_sidebar_wrap">
				<div class="row">
					<?php if ( $iso != '' ) { ?>
					<div class="col-xs-4">
						<a href="<?php echo get_post_type_archive_link( 'case' ); ?>?_countries=<?php echo $country[0]->slug; ?>" title="<?php echo __( 'All innovations by:', 'opsi' ); ?> <?php echo $country[0]->name; ?>" class="blacklink" target="_blank" >
							<img src="<?php echo get_stylesheet_directory_uri().'/images/flags/'.$iso.'.png'; ?>" width="96" height="96" alt="<?php echo $country[0]->name; ?>" class="cs_flag" />
						</a>
					</div>
					<?php } ?>
					<?php if ( !empty( $country ) ) { ?>
					<div class="col-xs-8 cs_country_name">
						<h4>
							<a href="<?php echo get_post_type_archive_link( 'case' ); ?>?_countries=<?php echo $country[0]->slug; ?>" title="<?php echo __( 'All innovations by:', 'opsi' ); ?> <?php echo $country[0]->name; ?>" class="blacklink" target="_blank" >
								<?php echo $country[0]->name; ?>
						</h4></a>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">

					<?php if ( $fields['describing_the_innovation']['year_innovation_launched'] != '' ) { ?>
						<div class="year_wrap">
							<span class="sidebar_label"><?php echo __( 'Year:', 'opsi' ); ?></span>
							<span class="strong"><?php echo $fields['describing_the_innovation']['year_innovation_launched']['text']; ?></span>
						</div>
					<?php } ?>


					<?php if ( $fields['describing_the_innovation']['innovation_website']['text'] != '' ) { ?>
						<div class="website_wrap">
							<span class="sidebar_label"><?php echo __( 'Website:', 'opsi' ); ?></span>
							<span class="strong truncate">
								<a href="<?php echo $fields['describing_the_innovation']['innovation_website']['text']; ?>" title="<?php echo $fields['describing_the_innovation']['name_of_innovation']['text']; ?>" target="_blank" >
									<?php echo $fields['describing_the_innovation']['innovation_website']['text']; ?>
								</a>
							</span>
						</div>
					<?php } ?>
					<?php if ( $other_url != '' ) { ?>
						<div class="website_wrap additional">
							<span class="strong truncate">
								<a href="<?php echo $other_url; ?>" title="<?php echo __( 'additional URL', 'opsi' ); ?>" target="_blank" >
									<?php echo $other_url; ?>
								</a>
							</span>
						</div>
					<?php } ?>


					<?php if ( !empty( $fields['organisation_details']['level_of_government'] ) ) { ?>
						<div class="log_wrap">
							<span class="sidebar_label"><?php echo __( 'Level of government:', 'opsi' ); ?></span>
							<span class="strong"><?php echo $fields['organisation_details']['level_of_government']['text']['label']; ?></span>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>

			<?php if ( !empty( $fields['innovation_description']['innovation_status']['text'] ) ) { ?>
			<div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-xs-12">
						<h4 class="status_label"><?php echo __( 'Status:', 'opsi' ); ?></h4>
						<ul class="nospace">
						<?php foreach( $fields['innovation_description']['innovation_status']['text'] as $status ) { ?>
								<li><span class="strong"><?php echo $status['label']; ?></span></li>
						<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<?php } ?>

			<div class="post_tags_wrap">
				<div class="post_tags">
					<h4><?php echo __( 'Innovation tags:', 'opsi' ); ?></h4>
					<?php echo get_the_term_list( get_the_ID(), 'innovation-tag' );

					?>
					<?php if ( !empty( $fields['describing_the_innovation']['custom_innovation_tags']['text'] ) ) {

						$custom_tags = explode( ',', $fields['describing_the_innovation']['custom_innovation_tags']['text'] );

						foreach( $custom_tags as $ctag ) {

							echo '<span class="custom_tag" title="'. $ctag .'">'. $ctag .'</span>';

						}

					}
					?>
				 </div>
             </div>

			<div class="row">
				<div class="col-md-12">
					<?php echo get_collaborators_list( get_the_ID() ); ?>
				</div>
			</div>

			<?php  if ( !empty( $upload_images ) ) { ?>
			<div class="cs_sidebar_wrap">
				<div class="row">
					<div class="col-md-12">

						<h4 class="gallery_label"><?php echo __( 'Media:', 'opsi' ); ?></h4>
						<div class="gallery_wrap">
							<?php
							$g = 0;
							foreach ( $upload_images as $upimg ) {

								echo '<a href="' . $upimg['sizes']['large'] . '" title="'.$upimg['title'].'" class="featuredimglinked fancybox" data-fancybox="gallery" >';
								echo '<img src="'.$upimg['sizes']['tiny'].'" alt="'.$upimg['title'].'" width="'. $upimg['sizes']['tiny-width'] .'" height="'. $upimg['sizes']['tiny-height'] .'"" />';
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
								$icon  = '<i class="fa fa-file-archive-o" aria-hidden="true"></i>';
								if ( $file['subtype'] = 'pdf' ) {
									$icon  = '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>';
								}

								echo '
								<li>
									<a href="' . $file['url'] . '" title="'.$file['title'].'" target="_blank" >
										'. $icon .' '.$file['title'].'
									</a><small class="help-block">'. $file['description'] .'</small>
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
