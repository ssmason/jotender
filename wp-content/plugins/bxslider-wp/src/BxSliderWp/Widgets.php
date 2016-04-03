<?php
/**
* Class for initializing widgets
*/
class BxSliderWp_Widgets extends BxSliderWp_Base {

    /**
     * Initialize
     */
    public function bootstrap() {
        add_action('widgets_init', array( $this, 'register_widgets') );
    }
    
    /**
     * Register to WP
     */
    public function register_widgets(){
        register_widget('BxSliderWp_WidgetSlider');
    }
    
} // end class