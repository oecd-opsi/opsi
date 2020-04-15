<?php
	if ( ! defined( 'ABSPATH' ) ) die('no direct access'); // Exit if accessed directly

	$country = wp_get_post_terms( get_the_ID(), 'country' );
    $iso = get_field( 'iso_code', $country[0] );
	$userid = get_the_author_meta('ID');
	$owner = get_user_by( 'ID', $userid );
    $name = function_exists( 'xprofile_get_field_data' )? xprofile_get_field_data( 'Name', $userid) : '';

  ?>
<div class="col-md-12 case_col">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="row">
			<div class="col-md-9 col-sm-8">
				<h2 class="article-title h4"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			</div>
			<div class="col-md-3 col-sm-4">
				<div class="pull-right country_flag">
					<a href="<?php echo get_post_type_archive_link( 'covid_response' ); ?>?_countries=<?php echo $country[0]->slug; ?>" title="<?php echo __( 'All innovations by:', 'opsi' ); ?> <?php echo $country[0]->name; ?>" class="blacklink" >
						<?php echo $country[0]->name; ?>
					</a>
					<a href="<?php echo get_post_type_archive_link( 'covid_response' ); ?>?_countries=<?php echo $country[0]->slug; ?>">
						<img src="<?php echo get_stylesheet_directory_uri().'/images/flags/'.$iso.'.png'; ?>" width="96" height="96" alt="<?php echo $country[0]->name; ?>" class="cs_flag" />
					</a>
				</div>
			</div>
		</div>

		<hr class="divline">

		<div class="row">
			<div class="col-md-3 col-sm-4">
				<div class="post_author">
					<div class="post_tags_wrap">
						<div class="post_tags">
							<?php
							// get slug of taxonomy term of queried object
							$taxonomy = get_queried_object();
							$tax_term_slug = $taxonomy->slug;
							$tags = get_the_term_list( get_the_ID(), 'response-tag' );
							?>
							<?php echo $tags ?>
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
					$information = get_field( 'information_about_the_response' );
					if ( ! empty( $information['innovative_response_description'] ) ) { ?>
						<p class="csp"><?php echo mb_strimwidth( $information['innovative_response_description'], 0, 500, '...' ); ?></p>
					<?php } ?>
					<?php
					echo bpmts_get_report_button( array(
						'item_id'    => get_the_ID(),
						'item_type'  => 'covid_response',
						'context'    => 'covid_response',
						'context_id' => get_the_ID(),
					) );
					?>
					<p class="read_more">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="button btn btn-info btn-sm">
							<?php echo __( 'View response details', 'opsi' ); ?>
							<i class="fa fa-chevron-right" aria-hidden="true"></i>
						</a>
					</p>
			</div>
		</div>
    </article>
</div>
