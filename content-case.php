<?php
	if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly
  
	$country = wp_get_post_terms( get_the_ID(), 'country' );	
    $iso = get_field( 'iso_code', $country[0] );
	$userid = get_the_author_meta('ID');
	$owner = get_user_by( 'ID', $userid );
    $name = xprofile_get_field_data( 'Name', $userid);
      
  ?>
<div class="col-md-12 case_col">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="row">
			<div class="col-md-9 col-sm-8">
				<h2 class="article-title h4"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			</div>
			<div class="col-md-3 col-sm-4">
				<div class="pull-right country_flag">
					<a href="<?php echo get_post_type_archive_link( 'case' ); ?>?_countries=<?php echo $country[0]->slug; ?>" title="<?php echo __( 'All innovations by:', 'opsi' ); ?> <?php echo $country[0]->name; ?>" class="blacklink" >
						<?php echo $country[0]->name; ?>
					</a>
					<a href="<?php echo get_post_type_archive_link( 'case' ); ?>?_countries=<?php echo $country[0]->slug; ?>">
						<img src="<?php echo get_stylesheet_directory_uri().'/images/flags/'.$iso.'.png'; ?>" width="96" height="96" alt="<?php echo $country[0]->name; ?>" class="cs_flag" />
					</a>
				</div>
			</div>
		</div>
		
		<hr class="divline">
		
		<div class="row">
			<div class="col-md-3 col-sm-4">
				<div class="post_author">
					<?php if ( is_user_logged_in() ) { ?>
					<a href="<?php echo bp_core_get_userlink( $userid, false, true ); ?>" title="<?php echo  $owner->display_name .' '. __( 'profile', 'opsi' ); ?>">
						<?php echo bp_get_displayed_user_avatar( array('item_id' => $owner->ID, 'type'=>'thumb') ); ?>
					</a>
					<?php } else { 
						echo get_default_avatar_img( 'thumb' );
					}
					if ( is_user_logged_in() ) {
					?>
					<span class="submitted_by">
						<span class="submitted_label"><?php echo __( 'Submitted by:', 'opsi' ); ?></span>
						<a href="<?php echo bp_core_get_userlink( $userid, false, true ); ?>" title="<?php echo  $owner->display_name .' '. __( 'profile', 'opsi' ); ?>" class="author_link">
							<span class="author_name"><?php echo  $owner->first_name .' '. $owner->last_name; ?></span>
						</a>
					</span>
					<?php } else { ?>
						<span class="submitted_by">
							<a href="<?php echo get_permalink( get_page_by_path('login') ); ?>" title="<?php echo __( 'Log In', 'opsi'); ?>" class="author_link">
								<span class="author_name">
									<br /><?php echo __( 'Log In to View', 'opsi'); ?>
								</span>
							</a>
						</span>
					<?php } ?>
					<div class="clearfix"></div>
					<div class="post_tags_wrap">
						<div class="post_tags">
							<?php echo get_the_term_list( get_the_ID(), 'innovation-tag' ); ?>
							<?php if ( !empty( get_field( 'describing_the_innovation_custom_innovation_tags' ) ) ) { 
						
								$custom_tags = explode( ',', get_field( 'describing_the_innovation_custom_innovation_tags' ) );
														
								foreach( $custom_tags as $ctag ) {
									
									echo '<span class="custom_tag" title="'. $ctag .'">'. $ctag .'</span>';
									
								}
							
							}
							?>
						</div>
					</div>
					
				</div>
			</div>
			<div class="col-md-9 col-sm-8">
				<?php 
					$executive_summary = get_field( 'describing_the_innovation_short_and_simple_explanation' );
					if ( $executive_summary != '' ) { ?>
						<p class="csp"><?php echo $executive_summary; ?></p>
					<?php } ?>
					<p class="read_more">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="button btn btn-info btn-sm">
							<?php echo __( 'Read case study', 'opsi' ); ?>
							<i class="fa fa-chevron-right" aria-hidden="true"></i>
						</a>
					</p>
			</div>
		</div>
    </article>
</div>