<?php
 get_header(); ?>
<div class="container">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


          <div class="feature col-md-3 col-sm-6 col-xs-12 to-click"  >
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


                <div class="fmp-circle center-block on-click add-color" data-id="<?php the_ID()?>" data-color="<?php echo $color ?>">

                  <!-- Getting featured image to make the icon -->
                  <?php if (has_post_thumbnail( get_the_ID() ) ): ?>
                  <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ); ?>
                  <i id="fmp-icon" style="background: url('<?php echo $image[0]; ?>') no-repeat center"></i>
                  <?php endif; ?>

                </div>

                  <h3 id="feature-title" class="text-uppercase text-center">
                  <?php the_title(  ); ?>
                  </h3>
                  <div class="title-container" style="border-color:<?php print $color; ?>">
                  </div>

                  <p class="text-center" id="feature-text">
                <?php
                      $content =  get_the_content();
                      $trimmed = wp_trim_words( $content, $limit, ('<br><br><a id="read-more"><p class="text-center other-on-click" data-test="'.get_the_ID().'">Read more</p></a>') );
                      print $trimmed;
                 ?>
              </p>
          </div>

            <!-- MODAL POPUP-->
        <div  class="modal modal-feature" id="fmpModal-<?php the_ID()?>"  tabindex="-1" role="dialog" aria-labelledby="featureModalLabel-1" aria-hidden="true">
           <div class="modal-dialog modal-lg">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&times;</button>
                       <h1 class="text-center"> <?php the_title()?> </h1>
                   </div>
                   <div class="modal-body">

                       <div class="row">
                         <div class="col-md-4 col-sm-12 col-xs-12 modal-img">
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


      <?php endwhile; else : ?>
      	<p><?php _e( 'Sorry, no feature registered here.' ); ?></p><!--END OF THE LOOP-->
      <?php endif; ?>

    </div><!--//col-md-12 col-sm-12 col-xs-12-->
  </div><!--//row-->
</div><!--//container-->

<script>

//Functions to call the modal

$(document).ready(function(){
  //Calling modal from 'on-click' class
  $('.on-click').click(function(){
    var fieldData = $(this).data('id');
    $('#fmpModal-' + fieldData).modal('show');
  });
//Calling modal from 'on-click' class - ('Read More' button)
  $('.other-on-click').click(function(){
    var fieldTest = $(this).data('test');
    $('#fmpModal-' + fieldTest).modal('show');
  });
  //Adding color to border and circle elements

  $('.add-color').mouseover(function(){
    var dataColor = $(this).data('color');
      $(this).css('border-color',dataColor);
  });
  $('.add-color').mouseout(function(){
    var dataColor = $(this).data('color');
      $(this).css('border-color','#ececec');
  });


});

</script>

<?php
 get_footer();
?>
