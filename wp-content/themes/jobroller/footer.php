<div id="footer">

	<div class="footer-widgets">

		<div class="inner">

			<?php foreach ( $footer_cols as $number ): ?>

				<?php $sidebar_id = "footer_col_{$number}"; ?>

				<div class="column">
					<div id="<?php echo esc_attr( $sidebar_id ); ?>">
						<?php dynamic_sidebar( $sidebar_id ); ?>
					</div>
				</div>

			<?php endforeach; ?>

			<div class="clear"></div>

			<div class="copyright">
				<a href="https://www.appthemes.com/themes/jobroller/" target="_blank" rel="nofollow">JobRoller Theme</a> - <?php _e('Powered by', APP_TD); ?> <a href="https://wordpress.org" target="_blank" rel="nofollow">WordPress</a>
			</div>

		</div><!-- end inner -->

	</div><!-- end footer-widgets -->

</div><!-- end footer -->
