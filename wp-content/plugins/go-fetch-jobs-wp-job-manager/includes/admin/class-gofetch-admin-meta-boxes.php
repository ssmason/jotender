<?php
/**
 * Sets up the write panels used by the schedules (custom post types).
 *
 * @package GoFetch/Admin/Meta Boxes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Schedules meta boxes base class.
 */
class GoFetch_WPJM_Schedule_Meta_Boxes{

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'rename_meta_boxes' ), 20 );
		add_action( 'admin_init', array( $this, 'add_meta_boxes' ), 30 );
	}

	/**
	 * Removes Meta boxes.
	 */
	public function remove_meta_boxes() {

		$remove_boxes = array( 'authordiv' );

		foreach ( $remove_boxes as $id ) {
			remove_meta_box( $id, GoFetch_WPJM()->post_type, 'normal' );
		}

	}

	/**
	 * Renames Meta boxes.
	 */
	public function rename_meta_boxes() {
		add_meta_box( 'authordiv', __( 'Job Listers', 'gofetch-wpjm' ) , array( $this, 'post_author_meta_box' ), GoFetch_WPJM()->post_type, 'side', 'low' );
	}

	/**
	 * Add Meta boxes.
	 */
	public function add_meta_boxes() {
		new GoFetch_WPJM_Schedule_Import_Meta_Box;
		new GoFetch_WPJM_Schedule_Cron_Meta_Box;
		new GoFetch_WPJM_Schedule_Period_Meta_Box;
	}

	/**
	 * Display custom form field with list of job listers.
	 */
	public function post_author_meta_box( $post ) {
		global $user_ID;

	?>
	<label class="screen-reader-text" for="post_author_override2"><?php _e( 'Job Lister', 'gofetch-wpjm' ); ?></label>
	<?php
		$job_listers_raw = array();
		$admins_raw      = get_users( array( 'role' => 'administrator' ) );

		$include = array();

		foreach( array_merge( $job_listers_raw, $admins_raw ) as $user ) {
			$include[] = $user->ID;
		}

		wp_dropdown_users( array( 'include' => implode( ',', $include ) ) );
	}

}

/**
 * The import settings meta box for the schedules.
 */
class GoFetch_WPJM_Schedule_Import_Meta_Box extends scbPostMetabox {

	/**
	 * Constructor.
	 */
	public function __construct() {

		parent::__construct( 'goft_wpjm-export', __( 'Import Template', 'gofetch-wpjm' ), array(
			'post_type' => GoFetch_WPJM()->post_type,
			'context'   => 'normal',
			'priority'  => 'high'
		) );

	}

	public function before_form( $post ) {
		echo __( 'Select the pre-defined template to use in the import process. The process will use the selected template setup for importing jobs to your database.', 'gofetch-wpjm' );
	}

	/**
	 * Meta box custom meta fields.
	 */
	public function form_fields(){
		global $goft_wpjm_options;

		if ( empty( $goft_wpjm_options->templates ) ) {
			$templates = array( '' => __( 'No templates found', 'gofetch-wpjm' ) );
		} else {
			$templates = array_keys( $goft_wpjm_options->templates );
		}

		return array(
			array(
				'title'   => __( 'Template Name', 'gofetch-wpjm' ),
				'type'    => 'select',
				'name'    => '_goft_wpjm_template',
				'choices' => $templates,
				'desc'    => sprintf( __( '<a href="%s">Create Template</a>', 'gofetch-wpjm' ), esc_url( add_query_arg( 'page', GoFetch_WPJM()->slug, 'admin.php' ) ) ),

			),
		);

	}

}

/**
 * The cron settings meta box for the schedules.
 */
class GoFetch_WPJM_Schedule_Cron_Meta_Box extends scbPostMetabox {

	/**
	 * Constructor.
	 */
	public function __construct() {

		parent::__construct( 'goft_wpjm-time', __( 'Schedule', 'gofetch-wpjm' ), array(
			'post_type' => GoFetch_WPJM()->post_type,
			'context'   => 'side',
		) );

	}

	public function after_form( $post ) {
		echo __( '<strong>Daily:</strong> Runs every day / <strong>Weekly:</strong> Runs every monday / <strong>Monthly:</strong> Runs on the 1st of each month', 'gofetch-wpjm' );
	}

	/**
	 * Meta box custom meta fields.
	 */
	public function form_fields(){

		return array(
			array(
				'title'   => __( 'Run Once Every...', 'gofetch-wpjm' ),
				'type'    => 'select',
				'name'    => '_goft_wpjm_cron',
				'choices' => array(
					'daily'   => __( 'Day', 'gofetch-wpjm' ),
					'weekly'  => __( 'Week', 'gofetch-wpjm' ),
					'monthly' => __( 'Month', 'gofetch-wpjm' ),
				),
			),
		);

	}

}

/**
 * The time period meta box for the schedules.
 */
class GoFetch_WPJM_Schedule_Period_Meta_Box extends scbPostMetabox {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( 'goft_wpjm-content-period', __( 'Content', 'gofetch-wpjm' ), array(
			'post_type' => GoFetch_WPJM()->post_type,
			'context'   => 'normal',
		) );
	}

	public function before_form( $post ) {
		echo __( 'Limit the content being imported by choosing the time period that should match the jobs being imported and the number of jobs to import every time this scheduled import runs.', 'gofetch-wpjm' );
	}

	/**
	 * Meta box custom meta fields.
	 */
	public function form_fields(){

		return array(
			array(
				'title'   => __( 'Jobs From...', 'gofetch-wpjm' ),
				'type'    => 'select',
				'name'    => '_goft_wpjm_period',
				'choices' => array(
					'today'  => __( 'Today', 'gofetch-wpjm' ),
					'custom' => __( 'Custom', 'gofetch-wpjm' ),
				),
				'extra' => array( 'id' => '_goft_wpjm_period' ),
			),
			array(
				'title' => __( 'Last...', 'gofetch-wpjm' ),
				'type'  => 'text',
				'name'  => '_goft_wpjm_period_custom',
				'extra' => array(
					'id'    => '_goft_wpjm_period_custom',
					'class' => 'small-text',
				),
				'desc' => __( 'days', 'gofetch-wpjm' ),
			),
			array(
				'title' => __( 'Limit', 'gofetch-wpjm' ),
				'type'  => 'text',
				'name'  => '_goft_wpjm_limit',
				'extra' => array(
					'class'     => 'small-text',
					'maxlength' => 5,
				),
				'desc' => __( 'job(s)', 'gofetch-wpjm' ) .
						  '<br/><br/>' .__( 'Leave empty to import all jobs found.', 'gofetch-wpjm' ),
			),
		);

	}

}

new GoFetch_WPJM_Schedule_Meta_Boxes;
