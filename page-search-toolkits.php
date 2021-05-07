<?php get_header('toolkits');
    // note change
    global $post;


  ?>




  <div id="search-toolkits" class="col-sm-12">

  <?php while ( have_posts() ) : the_post(); $postid = get_the_ID(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                  <!-- Display featured image in right-aligned floating div -->

             <section id="search-page-intro">
               <h1 class="search-title"><?php the_title(); ?></h1>
              </section>

              <p class="search-explainer">Still in beta! You can also <a href="/toolkit-navigator/">browse all toolkits</a>, and check out <a href="https://www.oecd-opsi.org/toolkit-taxonomy/">this blog post</a> for more information about how we're organizing toolkits into types and disciplines.</p>

              <div class="entry-content"><?php // the_content(); ?></div>


              <div id="filters-section" class="filters-section col-md-4 col-sm-4 col-xs-12">
	              <?php $toolkit_export_url = 'https://oecd-opsi.org/wp-load.php?security_token=ea4ac87b71ea0d18&export_id=12&action=get_data'; ?>
	              <a class="button btn btn-default btn-block big toolkit-export-button" href="<?php echo $toolkit_export_url . '&time=' . time(); ?>"><?php echo __( 'Export Toolkits', 'opsi' ); ?></a>
              <?php


// add filters here


echo do_shortcode('[searchandfilter id="1894"]');
// note! this id is unique to the wordpress install.
// beta version uses 1414
// production version uses 1894
?>
</div>

<?php

// end of filters




        // the query
        $args = array(
          'post_type' => 'post'
                );
        $args['search_filter_id'] = 1894;
        // note! this id is unique to the wordpress install.
        // beta version uses 1414
        // production version uses 1894


        $the_query = new WP_Query( $args ); ?>

        <?php if ( $the_query->have_posts() ) : ?>

          <div id="search-filter-results-1894" class="results-section col-md-8 col-sm-8 col-xs-12">
            <!--
            // note! this id is unique to the wordpress install.
            // beta version uses 1414
            // production version uses 1894
            -->

        <!-- pagination here -->

        <!-- the loop -->
        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>


        <div class="result-item">
		<?php echo do_shortcode( '[opsi-adaptability_badges size="tiny" wrap_class="adaptabilities tooltips_wrap"]' ); ?>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

<!-- current image handling -->
<div class="results-image col-md-3 col-sm-3 col-xs-12">
  <div class="toolkit-image <?php echo (!has_post_thumbnail() ? 'noimg' : ''); ?>">
    <a href="
    <?php echo the_permalink() ?>" class="toolkit-list-image">
    <?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>
    </a>

  </div>
</div>
<!-- end current image handling -->

<div class="results-content col-md-9 col-sm-9 col-xs-12">
<div class="toolkit-description">
  <?php the_field('description'); ?>
</div>


<div class="toolkit-results-meta-row row col-md-12 col-sm-12 col-xs-12">


  <div class="results-meta-column col-md-4 col-sm-4 col-xs-4">
  <!-- Publisher -->
  <p class="results-meta-heading">Publisher</p>
  <?php
  $publishers = get_field('publisher');
  if( $publishers ): ?>
    <?php foreach( $publishers as $publisher ): ?>
      <p class="search-results-meta">
      <a href="<?php echo get_term_link( $publisher ); ?>"><?php echo $publisher->name; ?></a>
      </p>
    <?php endforeach; ?>
  <?php endif; ?>
  <!-- End Publisher -->
  </div>


<div class="results-meta-column col-md-4 col-sm-4 col-xs-4">
<!-- Discipline or practice -->
<p class="results-meta-heading">Discipline or Practice</p>
<?php
$disciplines = get_field('discipline-or-practice');
if( $disciplines ): ?>
  <?php foreach( $disciplines as $discipline ): ?>
    <p class="search-results-meta">
    <a href="<?php echo get_term_link( $discipline ); ?>"><?php echo $discipline->name; ?></a>
    </p>
  <?php endforeach; ?>
<?php endif; ?>
<!-- End Discipline or practice -->
</div>



<div class="results-meta-column col-md-4 col-sm-4 col-xs-4 final-column">
<!-- Features  -->
<p class="results-meta-heading">Features</p>
<?php
$features = get_field('toolkit-features');
if( $features ): ?>
  <?php foreach( $features as $feature ): ?>
    <p class="search-results-meta">
    <a href="<?php echo get_term_link( $feature ); ?>"><?php echo $feature->name; ?></a>
    </p>
  <?php endforeach; ?>
<?php endif; ?>
<!-- End Features -->
</div>

</div>
</div>
<div class="clearfix"></div>
</div> <!-- result item -->


        <?php endwhile; ?>
        <!-- end of the loop -->

<?php
  //      do_action('search_filter_setup_pagination', 1414);
  //      wpbeginner_numeric_posts_nav();
  ?>

        <!-- pagination here -->

        <?php wp_reset_postdata(); ?>

        <?php else : ?>
        <div id="no-results-message" class="col-md-8 col-sm-8 col-xs-12">
          <h4>No toolkits matched the filters and search criteria.</h4>
          <p><a href="/search-toolkits/">Click here to reset filters</a></p>
        </div>
        <?php endif; ?>


          </article>
      <?php // comments_template(); ?>
      <?php endwhile; ?>




    </div>

  <?php wp_reset_query(); ?>


<?php get_footer(); ?>
