<?php
/**
 * Template Name: Partners
 *
 * @package WordPress
 * @subpackage Business
 * @since Business 1.0
 */

get_header(); ?>

	 

        
 <div class="content-wrapper">       
        <div class="colmask leftmenu">
                <div class="colleft">
                        <div class="col1">

   <?php
                    // Start the loop.
                    while ( have_posts() ) : the_post();

                            // Include the page content template.
                            get_template_part( 'content', 'page' );

                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                    comments_template();
                            endif;

                    // End the loop.
                    endwhile;
                    ?>
                              
                        </div>
                        <div class="col2">
                             <?php get_sidebar("partners"); ?>
                        </div>
                </div>
        </div>        
 </div>     
<?php get_footer(); ?>
