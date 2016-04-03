<?php if(!defined('ABSPATH')) die('Direct access denied.'); ?>
<div class="bxslider-cover"><p>Available in <a href="http://www.codefleet.net/bxslider-wp/">premium</a> version.</p></div>
<div class="bxslider-field">
	<label for="bxslider_options_controls"><?php _e('Controls:', 'bxslider'); ?> </label>
	<input type="hidden" name="bxslider[options][controls]" value="false" />
	<input id="bxslider_options_controls" type="checkbox" name="bxslider[options][controls]" value="true" <?php echo ($options['controls']=='true') ? 'checked="checked"' : ''; ?> />
	<label for="bxslider_options_controls" class="note"><?php _e('If checked, "Next" / "Prev" controls will be added.', 'bxslider'); ?></label>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_next_text"><?php _e('Next Text:', 'bxslider'); ?> </label>
	<input id="bxslider_options_next_text" type="text" name="bxslider[options][next_text]" value="<?php echo esc_attr($options['next_text']); ?>" />
	<span class="note"><?php _e('Text to be used for the "Next" control.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_prev_text"><?php _e('Prev Text:', 'bxslider'); ?> </label>
	<input id="bxslider_options_prev_text" type="text" name="bxslider[options][prev_text]" value="<?php echo esc_attr($options['prev_text']); ?>" />
	<span class="note"><?php _e('Text to be used for the "Prev" control.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_next_selector"><?php _e('Next Selector:', 'bxslider'); ?> </label>
	<input id="bxslider_options_next_selector" type="text" name="bxslider[options][next_selector]" value="<?php echo esc_attr($options['next_selector']); ?>" />
	<span class="note"><?php _e('Element used to populate the "Next" control.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_prev_selector"><?php _e('Prev Selector:', 'bxslider'); ?> </label>
	<input id="bxslider_options_prev_selector" type="text" name="bxslider[options][prev_selector]" value="<?php echo esc_attr($options['prev_selector']); ?>" />
	<span class="note"><?php _e('Element used to populate the "Prev" control.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_auto_controls"><?php _e('Auto Controls:', 'bxslider'); ?> </label>
	<input type="hidden" name="bxslider[options][auto_controls]" value="false" />
	<input id="bxslider_options_auto_controls" type="checkbox" name="bxslider[options][auto_controls]" value="true" <?php echo ($options['auto_controls']=='true') ? 'checked="checked"' : ''; ?> />
	<label for="bxslider_options_auto_controls" class="note"><?php _e('If checked, "Start" / "Stop" controls will be added.', 'bxslider'); ?></label>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_start_text"><?php _e('Start Text:', 'bxslider'); ?> </label>
	<input id="bxslider_options_start_text" type="text" name="bxslider[options][start_text]" value="<?php echo esc_attr($options['start_text']); ?>" />
	<span class="note"><?php _e('Text to be used for the "Start" control.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_stop_text"><?php _e('Stop Text:', 'bxslider'); ?> </label>
	<input id="bxslider_options_stop_text" type="text" name="bxslider[options][stop_text]" value="<?php echo esc_attr($options['stop_text']); ?>" />
	<span class="note"><?php _e('Text to be used for the "Stop" control.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_auto_controls_combine"><?php _e('Auto Controls Combine:', 'bxslider'); ?> </label>
	<input type="hidden" name="bxslider[options][auto_controls_combine]" value="false" />
	<input id="bxslider_options_auto_controls_combine" type="checkbox" name="bxslider[options][auto_controls_combine]" value="true" <?php echo ($options['auto_controls_combine']=='true') ? 'checked="checked"' : ''; ?> />
	<label for="bxslider_options_auto_controls_combine" class="note"><?php _e('When slideshow is playing only "Stop" control is displayed and vice-versa.', 'bxslider'); ?></label>
	<div class="clear"></div>
</div>
<div class="bxslider-field last">
	<label for="bxslider_options_auto_controls_selector"><?php _e('Auto Controls Selector:', 'bxslider'); ?> </label>
	<input id="bxslider_options_auto_controls_selector" type="text" name="bxslider[options][auto_controls_selector]" value="<?php echo esc_attr($options['auto_controls_selector']); ?>" />
	<span class="note"><?php _e('Element used to populate the auto controls.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
