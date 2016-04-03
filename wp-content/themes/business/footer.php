<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Business
 * @since Business 1.0
 */
?>

	</div><!-- .site-content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
                    <h5>Footer links here</h5>
			<?php
                            wp_nav_menu( array(
                                    'menu' => 'Footer',
                                    'menu_class'     => 'footer-menu',
                             ) );
                         ?>
			 </div><!-- .site-info -->
	</footer><!-- .site-footer -->

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>
