<?php
/**
 * The template for the sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Business
 * @since Business 1.0
 */
?>


<?php if ( is_active_sidebar( 'sidebar-partners' )  ) : ?>
	<aside id="secondary" class="sidebar-partners widget-area" role="complementary">
            <?php dynamic_sidebar( 'sidebar-partners' ); ?>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>
