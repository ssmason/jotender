<?php if(!defined('ABSPATH')) die('Direct access denied.'); ?>
<div class="bxslider-cover"><p>Available in <a href="http://www.codefleet.net/bxslider-wp/">premium</a> version.</p></div>
<div class="bxslider-field">
	<label for="bxslider_options_pager"><?php _e('Pager:', 'bxslider'); ?> </label>
	<input type="hidden" name="bxslider[options][pager]" value="false" />
	<input id="bxslider_options_pager" type="checkbox" name="bxslider[options][pager]" value="true" <?php echo ($options['pager']=='true') ? 'checked="checked"' : ''; ?> />
	<label for="bxslider_options_pager" class="note"><?php _e('If checked, a pager will be added.', 'bxslider'); ?></label>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_pager_type"><?php _e('Pager Type:', 'bxslider'); ?></label>
	<select id="bxslider_options_pager_type" name="bxslider[options][pager_type]">
	<?php foreach($pager_type_options as $pager_type_option): ?>
		<option <?php echo esc_attr($pager_type_option['selected']); ?> value="<?php echo esc_attr($pager_type_option['value']); ?>"><?php echo esc_attr($pager_type_option['text']); ?></option>
	<?php endforeach; ?>
	</select>
	<span class="note"><?php _e('If "full", a pager link will be generated for each slide. If "short", a x / y pager will be used (ex. 1 / 5).', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field">
	<label for="bxslider_options_pager_short_separator"><?php _e('Pager Short Separator:', 'bxslider'); ?> </label>
	<input id="bxslider_options_pager_short_separator" type="text" name="bxslider[options][pager_short_separator]" value="<?php echo esc_attr($options['pager_short_separator']); ?>" />
	<span class="note"><?php _e('If "short", pager will use this value as the separating character.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
<div class="bxslider-field last">
	<label for="bxslider_options_pager_selector"><?php _e('Pager Selector:', 'bxslider'); ?> </label>
	<input id="bxslider_options_pager_selector" type="text" name="bxslider[options][pager_selector]" value="<?php echo esc_attr($options['pager_selector']); ?>" />
	<span class="note"><?php _e('Element used to populate the pager. By default, the pager is appended to the bx-viewport. Note: Use jQuery selectors.', 'bxslider'); ?></span>
	<div class="clear"></div>
</div>
