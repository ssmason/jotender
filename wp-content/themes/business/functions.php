<?php
 
    // ADD POST THUMBNAIL SUPPORT
    add_theme_support( 'post-thumbnails' );

    // ADDED MENU FUNCTIONALITY
    // Main and Footer
//    function register_my_menus() {
//        register_nav_menus(
//            array(
//                'main-menu' => __( 'Main Menu' ),
////                'footer-membership-menu' => __( 'Membership Footer Menu' ),
////                'footer-more-info-menu' => __( 'More Info Footer Menu' )
//            )
//        );
//    }
//    add_action( 'init', 'register_my_menus' );

    // REMOVE WP EMOJI
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // remove junk from head
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
 


class Menu_With_Description extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';

        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';
        $attributes .= ! empty( $item->description ) ? ' data-description="' . esc_attr( $item->description ) .'"' : '';

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

 
add_action( 'admin_menu', 'url_list' );


function url_list() {
	add_options_page( 'List All URLs', 'List All URLs', 'manage_options', 'list-all-urls', 'generate_url_list' );
}


function generate_url_list() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
}
if(function_exists('register_nav_menus')){
	register_nav_menus(
		array(
			'main_nav' => 'Main Navigation Menu'
			)
	);
}


/*
 * Registers a widget area.
 *
 * @link 
 *
 * @since Business 1.0
 */
function business_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'home' ),
		'id'            => 'sidebar-home',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'business' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
        register_sidebar( array(
		'name'          => __( 'jobs' ),
		'id'            => 'sidebar-jobs',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'business' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
        register_sidebar( array(
		'name'          => __( 'header' ),
		'id'            => 'sidebar-header',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'business' ),
		'before_widget' => '',
		'after_widget'  => '',
	
	) );
	register_sidebar( array(
		'name'          => __( 'partners' ),
		'id'            => 'sidebar-partners',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'business' ),
		'before_widget' => '',
		'after_widget'  => '',
	
	) );
	 
}
add_action( 'widgets_init', 'business_widgets_init' );


// Enable the use of shortcodes in text widgets.
add_filter( 'widget_text', 'do_shortcode' );

 
 