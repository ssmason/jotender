<?php

if ( !function_exists( '_bc_after_scb_loaded' ) ) {
    require_once 'includes/wp-scb-framework/load.php';
    /**
     * Includes framework dependencies.
     */
    function _bc_after_scb_loaded()
    {
        require_once 'includes/utils.php';
        
        if ( is_admin() ) {
            require_once 'admin/class-tooltips.php';
            require_once 'admin/class-bc-admin-page.php';
            require_once 'admin/class-scb-tabs-page.php';
            require_once 'admin/about/class-bc-about.php';
        }
    
    }
    
    scb_init( '_bc_after_scb_loaded' );
}