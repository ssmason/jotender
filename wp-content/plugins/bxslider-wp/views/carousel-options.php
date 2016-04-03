<?php if(!defined('ABSPATH')) die('Direct access denied.'); ?>
<div class="bxslider-cover"><p>Available in <a href="http://www.codefleet.net/bxslider-wp/">premium</a> version.</p></div>
<div class="bxslider-field">
	<label for="bxslider_options_min_slides"><?php _e('Min Slides:', 'bxslider'); ?> </label>
	<input id="bxslider_options_min_slides" type="number" name="bxslider[options][min_slides]" value="<?php echo esc_attr($options['min_slides']); ?>" />
	<span class="note"><?php _e('The minimum number of slides to be shown. Slides will be sized down if carousel becomes smaller than the original size.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_max_slides"><?php _e('Max Slides:', 'bxslider'); ?> </label>
	<input id="bxslider_options_max_slides" type="number" name="bxslider[options][max_slides]" value="<?php echo esc_attr($options['max_slides']); ?>" />
	<span class="note"><?php _e('The maximum number of slides to be shown. Slides will be sized up if carousel becomes larger than the original size.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_move_slides"><?php _e('Move Slides:', 'bxslider'); ?> </label>
	<input id="bxslider_options_move_slides" type="number" name="bxslider[options][move_slides]" value="<?php echo esc_attr($options['move_slides']); ?>" />
	<span class="note"><?php _e('The number of slides to move on transition. This value must be ">= minSlides", and "<= maxSlides". If zero (default), the number of fully-visible slides will be used.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field last">
	<label for="bxslider_options_slide_width"><?php _e('Slide Width:', 'bxslider'); ?> </label>
	<input id="bxslider_options_slide_width" type="number" name="bxslider[options][slide_width]" value="<?php echo esc_attr($options['slide_width']); ?>" />
	<span class="note"><?php _e('The width of each slide. This setting is required for all horizontal carousels!', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
