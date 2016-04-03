<?php
 
require_once('includes/content_types.php');


add_action( 'init', 'create_location_taxonomy', 0 );


    function create_location_taxonomy() {

        
        $labels = array(
                'name' => _x( 'Facilities', 'taxonomy general name' ),
                'singular_name' => _x( 'Facility', 'taxonomy singular name' ),

                'search_items' =>  __( 'Search Facilities' ),
                'all_items' => __( 'All Facilities' ),
                'parent_item' => __( 'Parent Facilities' ),
                'parent_item_colon' => __( 'Parent Facilities:' ),
                'edit_item' => __( 'Edit Facility' ),
                'update_item' => __( 'Update Facility' ),
                'add_new_item' => __( 'Add New Facility' ),
                'new_item_name' => __( 'New Facility Name' ),
                'menu_name' => __( 'Facilities' ),
              );


              register_taxonomy('facilities',array('courses'), array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'locations' ),
              ));
        

        $labels = array(
                'name' => _x( 'Locations', 'taxonomy general name' ),
                'singular_name' => _x( 'Location', 'taxonomy singular name' ),

                'search_items' =>  __( 'Search Locations' ),
                'all_items' => __( 'All Locations' ),
                'parent_item' => __( 'Parent Location' ),
                'parent_item_colon' => __( 'Parent Location:' ),
                'edit_item' => __( 'Edit Location' ),
                'update_item' => __( 'Update Location' ),
                'add_new_item' => __( 'Add New Location' ),
                'new_item_name' => __( 'New Location Name' ),
                'menu_name' => __( 'Locations' ),
              );


              register_taxonomy('locations',array('courses'), array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'locations' ),
              ));

  $labels = array(
                'name' => _x( 'Locations', 'taxonomy general name' ),
                'singular_name' => _x( 'Location', 'taxonomy singular name' ),

                'search_items' =>  __( 'Search Locations' ),
                'all_items' => __( 'All Locations' ),
                'parent_item' => __( 'Parent Location' ),
                'parent_item_colon' => __( 'Parent Location:' ),
                'edit_item' => __( 'Edit Location' ),
                'update_item' => __( 'Update Location' ),
                'add_new_item' => __( 'Add New Location' ),
                'new_item_name' => __( 'New Location Name' ),
                'menu_name' => __( 'Locations' ),
              );


              register_taxonomy('holiday_locations',array('holidays'), array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'holiday-locations' ),
              ));

 
    }


// Creating the widget 
class wpb_top3 extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'wpb_top3', 

// Widget name will appear in UI
__('Top 3 Green Fee Discounts', 'wpb_widget_domain'), 

// Widget description
array( 'description' => __( 'Widget to display Top 3 Green Fee offers', 'wpb_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
echo __( 'Hello, World!', 'wpb_widget_domain' );
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'wpb_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_top3' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
 
?>
