<?php
/*
    Template: Feature
 */
define('WP_USE_THEMES', false); get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <div class="row">
    <div class="feature col-md-3 col-sm-6 col-xs-12" id="<?php the_ID()?>">

        <a href="#">
          <?php echo get_the_post_thumbnail( $page->ID, 'thumbnail' , array( 'class' => 'center-block img-circle')) ?>
        </a>
        <?php the_title( '<h3 class="text-uppercase text-center">', '</h3>' ); ?>
        <p class="text-center">
        <?php
              $content =  get_the_content();
              $trimmed = wp_trim_words( $content, $num_words = 55,  __( '<br>Read more' ) );
              print $trimmed;
         ?>
        </p>
        <small></small>

    </div>
  </div>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif;

get_footer();
 ?>
