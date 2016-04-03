<?php
/**
 * The template for the sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Business
 * @since Business 1.0
 */
?>

<?php if ( is_active_sidebar( 'sidebar-home' )  ) : ?>
	<aside id="secondary" class="sidebar-home widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-home' ); ?>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>
 