<?php
/**
 * Template Name: JOb registration
 *
 * @package WordPress
 * @subpackage Business
 * @since Business 1.0
 */

get_header(); ?>

	 

        
 <div class="content-wrapper">       
    
                
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
                            <div class="user-register"> 
                                <form method="post" action="">
                                    <fieldset>
                                    <legend>Personal:</legend>
                                    <label for="name">Forename</label>
                                    <input type="text" value="<?php echo $name; ?>" name="name" id="name"><br/>

                                    <label for="name">Surname</label>
                                    <input type="text" value="<?php echo $surname; ?>" name="surname" id="surname"><br/>

                                    <label for="name">DOB</label>
                                    <input type="text" value="<?php echo $dob; ?>" name="dob" id="dob"><br/>


                                    <label for="name">Location ( make this a map picker )</label>
                                    <input type="text" value="<?php echo $location; ?>" name="location" id="location"><br/>
                                    </fieldset>

                                   <fieldset>
                                      <legend>Login:</legend>
                                      <label for="username">Username</label>
                                      <input type="text" name="username" id="username"><br>
                                      <label for="username">Password</label>
                                      <input type="text" name="password" id="password"><br>
                                      
                                     </fieldset>

                                    
                                   <fieldset>
                                      <legend>Jobsearch:</legend>
                                      <label for="location">Location</label>
                                      <input type="text" name="location" id="location"><br>
                                      <label for="categories">Categories</label>
                                      <input type="text" name="categories" id="categories"><br>
                                       <label for="newsjobs">Receive to jobs by email</label>
                                      <input type="checkbox" name="newjobs" id="newjobs"><br>
                                     </fieldset>

                                    
                                    

                                    <input type="submit" name="submit" value="Register"><br/>

                                </form>
                            </div> 
                            
                            
                            
                            
                    </div>
                        
                
              
 </div>     
<?php get_footer(); ?>
