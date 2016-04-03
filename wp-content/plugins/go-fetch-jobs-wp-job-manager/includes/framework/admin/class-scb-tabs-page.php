<?php

/**
 * Tabs.
 *
 * @package Framework\Tabs
 */

if ( !class_exists( 'bc_scbTabsPage' ) ) {
    require dirname( __FILE__ ) . '/class-scb-list.php';
    abstract class bc_scbTabsPage extends bcAdminPage
    {
        public  $tabs ;
        public  $tab_sections ;
        protected abstract function init_tabs();
        
        public function __construct( $options = null )
        {
            parent::__construct( false, $options );
            $this->tabs = new bc_scbList();
        }
        
        public function page_loaded()
        {
            $this->init_tabs();
            do_action( 'tabs_' . $this->pagehook, $this );
            parent::page_loaded();
        }
        
        public function form_handler()
        {
            if ( empty($_POST['action']) || !$this->tabs->contains( $_POST['action'] ) ) {
                return;
            }
            check_admin_referer( $this->nonce );
            $form_fields = array();
            foreach ( $this->tab_sections[$_POST['action']] as $section ) {
                $form_fields = array_merge( $form_fields, $section['fields'] );
            }
            $to_update = scbForms::validate_post_data( $form_fields, null, $this->options->get() );
            $this->options->update( $to_update );
            $this->admin_msg();
            do_action( 'tabs_' . $this->pagehook . '_form_handler', $this, $this->options );
        }
        
        public function page_content()
        {
            do_action( 'tabs_' . $this->pagehook . '_page_content', $this, $this->options );
            $active_tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : '' );
            $tabs = $this->tabs->get_all();
            if ( !isset( $tabs[$active_tab] ) ) {
                $active_tab = key( $tabs );
            }
            $current_url = scbUtil::get_current_url();
            echo  '<h3 class="nav-tab-wrapper">' ;
            foreach ( $tabs as $tab_id => $tab_title ) {
                $class = 'nav-tab';
                if ( $tab_id == $active_tab ) {
                    $class .= ' nav-tab-active';
                }
                $href = esc_url( add_query_arg( 'tab', $tab_id, $current_url ) );
                echo  ' ' . html( 'a', compact( 'class', 'href' ), $tab_title ) ;
            }
            echo  '</h3>' ;
            $this->before_form( $active_tab );
            echo  '<form method="post" action="">' ;
            echo  '<input type="hidden" name="action" value="' . $active_tab . '" />' ;
            wp_nonce_field( $this->nonce );
            $fields = array();
            foreach ( $this->tab_sections[$active_tab] as $section_id => $section ) {
                if ( isset( $section['title'] ) ) {
                    echo  html( 'h3', $section['title'] ) ;
                }
                $this->before_tab_section( $section_id );
                
                if ( isset( $section['renderer'] ) ) {
                    call_user_func( $section['renderer'], $section, $section_id );
                } else {
                    $this->render_section( $section['fields'] );
                }
                
                if ( !empty($section['fields']) ) {
                    $fields[] = $section['fields'];
                }
            }
            echo  '</form>' ;
        }
        
        private function render_section( $fields )
        {
            $output = '';
            foreach ( $fields as $field ) {
                $output .= $this->table_row( $this->before_rendering_field( $field ) );
            }
            echo  $this->table_wrap( $output ) ;
        }
        
        /**
         * Useful for adding dynamic descriptions to certain fields.
         *
         * @param array field arguments
         * @return array modified field arguments
         */
        protected function before_rendering_field( $field )
        {
            return $field;
        }
    
    }
}