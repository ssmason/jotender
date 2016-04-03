<?php
abstract class BxSliderWp_Base {
    protected $plugin;
    
    final public function run( $plugin ) {
        $this->plugin = $plugin;
    }
}