<?php
get_header();
    if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <div class="row">
    <div class="feature col-md-3 col-sm-6 col-xs-12" id="<?php the_ID()?>">
      <?php
            $color = get_post_meta( get_the_ID(), 'meta-color', true );
            //Adjusting the value of fmp-excerpt-limit
            $limit =  get_post_meta( get_the_ID(), 'fmp-excerpt-limit', true );
            switch ($limit) {
              case 'select-five':
                $limit = 5;
                break;
              case 'select-ten':
                $limit = 10;
                break;
              case 'select-fifteen':
                $limit = 15;
                break;
              case 'select-twenty':
                $limit = 20;
                break;
              default:
                $limit = 15;
                break;
            }
      ?>


        <div class="fmp-circle center-block on-click" style="border-color:<?php print $color ?>">

          <!-- Getting featured image to make the icon -->
          <?php if (has_post_thumbnail( get_the_ID() ) ): ?>
          <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ); ?>
          <i id="fmp-icon" style="background: url('<?php echo $image[0]; ?>') no-repeat center"></i>
          <?php endif; ?>

        </div>

            <h3 id="feature-title" class="text-uppercase text-center">
            <?php the_title(  ); ?>
            </h3>
          <div class="title-container" style="border-color:<?php print$color; ?>">
          </div>

        <p class="text-center" id="feature-text">
        <?php
              $content =  get_the_content();
              $trimmed = wp_trim_words( $content, $limit, ('<br><br><a id="read-more"><p class="on-click text-center">Read more</p></a>') );
              print $trimmed;
         ?>
        </p>
      </div>
  </div>
<!--MODAL POPUP-->
  <div  class="modal modal-feature" id="fmpModal" tabindex="-1" role="dialog" aria-labelledby="featureModalLabel-1" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&times;</button>
                 <h1 class="text-center"> <?php the_title()?> </h1>

             </div>
             <div class="modal-body">

                 <div class="row">
                   <div class="col-md-4 col-sm-12 col-xs-12">
                     <?php fmp_echo_first_image(get_the_ID()); ?>
                   </div>

                 <div class="content col-md-8 col-sm-12 col-xs-12">
                         <?php

                         ob_start();
                         the_content();
                         $postOutput = preg_replace('/<img[^>]+./','', ob_get_contents());
                         ob_end_clean();
                         echo $postOutput;
                      ?>

                     </div><!--//content-->
                 </div><!--//row-->
             </div><!--//modal-body-->
         </div><!--//modal-content-->
     </div><!--//modal-dialog-->
 </div><!--//modal-->

 
 <script >
 $(document).ready(function(){
   $('.on-click').click(function(){
     $('#fmpModal').modal('show');
     $('#myModal').modal('handleUpdate');
   });
 });
 </script>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif;//End of the loop

get_footer();
 ?>
