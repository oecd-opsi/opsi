<?php get_header('toolkits');
    // note change
    global $post;


  ?>


  <div id="toolkit-container" class="col-sm-12">

  <?php while ( have_posts() ) : the_post(); $postid = get_the_ID();

    $userid = get_the_author_meta('ID');
    $job = xprofile_get_field_data( 'Job Title', $userid);
    $name = xprofile_get_field_data( 'Name', $userid);

  ?>

  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <p id="show-for-mobile"><a href="/toolkit-navigator/">Toolkit Navigator</a></p>

    <section id="top-section" class="toolkit-section">

      <div id="image-section" class="col-md-4 col-sm-4 col-xs-12">
        <div class="toolkit-image <?php echo (!has_post_thumbnail() ? 'noimg' : ''); ?>">
          <?php
           if ( has_post_thumbnail()) {
            $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'large');
            $img_info =  wp_get_attachment_metadata( get_post_thumbnail_id(get_the_ID()), 'medium' );

            echo '
            <a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" class="featuredimglink fancybox" >';
            echo get_the_post_thumbnail(get_the_ID(), 'medium');
            echo '</a>';

            if ($img_info['image_meta']['caption'] != '') {
              echo '<p>'. $img_info['image_meta']['caption'] .'</p>';
            }
           }
          ?>
        </div>
      </div>

      <div id="intro-section" class="col-md-7 col-sm-7 col-xs-12 toolkit-section">

        <h1 class="toolkit-title">
          <a href="<?php the_field('url'); ?>"><?php the_title(); ?></a>
        </h1>

        <p class="toolkit-description">
          <?php the_field('description'); ?>
        </p>


        <div class="row">
          <div class="meta-column col-md-6 col-sm-6 col-xs-12">
            <h5>Publisher</h5>
            <?php
            $publishers = get_field('publisher');
            if( $publishers ): ?>
            	<?php foreach( $publishers as $publisher ):
                $termSlug = $publisher->name;
                $termSlugLower = strtolower($termSlug);
                $termSlugReady = str_replace(' ', '-', $termSlugLower);
                ?>
            		<p>
            		<a href="/search-toolkits/?_sft_toolkit-publisher=<?php echo $termSlugReady ?>"><?php echo $publisher->name; ?></a>
                </p>
            	<?php endforeach; ?>
            <?php endif; ?>

            <h5>Discipline or practice</h5>
            <?php
            $disciplines = get_field('discipline-or-practice');
            if( $disciplines ): ?>
            	<?php foreach( $disciplines as $discipline ):
                $termSlug = $discipline->name;
                $termSlugLower = strtolower($termSlug);
                $termSlugReady = str_replace(' ', '-', $termSlugLower);
                ?>

            		<p>
            		<a href="/search-toolkits/?_sft_discipline-or-practice=<?php echo $termSlugReady ?>"><?php echo $discipline->name; ?></a>
                </p>

            	<?php endforeach; ?>
            <?php endif; ?>

          </div>
          <div class="meta-column col-md-6 col-sm-6 col-xs-12">

            <h5>Link to toolkit</h5>
            <p><a href="<?php the_field('url'); ?>"><?php the_field('url'); ?></a></p>



            <h5>Source files</h5>
            <?php

            $file1 = get_field('source-file-1');

            if( $file1 ):
            	// vars
            	$url = $file1['url'];
            	$title = $file1['title'];
            	 ?>
            	<p><a href="<?php echo $url; ?>" title="<?php echo $title; ?>">
            		<?php echo $title; ?>
            	</a></p>

            <?php endif; ?>




          </div>
        </div>
      </div>

      <div id="save-and-share" class="col-md-1 col-sm-1 col-xs-12">
        <?php echo (get_field('hide_social_sharing') === true ? '' : wpfai_social()); ?>
      </div>

    </section>

    <section id="details-section" class="toolkit-section col-md-12 col-sm-12 col-xs-12">
      <h2>About this resource</h2>
      <div class="row">
        <div class="meta-column col-md-3 col-sm-3 col-xs-6">
          <h5>Type</h5>
          <?php
          $types = get_field('toolkit-type');
          if( $types ): ?>
            <?php foreach( $types as $type ):
              $termSlug = $type->name;
              $termSlugLower = strtolower($termSlug);
              $termSlugReady = str_replace(' ', '-', $termSlugLower);
              ?>
              <p>
              <a href="/search-toolkits/?_sft_toolkit-type=<?php echo $termSlugReady ?>"><?php echo $type->name; ?></a>
              </p>
            <?php endforeach; ?>
          <?php endif; ?>

        </div>
        <div class="meta-column col-md-3 col-sm-3 col-xs-6">
          <h5>Features</h5>
          <?php
          $features = get_field('toolkit-features');
          if( $features ): ?>
            <?php foreach( $features as $feature ):
              $termSlug = $feature->name;
              $termSlugLower = strtolower($termSlug);
              $termSlugReady = str_replace(' ', '-', $termSlugLower);
              ?>
              <p>
              <a href="/search-toolkits/?_sft_toolkit-features=<?php echo $termSlugReady ?>"><?php echo $feature->name; ?></a>
              </p>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="meta-column col-md-3 col-sm-3 col-xs-6">
          <h5>Country/Territory</h5>
          <?php
          $countries = get_field('country-territory');
          if( $countries ): ?>
            <?php foreach( $countries as $country ):
              $termSlug = $country->name;
              $termSlugLower = strtolower($termSlug);
              $termSlugReady = str_replace(' ', '-', $termSlugLower);
              ?>
              <p>
              <a href="/search-toolkits/?_sft_country-territory=<?php echo $termSlugReady ?>"><?php echo $country->name; ?></a>
              </p>
            <?php endforeach; ?>
          <?php endif; ?>

          <h5>Date Published</h5>
              <p>
              <?php the_field('last-updated'); ?>
              </p>



        </div>
        <div class="meta-column col-md-3 col-sm-3 col-xs-6">
          <h5>License</h5>
          <?php
          $licenses = get_field('license');
          if( $licenses ): ?>
            <?php foreach( $licenses as $license ):
              $termSlug = $license->name;
              $termSlugLower = strtolower($termSlug);
              $termSlugReady = str_replace(' ', '-', $termSlugReady);
              ?>
              <p>
              <a href="/search-toolkits/?_sft_license=<?php echo $termSlugReady ?>"><?php echo $license->name; ?></a>
              </p>
            <?php endforeach; ?>
          <?php endif; ?>

          <h5>Formats</h5>
          <?php
              $formats = get_field('format');
              if( $formats ): ?>
                <?php foreach( $formats as $format ): ?>
                  <p>
                  <?php echo $format->name; ?>
                  </p>
                <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>

  </article>

  <!-- <section id="feedback-section" class="toolkit-section col-md-12 col-sm-12 col-xs-12"> -->

      <?php
      // $args = array (
      //     'status' => 'approve',
      //     'number' => '2'
      //     );
      //     $comments = get_comments( $args );
      //     if ( !empty( $comments ) ) :
      //     echo '<div class="row">';
      //     foreach( $comments as $comment ) :
      //     echo '<div class="meta-column col-md-6 col-sm-6 col-xs-12"><h5>' . $comment->comment_text . '</h5> <p><a href="' . comment_author_url() . '</a></p>' . $comment->comment_author . '</h5></div>';
      //     endforeach;
      //     echo '</div>';
      //     endif;
       ?>

  <!-- </section> -->

  <section id="referral-section" class="toolkit-section col-md-12 col-sm-12 col-xs-12">
    <div class="row">
      <div class="meta-column col-md-6 col-sm-6 col-xs-12">
        <div id="cases-referral-block" class="referral-block">
          <h5>See cases from others doing this in government</h5>
          <p><a href="/our-work/case-studies/">Go to case studies</a></p>
        </div>
      </div>
      <div class="meta-column col-md-6 col-sm-6 col-xs-12">
        <div id="cases-referral-block" class="referral-block">
          <h5>Find experts and advisers who can assist me with this</h5>
          <p><a href="/about-observatory-of-public-sector-innovation/about-our-in-country-contacts/">Go to advice</a></p>
        </div>
      </div>
    </div>
  </section>

  <section id="similar-resources-section" class="toolkit-section col-md-12 col-sm-12 col-xs-12">

    <?php
          $currentID = get_the_ID();

          $disciplines = get_field('discipline-or-practice');
          if( $disciplines ):
           foreach( array_reverse($disciplines) as $discipline ):

            $disciplineSlug = $discipline->name;

            endforeach;
        endif;




        $disciplineUpper = ucwords($disciplineSlug);
        $disciplineLower = strtolower($disciplineSlug);
        $disciplineHyphenated = str_replace(' ', '-', $disciplineLower);

        ?>



        <h2>Other toolkits related to <?php echo $disciplineUpper ?></h2>


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
            'post__not_in' => array($currentID), // removes the current page from being shown
            'posts_per_page' => 6,

            );

            $cssCounter = 1;


           $the_query = new WP_Query( $args ); ?>

            <?php if ( $the_query->have_posts() ) : ?>
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

            <div class="related-toolkits-column related-item-<?php echo $cssCounter ?> col-md-6 col-sm-6 col-xs-12">

              <div class="related-toolkit-image col-md-4 col-sm-4 col-xs-6">
                <div class="related-image-box">
                  <a href="
                  <?php echo the_permalink() ?>" class="toolkit-list-image">
                  <?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>
                  </a>
                </div>
              </div>

              <div class="related-toolkit-meta col-md-8 col-sm-8 col-xs-6">
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
              </div>
            </div> <!-- result item -->


            <?php
            $cssCounter++;
            endwhile; ?>
            <!-- end of the loop -->


            <!-- pagination here -->

            <?php wp_reset_postdata(); ?>

            <?php else : ?>
            <p>We're working on adding more toolkits in this discipline or practice.</p>
            <?php endif; ?>




    <h4 class="view-all-link"><a href="/search-toolkits/?_sft_discipline-or-practice=<?php echo $disciplineHyphenated ?>">View all toolkits related to <?php echo $disciplineUpper ?></a></h4>


  </section>




  <section id="all-comments" class="toolkit-section">

    <?php

    comments_template('/comments-toolkit.php');


    ?>
    <?php endwhile; ?>
  </section>

</div>

<?php wp_reset_query(); ?>
<?php get_footer(); ?>
