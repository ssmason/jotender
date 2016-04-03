<?php
/**
 * The template for the sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Business
 * @since Business 1.0
 */
?>


<?php if ( is_active_sidebar( 'sidebar-header' )  ) : ?>
	<aside id="secondary" class="sidebar-header widget-area" role="complementary">
		
            <ul class="user-controller">
                <li class="login"><a href="">login</a></li>
                <li class="logout"><a href="">logout</a></li>
                <li class="register"><a href="/register">Register</a></li>
                <li><?php dynamic_sidebar( 'sidebar-header' ); ?></li></ul>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>
