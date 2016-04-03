<?php
/**
 * Template Name: Job Listings
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


                              <?php echo do_shortcode( "[jobs]"); ?>
                        </div>
                        <div class="col2">
                             <?php get_sidebar("jobs"); ?>
                        </div>
                </div>
        </div>        
 </div>     
<?php get_footer(); ?>
