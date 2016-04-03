<?php
/**
 * The template for displaying front page
 * @package WordPress
 * @subpackage Business
 * @since Business 1.0
 */

get_header(); ?>
<style>
.flexslider {
    margin-top:98px !important;

}
.flexslider ul li img {
   object-fit:cover;
 
}

</style>
<?php echo do_shortcode( "[slider id='123' name='Home page carousel'  size='full']"); ?>
<div class="main_area_homepg">
<div class="container">
       		<div class="main_tagLine">domine, manifestus sum, quicumque sfructu.</div>

            <div class="sub_tagLine"><div class="wrps">Register and Post your jobs/tenders today and get unlimited access to blade and his resources .</div></div>

       	 	

            <form method="get" action="">

            <div class="search_box_main">

            	<div class="search_box_main2">

                    <div class="rama1"><input type="text" placeholder="Search for Tenders? (e.g. Construction Tenders)" id="findTenders" name="term"></div>

                    <div class="search-button"><input type="image" src="" width="44" height="44"></div>

                </div>

            </div>

            </form>

        	

            <div class="buttons_box_main">

            	<ul class="regular_ul">

                	<li><a href="">Post Your Job/Tender</a></li>

                	<li><a href="">Register. Now</a></li>

                </ul>

            

            </div>

        

        </div>  
</div>
<div class="content-wrapper"> 
    
           
               
    <div class="colmask leftmenu">


			<div class="box-1" >

<div class="text-block adjust-v-margin bg-text-grey" style="height: 335px;">
<div class="ui-header-container">
<h3>
On your phone
</h3>
</div>
<div class="ui-text-container">
<div>
<p>
‘domine, quis similis tibi?’ dirupisti vincula mea: sacrificem tibi sacrificium laudis. quomodo dirupisti ea narrabo, et dicent omnes qui adorant te, cum audiunt haec, ‘benedictus dominus in caelo et in terra; magnum et mirabile nomen eius.’ inhaeserant praecordiis meis verba tua, et un</p>
<div class="ui-cta-container el-align-left">
<a href="" data-track-type="PrimaryCTA" class="pri-button" title="Register" target="">Register
</a>
</div>
</div>

</div>
</div>

</div>
			<div class="box-1" >

<div class="text-block adjust-v-margin bg-text-grey" style="height: 335px;">
<div class="ui-header-container">
<h3 style="background-color:#cc0000">
JOBS
</h3>
</div>
<div class="ui-text-container">
<div>
<p>
‘domine, quis similis tibi?’ dirupisti vincula mea: sacrificem tibi sacrificium laudis. quomodo dirupisti ea narrabo, et dicent omnes qui adorant te, cum audiunt haec, ‘benedictus dominus in caelo et in terra; magnum et mirabile nomen eius.’ inhaeserant praecordiis meis verba tua, et un</p>
<div class="ui-cta-container el-align-left">
<a href="" data-track-type="PrimaryCTA" class="pri-button" title="Register" target="">Register
</a>
</div>
</div>

</div>
</div>
</div>
			<div class="box-1" >

<div class="text-block adjust-v-margin bg-text-grey" style="height: 335px;">
<div class="ui-header-container">
<h3 style="background-color:#ccc">
GO Bid
</h3>
</div>
<div class="ui-text-container">
<div>
<p>
‘domine, quis similis tibi?’ dirupisti vincula mea: sacrificem tibi sacrificium laudis. quomodo dirupisti ea narrabo, et dicent omnes qui adorant te, cum audiunt haec, ‘benedictus dominus in caelo et in terra; magnum et mirabile nomen eius.’ inhaeserant praecordiis meis verba tua, et un.</p>
<div class="ui-cta-container el-align-left">
    <a href="" data-track-type="PrimaryCTA" class="pri-button" title="Register" target="">Register
</a>
</div>
</div>

</div>
</div>

</div>
			<div class="box-1" >

<div class="text-block adjust-v-margin bg-text-grey" style="height: 335px;">
<div class="ui-header-container">
<h3  style="background-color:green">
Tenders
</h3>
</div>
<div class="ui-text-container">
<div>
<p>
Manage your money on your smartphone with our Barclays Mobile Banking app.</p>
<div class="ui-cta-container el-align-left">
<a href="" data-track-type="PrimaryCTA" class="pri-button" title="Register" target="">Register
</a>
</div>
</div>

</div>
</div>

</div>
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
                         <?php get_sidebar("home"); ?>
                    </div>
            </div>
    </div> 
</div>
<?php get_footer(); ?>