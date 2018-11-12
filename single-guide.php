<?php get_header('toolkits');
    // note change
    global $post;

    $is_subpage = $post->post_parent !== 0;
    $main_content_id = $is_subpage ? 'subguide-main-content' : 'guide-main-content';
    $main_content_classes = $is_subpage ? 'subguide-page-content wpb_column vc_column_container col-md-12 vc_col-sm-12' : 'guide-page-content wpb_column vc_column_container col-md-8 vc_col-sm-12';
  ?>






  <?php while ( have_posts() ) : the_post(); $postid = get_the_ID(); ?>
          <article class="toolkit-guide" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                  <!-- Display featured image in right-aligned floating div -->
            <p id="show-for-mobile"><a href="/toolkit-navigator/">Toolkit Navigator</a></p>


            <section id="guide-header">

			  <?php do_action( 'opsi-before-guide-title', $is_subpage ); ?>
              <h1 class="guide-title"><?php the_title(); ?></h1>
              <p id="skip-for-mobile"><a href="#guide-related-header">Scroll to toolkits</a></p>

            </section>

            <section id="<?php echo $main_content_id; ?>" class="<?php echo $main_content_classes; ?>">

              <?php the_content(); ?>

            </section>

			<?php

			if ( !$is_subpage ) :

				$currentID = get_the_ID();

				$disciplines = get_field('guide_discipline_or_practice');
				if( $disciplines ):
					foreach( array_reverse($disciplines) as $discipline ):

						$disciplineSlug = $discipline->name;

					endforeach;
				endif;

				$disciplineUpper = ucwords($disciplineSlug);
				$disciplineLower = strtolower($disciplineSlug);
				$disciplineHyphenated = str_replace(' ', '-', $disciplineLower);

			?>

            <section id="guide-sidebar" class="guide-page-content wpb_column vc_column_container col-md-4 vc_col-sm-12">

              <section id="referral-section" class="toolkit-section col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                  <div class="referral-box meta-column col-md-12 col-xs-12">
                    <div id="cases-referral-block" class="referral-block">
                      <h5>Contact other experts in this area</h5>
                      <p><a href="https://oecd-opsi.org/members/?members_search=<?php echo $disciplineHyphenated; ?>">Log in to connect</a></p>
                    </div>
                  </div>
                  <div class="referral-box meta-column col-md-12 col-xs-12">
                    <div id="cases-referral-block" class="referral-block">
                      <h5>See how other governments are doing this work</h5>
                      <p><a href="/our-work/case-studies/">Go to case studies</a></p>
                    </div>
                  </div>

                </div>



                    <h2 id="guide-related-header"><?php echo $disciplineUpper ?> toolkits</h2>

				  	<h2 class="view-all-link"><a href="/search-toolkits/?_sft_discipline-or-practice=<?php echo $disciplineHyphenated ?>">View all toolkits for <?php echo $disciplineUpper ?></a></h2>


            <?php


                       $args = array(
                         'post_type'   => 'toolkit',
                         'post_status' => 'publish',
                         'tax_query'   => array(
                         	array(
                         		'taxonomy' => 'discipline-or-practice',
                            'field'    => 'slug',
                         		'terms'    => $disciplineSlug // current discipline or practice
                         	)
                        ),
                        'posts_per_page' => 6,

                        );



                       $the_query = new WP_Query( $args ); ?>

                        <?php if ( $the_query->have_posts() ) : ?>
                        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

                        <div class="related-toolkits-item guides-related-item col-md-12 col-sm-12 col-xs-12">

                            <div class="guide-sidebar-image-box">
                              <a href="
                              <?php echo the_permalink() ?>" class="toolkit-list-image">
                              <?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>
                              </a>
                            </div>

                            <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                              <?php
                              $publishers = get_field('publisher');
                              if( $publishers ): ?>
                                <?php foreach( $publishers as $publisher ): ?>
                                  <h4>
                                  <?php echo $publisher->name; ?>
                                </h4>
                                <?php endforeach; ?>
                              <?php endif; ?>
                            <p><?php the_field('description'); ?></p>
                        </div> <!-- result item -->


                        <?php
                        endwhile; ?>
                        <!-- end of the loop -->


                        <!-- pagination here -->

                        <?php wp_reset_postdata(); ?>

                        <?php else : ?>
                        <p>We're working on adding more toolkits in this discipline or practice.</p>
                        <?php endif; ?>


              </section>

            </section>

			<?php endif; ?>



            <section id="another-section" class="browse-page-content category-row wpb_column vc_column_container col-md-12 vc_col-sm-12">



            </section>







          </article>
      <?php // comments_template(); ?>
      <?php endwhile; ?>





  <?php wp_reset_query(); ?>


<?php get_footer(); ?>
