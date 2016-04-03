<?php
/**
 * Provides and outputs all the fields used on the import page.
 *
 * @package GoFetch/Admin/Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $goft_wpjm_options;

/**
 * Settings Admin class.
 */
class GoFetch_WPJM_Admin_Settings extends bcAdminPage {

	private static $imported = 0;

	/**
	 * Setup the Import page.
	 */
	public function setup() {

		$this->args = array(
			'page_title'    => __( 'Import Jobs', 'gofetch-wpjm' ),
			'page_slug'     => 'go-fetch-jobs-wp-job-manager',
			'parent'        => 'options.php',
			'menu_title'    => __( 'Go Fetch Jobs', 'gofetch-wpjm' ),
			'submenu_title' => __( 'Import Jobs', 'gofetch-wpjm' ),
			'toplevel'      => 'menu',
		);

		add_action( 'wp_ajax_goft_wpjm_save_template', array( $this, 'ajax_save_template' ) );
		add_action( 'admin_init', array( $this, 'init_tooltips' ), 9999 );
	}

	/**
	 * Load tooltips for the current screen.
	 * Avoids loading multiple tooltip instances on metaboxes.
	 */
	public function init_tooltips() {
		new BC_ToolTips( array( 'toplevel_page_gofetch' ) );
	}

	/**
	 * The settings for the Import page.
	 */
	public function page_content() {
		global $goft_wpjm_options;

		if ( get_option( 'goft-wpjm-error' ) ) {
			return;
		}

		$this->init_tooltips();

		$fetch_images_tip = html( 'span', array(
			'class'        => 'dashicons-before dashicons-editor-help tip-icon bc-tip',
			'data-tooltip' => BC_ToolTips::supports_wp_pointer() ? __( 'Please note that some RSS feed providers output their own logo as the companies logos. You can try loading the RSS feed with this option unchecked first and look at the sample table to verify this.', 'gofetch-wpjm' ) : __( 'Click for more info', 'gofetch-wpjm' ),
		) );

		$force_feed_tip = html( 'span', array(
			'class'        => 'dashicons-before dashicons-editor-help tip-icon bc-tip',
			'data-tooltip' => BC_ToolTips::supports_wp_pointer() ? __( 'Check this option and try loading the feed again if you\'re sure the feed is valid (not guaranteed to always work).', 'gofetch-wpjm' ) : __( 'Click for more info', 'gofetch-wpjm' ),
		) );

		echo html( 'p', __( 'From this page you can import any RSS jobs feed to your jobs database. Select an RSS feed provider from the <em>Providers</em> dropdown or user your own RSS feed.', 'gofetch-wpjm' ) .
			      ' ' . __( 'If you have trouble with any RSS feed make sure they are working and retrieving jobs by copy & pasting the URL on our web browser (you should be able to see a structured XML file with jobs inside).', 'gofetch-wpjm' ) );

		echo html( 'p', __( 'It is recommended to import targeted/categorized RSS feeds, meaning, for a specific job category, location, etc, or filtered by some criteria instead of a big generic RSS feed, for better control of the jobs being imported.', 'gofetch-wpjm' ) );

		if ( gfjwjm_fs()->is_not_paying() ) {
			echo scb_admin_notice( sprintf( html( 'span class="dashicons dashicons-warning"', '&nbsp;' ) . ' ' . __( 'If you need further features like more RSS providers, a custom RSS feed builder, featuring imported jobs, schedule imports, and more, please upgrade to a <a href="%1$s">premium plan</a>.', 'gofetch-wpjm' ), esc_url( gfjwjm_fs()->get_upgrade_url() ) ) );
		}


		$fields = array(
			array(
				'title'         => '',
				'name'          => '_blank',
				'type'          => 'custom',
				'section_break' => true,
				'render'        => array( $this, 'section_break' ),
			),
			array(
				'title' => __( 'Template', 'gofetch-wpjm' ),
				'type'  => 'select',
				'name'  => 'templates_list',
				'extra' => array(
					'id' => 'templates_list',
				),
				'choices'  => array_keys( $goft_wpjm_options->templates ),
				'selected' => ! empty( $_POST['templates_list'] ) ? $_POST['templates_list'] : '',
				'desc'     => html( 'input', array( 'type' => 'submit', 'name' => 'refresh_templates', 'class' => 'refresh-templates button-secondary', 'value' => __( 'Refresh', 'gofetch-wpjm' ) ) ) . ' ' .
						      html( 'input', array( 'type' => 'submit', 'name' => 'delete_template', 'class' => 'button-secondary', 'disabled' => true, 'value' => __( 'Delete', 'gofetch-wpjm' ) ) ),
				'text'     => __( '-- Feed Template --', 'gofetch-wpjm' ),
				'tip'      => __( 'The list of all your saved feed templates. Choosing an existing template automatically loads the related RSS feed and setup.', 'gofetch-wpjm' ),
			),
			array(
				'title'  => __( 'Providers', 'gofetch-wpjm' ),
				'type'   => 'custom',
				'name'   => '_blank',
				'render' => array( $this, 'provider_helper_dropdown' ),
				'tip'    => __( 'A list with some of the most popular job sites that offer jobs via RSS feeds. Click a provider to view more details and instructions on how to use the RSS feed.', 'gofetch-wpjm' ) .
								( gfjwjm_fs()->is_not_paying() ? html( 'p', html( 'code', html( 'span class="dashicons dashicons-warning"', '&nbsp;' ) .  ' ' . __( 'Premium plans include a bigger list and an RSS feed builder for select providers.' ) ) ) : '' ),
				'render' => array( $this, 'provider_helper_dropdown' ),
			),
			array(
				'title'  => '',
				'name'   => '_blank',
				'type'   => 'custom',
				'render' => array( $this, 'section_providers_placeholder' ),
				'tr'     => 'tr-hide',
			),
			array(
				'title' => __( 'URL', 'gofetch-wpjm' ),
				'type'  => 'text',
				'name'  => 'rss_feed_import',
				'extra' => array(
					'class'       => 'regular-text2',
					'placeholder' => 'e.g: http://jobs.wordpress.net/feed/?s=developer',
				),
				'tip'      => __( 'An RSS feed URL with job listings that you wish to import to your site. Make sure you use a targeted RSS feed instead of a generic feed.', 'gofetch-wpjm' ) .
							  __( '<br/><br/><code>GOOD - http://jobs.wordpress.net/feed/?s=developer (filtered results)</code> <br/><code>BAD - http://jobs.wordpress.net/feed (bulk results)</code>', 'gofetch-wpjm' ),
				'desc'     => html( 'input', array( 'type' => 'submit', 'name' => 'import_feed', 'class' => 'import-feed button-primary', 'value' => __( 'Load', 'gofetch-wpjm' ) ) ) .
							  html( 'p', html( 'input', array( 'type' => 'checkbox', 'name' => 'load_images', 'class' => 'rss-load-images', 'value' => 1 ) ) . __( 'Fetch job companies logos, if available (slower - may take several minutes on big RSS feeds)' . ' ' . $fetch_images_tip, 'gofetch-wpjm' ) ) .
							  html( 'p class="force-feed"', html( 'input', array( 'type' => 'checkbox', 'name' => 'force_feed', 'value' => 1 ) ) . __( 'Force Feed', 'gofetch-wpjm' ) . ' ' . $force_feed_tip ),
				'value'    => ( ! empty( $_POST['rss_feed_import'] ) ? $_POST['rss_feed_import'] : '' ),
			),
			array(
				'title'  => '',
				'name'   => '_blank',
				'type'   => 'custom',
				'render' => array( $this, 'placeholder' ),
			),
			array(
				'title'  => __( 'Sample', 'gofetch-wpjm' ),
				'name'   => '_blank',
				'type'   => 'custom',
				'render' => array( $this, 'table_fields_title' ),
				'tr'     => 'temp-tr-hide',
			),
			array(
				'title'  => '',
				'name'   => '_blank',
				'type'   => 'custom',
				'render' => array( $this, 'output_sample_table' ),
				'tr'     => 'temp-tr-hide',
			),
			array(
				'title'         => '',
				'name'          => '_blank',
				'type'          => 'custom',
				'section_break' => true,
				'render'        => array( $this, 'section_break' ),
				'tr'            => 'temp-tr-hide',
			),
			array(
				'title' => __( 'Name', 'gofetch-wpjm' ),
				'type'  => 'text',
				'name'  => 'source[name]',
				'extra' => array(
					'class'       => 'regular-text',
					'placeholder' => 'e.g: Monster Jobs',
					'section'     => 'source',
				),
				'tip'   => __( 'The feed source name (e.g: Monster Jobs).', 'gofetch-wpjm' ),
				'value' => ( ! empty( $_POST['source[name]'] ) ? $_POST['source[name]'] : '' ),
				'desc'  => html( 'span', array( 'class' => "wp-ui-text-highlight reset-val", 'data-parent' => 'source[name]', 'title' => esc_attr( __( 'Revert to default value.', 'gofetch-wpjm' ) ) ), html( 'span', array( 'class' => "dashicons dashicons-image-rotate" ) ) ),
				'tr'    => 'tr-hide',
			),
			array(
				'title' => __( 'Website', 'gofetch-wpjm' ),
				'type'  => 'text',
				'name'  => 'source[website]',
				'extra' => array(
					'class'       => 'regular-text',
					'placeholder' => 'e.g: www.monster.com',
					'section'     => 'source',
				),
				'tip'   => __( 'The feed source URL (e.g: www.monster.com).', 'gofetch-wpjm' ),
				'value' => ( ! empty( $_POST['source[url]'] ) ? $_POST['source[url]'] : '' ),
				'desc'  => html( 'span', array( 'class' => "wp-ui-text-highlight reset-val", 'data-parent' => 'source[website]', 'title' => esc_attr( __( 'Revert to default value.', 'gofetch-wpjm' ) ) ), html( 'span', array( 'class' => "dashicons dashicons-image-rotate" ) ) ),
				'tr'    => 'tr-hide',
			),
			array(
				'title'  => __( 'Logo', 'gofetch-wpjm' ),
				'name'   => 'source[logo]',
				'type'   => 'custom',
				'tip'    => __( "Specify an image URL here to display the jobs source site logo instead of only the site name. It's recommend that you use a local image so you can resize it accordingly.", 'gofetch-wpjm' ),
				'render' => array( $this, 'provider_logo_uploader' ),
				'tr'     => 'tr-hide',
			),
		);

		$monetize = array(
			array(
				'title'         => '',
				'name'          => '_blank',
				'type'          => 'custom',
				'section_break' => true,
				'render'        => array( $this, 'section_break' ),
				'tr'            => 'temp-tr-hide',
			),
			array(
				'title'  => __( 'URL Parameters', 'gofetch-wpjm' ),
				'name'   => '_blank',
				'type'   => 'custom',
				'render' => array( $this, 'output_affiliate_param' ),
				'tr'     => 'temp-tr-hide',
			),
		);

		if ( gfjwjm_fs()->is_not_paying() ) {
			$monetize = array();
		}

		$fields = array_merge( $fields, $monetize, array(
			array(
				'title'         => '',
				'name'          => '_blank',
				'type'          => 'custom',
				'section_break' => true,
				'render'        => array( $this, 'section_break' ),
				'tr'            => 'temp-tr-hide',
			),
			array(
				'title'         => '',
				'name'          => '_blank',
				'type'          => 'custom',
				'section_break' => true,
				'render'        => array( $this, 'section_break' ),
				'section'       => 'taxonomies',
				'tr'            => 'temp-tr-hide',
			),
			array(
				'title'         => '',
				'name'          => '_blank',
				'type'          => 'custom',
				'section_break' => true,
				'render'        => array( $this, 'section_break' ),
				'section'       => 'meta',
				'tr'            => 'temp-tr-hide',
			),
			array(
				'title'         => '',
				'name'          => '_blank',
				'type'          => 'custom',
				'section_break' => true,
				'render'        => array( $this, 'section_break' ),
				'tr'            => 'temp-tr-hide',
			),
			array(
				'title'  => __( 'Name', 'gofetch-wpjm' ),
				'name'   => 'job_author',
				'type'   => 'custom',
				'render' => array( $this, 'output_job_listers' ),
				'tip'    => __( 'Choose the user to be assigned to the imported jobs. This user will only be assigned to the jobs your are currently importing. It is not saved in templates.', 'gofetch-wpjm' ),
				'tr'     => 'temp-tr-hide',
			),
			array(
				'title'         => '',
				'name'          => '_blank',
				'type'          => 'custom',
				'section_break' => true,
				'render'        => array( $this, 'section_break' ),
				'tr'            => 'temp-tr-hide',
			),
			array(
				'title'  => __( 'Date Span', 'gofetch-wpjm' ),
				'name'   => '_blank',
				'type'   => 'custom',
				'render' => array( $this, 'output_date_span' ),
				'tip'    => __( 'Choose a date interval to filter the job feed results. The date internal is only applied to the jobs your are currently importing. It is not saved in templates.', 'gofetch-wpjm' ),
				'tr'     => gfjwjm_fs()->is_plan__premium_only( 'proplus', true ) ? 'temp-tr-hide' : 'tr-hide',
			),
			/*
			array(
				'title' => __( 'Keywords', 'gofetch-wpjm' ),
				'name'  => 'keywords',
				'type'  => 'text',
				'extra' => array(
					'class'     => 'regular-text2',
					'maxlength' => 5,
				),
				'tip'   => __( 'Use this field as an additional criteria to make sure jobs contains specific keywords.', 'gofetch-wpjm' ),
				'desc'  => __( 'Comma separated list of keywords that should be in each job content.', 'gofetch-wpjm' ),
				'value' => 	! empty( $_POST['limit'] ) ? wp_strip_all_tags( $_POST['limit'] ) : '',
			),*/
			array(
				'title' => __( 'Limit', 'gofetch-wpjm' ),
				'name'  => 'limit',
				'type'  => 'text',
				'extra' => array(
					'class'     => 'small-text',
					'maxlength' => 5,
				),
				'tip'     => __( 'Choose the number of jobs to import (leave empty to import all the jobs in the feed - not recommended on large RSS feeds).', 'gofetch-wpjm' ),
				'value'   => 	! empty( $_POST['limit'] ) ? wp_strip_all_tags( $_POST['limit'] ) : '',
				'tr'      => 'temp-tr-hide',
				'default' => 20,
			),
			array(
				'title'         => '',
				'name'          => '_blank',
				'type'          => 'custom',
				'section_break' => true,
				'render'        => array( $this, 'section_break' ),
				'tr'            => 'temp-tr-hide',
			),
			array(
				'title' => __( 'Template', 'gofetch-wpjm' ),
				'type'  => 'text',
				'name'  => 'template_name',
				'extra' => array(
					'style' => 'width: 312px',
					'class' => 'field_dependent',
				),
				'desc' => html( 'a', array( 'class' => 'save-template button button-primary field_dependent' ), __( 'Save', 'gofetch-wpjm' ) ),
				'tip'  => __( 'Specify a template name and and save your import setup. Templates can be loaded later and can also be used on scheduled imports.', 'gofetch-wpjm' )
							 . '<br/>' . __( 'Some template name examples: my fulltime jobs, my tech jobs, my big salary jobs, etc.', 'gofetch-wpjm' ),
				'value'  => ! empty( $_POST['template_name'] ) ? wp_strip_all_tags( $_POST['template_name'] ) : 'my-rss-feed',
 				'tr'     => 'temp-tr-hide',
			),
			array(
				'title'         => '',
				'name'          => '_blank',
				'type'          => 'custom',
				'section_break' => true,
				'render'        => array( $this, 'section_break' ),
				'tr'            => 'temp-tr-hide',
			),
		) );

		$taxonomies = $this->page_content_taxonomies();
		$tax_pos = BC_Utils::list_find_pos( $fields, array( 'section' => 'taxonomies' ) ) + 1;

		$fields = array_merge( array_slice( $fields, 0, $tax_pos, true ), $taxonomies, array_slice( $fields, $tax_pos, count( $fields ) - 1, true ) );

		$meta_fields = $this->page_content_meta_fields();
		$meta_pos = BC_Utils::list_find_pos( $fields, array( 'section' => 'meta' ) ) + 1;

		$fields = array_merge( array_slice( $fields, 0, $meta_pos, true ), $meta_fields, array_slice( $fields, $meta_pos, count( $fields ) - 1, true ) );

		echo $this->form_table( $fields );
	}

	/**
	 * Retrieves the main taxonomies related with the the 'job_listing' post type.
	 */
	protected function page_content_taxonomies() {

		$taxonomies = get_object_taxonomies( GoFetch_WPJM()->parent_post_type, 'objects' );

		$fields = array();

		// Provide compatibility for objects in anonymous functions (PHP < 5.3).
		$o_this = $this;

		foreach( $taxonomies as $taxonomy ) {

			$render = function() use ( $o_this, $taxonomy ) {
			    return $o_this->output_taxonomy( $taxonomy );
			};

			$fields[] = array(
				'title'  => $taxonomy->label,
				'name'   => '_blank',
				'type'   => 'custom',
				'render' => $render,
				'tip'    => sprintf( __( 'The %s for the jobs being imported.', 'gofetch-wpjm' ), $taxonomy->label ),
				'tr'     => 'tr-hide',
			);

		}
		return $fields;
	}

	/**
	 * Retrieves all the meta fields available for the 'job_listing' post type.
	 */
	protected function page_content_meta_fields() {

		$fields = apply_filters( 'goft_wpjm_meta_fields', array() );

		$defaults = array(
			'title' => '',
			'name'  => '',
			'type'  => 'text',
			'extra' => array(
				'section' => 'meta',
				'default' => '',
			),
			'tr' => 'tr-hide',
		);

		$final_fields = array();

		foreach ( $fields as $field ) {

			if ( ! empty( $field['type'] ) && 'checkbox' === $field['type'] ) {
				$defaults['extra']['class'] = '';
			} else {
				$defaults['extra']['class'] = 'regular-text';
			}

			$new_field = wp_parse_args( $field, $defaults );
			if ( ! empty( $field['extra'] ) ) {
				$new_field['extra'] = wp_parse_args( $field['extra'], $defaults['extra'] );
			}

			// Automatically set the default value.
			if ( empty( $new_field['extra']['default'] ) ) {
				preg_match_all( "/\[([^\]]*)\]/", $field['name'], $field_name );
				if ( ! empty( $field_name[1][0] ) ) {
					$new_field['extra']['default'] = $this->get_default_value_for_meta( $field_name[1][0] );
				}
			}

			$final_fields[] = $new_field;
		}

		return $final_fields;
	}

	/**
	 *
	 */
	public function provider_helper_dropdown() {

		$atts =	array(
			'name' => 'providers_list',
			'id'   => 'providers_list',
		);
		return GoFetch_WPJM_RSS_Providers::output_providers_dropdown( $atts );
	}

	/**
	 * Renders the provider logo uploader field.
	 */
	public function provider_logo_uploader() {

		$field = array(
			'name'  => 'source[logo]',
			'type'  => 'text',
			'extra' => array(
				'class'       => 'goft-source-logo goft-image regular-text',
				'placeholder' => 'e.g: www.my-jobs-site/uploads/monster-logo.png',
				'section'     => 'source',
			),
			'tip'   => __( "Specify an image URL here to display the jobs source site logo instead of only the site name. It's recommend that you use a local image so you can resize it accordingly.", 'gofetch-wpjm' ),
			'value' => ( ! empty( $_POST['source[logo]'] ) ? $_POST['source[logo]'] : '' ),
			'desc'  => html( 'span', array( 'class' => "wp-ui-text-highlight reset-val", 'data-parent' => 'source[logo]', 'title' => esc_attr( __( 'Revert to default value.', 'gofetch-wpjm' ) ) ), html( 'span', array( 'class' => "dashicons dashicons-image-rotate" ) ) ) .
					   html( 'a', array( 'class' => 'goft-source-logo goft-upload button-secondary' ), __( 'Browse...', 'gofetch-wpjm' ) ),
		);

		return $this->image_uploader( $field, 'goft-source-logo' );
	}

	/**
	 * Outputs an image uploader field;
	 */
	public function image_uploader( $field, $class ) {

		if ( function_exists( 'wp_enqueue_media' ) ) {
		    wp_enqueue_media();
		} else {
		    wp_enqueue_style( 'thickbox' );
		    wp_enqueue_script( 'media-upload' );
		    wp_enqueue_script( 'thickbox' );
		}

		ob_start();

		echo html( 'img', array( 'class' => "$class", 'src' => '' ) );

		echo $this->input( $field );
?>
		<script>
		    jQuery(document).ready(function($) {

		        $( ".goft-upload.<?php echo $class; ?>" ).on( 'click', function(e) {
		            e.preventDefault();

		            var custom_uploader = wp.media({
		                title: "<?php echo __( 'Custom Image' , 'gofetch-wpjm' ); ?>",
		                button: {
		                    text: "<?php echo __( 'Upload Image' , 'gofetch-wpjm' ); ?>"
		                },
		                multiple: false  // Set this to true to allow multiple files to be selected
		            })
		            .on('select', function() {
		                var attachment = custom_uploader.state().get('selection').first().toJSON();
		                $( "input[name='<?php echo esc_attr( $field['name'] ); ?>']" ).val( attachment.url ).trigger('change');
		            })
		            .open();
		        });

				$( "input[name='<?php echo esc_attr( $field['name'] ); ?>']" ).on( 'change', function(e) {

						$( "img.<?php echo $class; ?>" ).attr( 'src', $(this).val() );

						if ( $(this).val() ) {
							$( "img.<?php echo $class; ?>" ).show();
						} else {
							$( "img.<?php echo $class; ?>" ).hide();
						}

				});


		    });
		</script>
<?php
		return ob_get_clean();
	}

	/**
	 * Retrieves the key/value mapping for the geolocation meta keys and the geocomplete 'data-geo' helper fields.
	 */
	public static function get_geocomplete_hidden_fields() {
		return apply_filters( 'goft_wpjm_geocomplete_hidden_fields', array() );
	}

	/**
	 * Output hidden gecocomplete meta fields.
	 */
	public static function get_geocomplete_fields() {

		$hidden_fields = '';

		foreach ( self::get_geocomplete_hidden_fields() as $att => $location_att ) {
			$meta_key = "meta[{$att}]";
			$hidden_fields .= html( 'input', array( 'type' => 'hidden', 'name' => esc_attr( $meta_key ), 'data-geo' => esc_attr( $location_att ), 'value' => '' ) );
		}

		return html( 'div', array( 'class' => 'custom-location' ), $hidden_fields );
	}

	/**
	 * Outputs the placeholder that the user sees before the RSS feed is loaded.
	 */
	public function placeholder() {
		return html( 'p class="import-notes"', html( 'strong', __( '- Please load an existing feed template or an RSS feed to continue with the import process -', 'gofetch-wpjm' ) ) );
	}

	/**
	 * Outputs the sample fields table title.
	 */
	public function table_fields_title() {
		return html( 'p', __( 'The table below shows all the available fields for the loaded RSS Feed, and a sample of the content inside each field.', 'gofetch-wpjm' ) );
	}

	// _Sections.

	/**
	 * The sections titles.
	 */
	public function section_title( $section ) {

		$title_desc = '';

		$i = 1;

		$title_desc[ $i++ ] = array(
			'title'  => __( 'RSS Feed', 'gofetch-wpjm' ),
			'header' => 'h2 style="font-style: italic;"',
		);

		$title_desc[ $i++ ] = array(
			'title'   => __( 'Provider Details', 'gofetch-wpjm' ),
			'desc'    => __( "Provide some information about the current jobs feed provider. This information will help identify each job source and will be displayed on each job page. Leave empty if you don't want to show the jobs source. Note that some providers might require it. ", 'gofetch-wpjm' ),
			'header'  => 'h2 style="font-style: italic;"',
			'section' => 'source',
		);

		if ( ! gfjwjm_fs()->is_not_paying() ) {

			$title_desc[ $i++ ] = array(
					'title'   => __( 'Monetize', 'gofetch-wpjm' ),
					'desc'    => __( 'Affiliate parameters or any other parameters to use with the jobs provider external links to monetize on external job clicks.', 'gofetch-wpjm' ),
					'header'  => 'h2 style="font-style: italic;"',
					'section' => 'monetize',
			);

		}

		$title_desc[ $i++ ] = array(
			'title'  => __( 'Jobs Setup', 'gofetch-wpjm' ),
			'desc'   => __( 'Fill in additional details like job categories, job types, job duration, etc, for the jobs you are importing. These details will be added to each imported job.', 'gofetch-wpjm' ),
			'header' => 'h2 style="font-style: italic;"',
		);

		$title_desc[ $i++ ] = array(
			'title'   => __( 'Taxonomies', 'gofetch-wpjm' ),
			'desc'    => __( 'The taxonomies that best fit the content you are importing or configuring. Click "Edit..." to specify taxonomies yourself, otherwise jobs will use default taxonomies.', 'gofetch-wpjm' ),
			'tip'     => __( "To make sure jobs are imported with relevant taxonomy terms it's recommended that you import a relevant feed for each of your job types and job categories (e.g: import a feed containing 'Full-Time', 'Marketing' jobs). "
						 . '<br/>Otherwise, any taxonomy terms you choose below will be blindly assigned to all imported jobs.', 'gofetch-wpjm' ),
			'section' => 'taxonomies',
		);

		$title_desc[ $i++ ] = array(
			'title'   => __( 'Details', 'gofetch-wpjm' ),
			'desc'    => __( 'The default values for the custom fields (details) of each job being imported. Click "Edit..." to assign values to custom fields, otherwise they will be left as is.', 'gofetch-wpjm' ),
			'tip'     => __( 'The custom fields values will only be assigned if not already provided by the feed. They will not override the original values. As an example, if a job being imported already contains the location or the job company ***, that information will be used instead of any values you add here.', 'gofetch-wpjm' ) .
				   ' ' . __( 'The plugin will also try to assign default values based on the feed parameters (if any).', 'gofetch-wpjm' ) .
		  '<br/><br/>' . __( '*** Altough the import process does it\'s best to find the custom fields values it is not guaranteed since each provider outputs that information in their feeds differently.', 'gofetch-wpjm' ) . ' ' .
		  			     __( 'Check the sample table to see all the valid fields in the RSS feed.', 'gofetch-wpjm' ) .
		  			     ( ! gfjwjm_fs()->is_plan__premium_only( 'proplus', true ) ? html( 'p', html( 'code', html( 'span class="dashicons dashicons-warning"', '&nbsp;' ) .  ' ' . __( 'The Pro+ plan includes the \'Featured\' meta field to feature imported jobs.' ) ) ) : '' ),
			'section' => 'meta',
		);

		$title_desc[ $i++ ] = array(
			'title' => __( 'Posted by', 'gofetch-wpjm' ),
			'desc'  => __( 'Choose the user to be assigned to the jobs being imported. Note that this option is not saved on the template and apply only for instant imports.', 'gofetch-wpjm' ),
		);

		$title_desc[ $i++ ] = array(
			'title' => __( 'Filter', 'gofetch-wpjm' ),
			'desc'  => __( 'Use the filters below to limit the items that will be imported. Note that filter options are not saved on the template and apply only for instant imports.', 'gofetch-wpjm' ),
			'tip'    => __( 'Filters allow you to further refine the jobs that should be imported.', 'gofetch-wpjm' ) . ( ! gfjwjm_fs()->is_plan__premium_only( 'proplus', true ) ? html( 'p', html( 'code', html( 'span class="dashicons dashicons-warning"', '&nbsp;' ) .  ' ' . __( 'The Pro+ plan provides an additional filter to filter jobs by their feed date.' ) ) ) : '' ),
			'header' => 'h2 style="font-style: italic;"',
		);

		$title_desc[ $i++ ] = array(
			'title' => __( 'Save', 'gofetch-wpjm' ),
			'desc'  => __( "Save your current settings as a template to simplify future imports or to use later in scheduled imports. The 'Posted by' and 'Filters' settings will not be saved. Those settings are applied only to jobs imported through this page.", 'gofetch-wpjm' ),
			'header' => 'h2 style="font-style: italic;"',
		);

		if ( empty( $title_desc[ $section ] ) ) {
			return array();
		}

		$defaults = array(
			'title'       => '',
			'description' => '',
			'header'      => 'h4 style="font-style: italic;"',
			'section'     => '',
		);

		return wp_parse_args( $title_desc[ $section ], $defaults );
	}

	/**
	 * Outputs a placeholder for the providers RSS setup instructions.
	 */
	public function section_providers_placeholder() {

		$title = __( '<h3>Follow these instructions to setup an RSS feed for ...</h3>', 'gofetch-wpjm' );

		$placeholder = html( 'div', array( 'class' => 'providers-placeholder' ), $title . html( 'div', array( 'class' => 'providers-placeholder-content' ), '&nbsp;' ) );

		return $placeholder;
	}

	/**
	 * Outputs section breaks.
	 */
	public function section_break( $section ) {
		static $section;

		if ( ! $section ) {
			$section = 1;
		} else {
			$section++;
		}

		$title_desc_html = '';

		$title_desc = $this->section_title( $section );

		$section_slug = $section;

		if ( ! empty( $title_desc['section'] ) ) {
			$section_slug = $title_desc['section'];
		}

		if ( ! empty( $title_desc['title'] ) ) {
			$title_desc_html .= html( $title_desc['header'], array( 'class' => "section-{$section_slug}" ), $title_desc['title'] );
		}

		if ( ! empty( $title_desc['desc'] ) ) {

			if ( ! empty( $title_desc['tip'] ) ) {

				$tip  = html( 'span', array(
					'class'        => 'dashicons-before dashicons-editor-help tip-icon bc-tip',
					'title'        => __( 'Click to read additional info...', 'gofetch-wpjm' ),
					'data-tooltip' => BC_ToolTips::supports_wp_pointer() ? $title_desc['tip'] : __( 'Click for more info', 'gofetch-wpjm' ),
				) );

				if ( ! BC_ToolTips::supports_wp_pointer() ) {
					$tip .= html( "div class='tip-content'", $title_desc['tip'] );
				}

				$title_desc['desc'] .= ' ' . $tip;
			}

			$title_desc_html .= html( 'p', array( 'class' => "section-{$section_slug}", 'style' => "font-weight: normal" ), $title_desc['desc'] );
		}

		return $title_desc_html . '<hr/>';
	}


	// _Form handling.

	/**
	 * The main handler that starts the import process and/or saves the user settings and/or deletes user templates.
	 */
	public function form_handler() {

		if ( empty( $_POST['submit'] ) && empty( $_POST['action'] ) && empty( $_POST['delete_template'] ) ) {
			return false;
		}

		if ( ! empty( $_POST['delete_template'] ) ) {

			if ( ! empty( $_POST['templates_list'] ) ) {
				$this->delete_template( $_POST['templates_list'] );
			} else {
				echo scb_admin_notice( __( 'Please select a template to delete.', 'gofetch-wpjm' ), 'error' );
			}
			return;

		}

		// Only skip nonce check when saving a template since it already checks the nonce when calling the parent form handler.
		if ( empty( $_POST['save_template'] ) ) {
			check_admin_referer( $this->nonce );
		} else  {

			if ( empty( $_POST['template_name'] ) ) {
				echo scb_admin_notice( __( 'Please name your template.', 'gofetch-wpjm' ), 'error' );
				return;
			}

		}

		// Save the template and settings if requested.
		if ( ! empty( $_POST['save_template'] ) ) {
			$this->save_template( sanitize_text_field( $_POST['template_name'] ) );
		}

		// Skip earlier if the import was not requested.
		if ( empty( $_POST['submit'] ) ) {
			return;
		}

		$defaults = array(
			'post_author'     => 1,
			'tax_input'       => array(),
			'meta'            => array(),
			'source'          => array(),
			'from_date'       => '',
			'to_date'         => '',
			'limit'           => '',
			'rss_feed_import' => '',
		);
		$params = wp_parse_args( wp_array_slice_assoc( wp_unslash( $_POST ), array_keys( $defaults ) ), $defaults );

		// Retrieve the cached RSS items imported earlier.

		// Get the cached RSS items chunks.
		$chunks = get_transient('_goft-rss-feed-chunks');
		$chunks = $chunks ? $chunks : 1;

		$items = array();

		// Iterate trough each cached RSS items chunk.
		for ( $i = 0; $i < $chunks ; $i++ ) {
			$chunk = get_transient( "_goft-rss-feed-{$i}" );
			$items = array_merge( $items, (array) $chunk );
		}

		if ( empty( $items ) ) {
			echo scb_admin_notice( __( "Sorry, couldn't find anything to import.", 'gofetch-wpjm' ), 'error' );
			return;
		}

		if ( empty( $_POST['save_template'] ) ) {
			add_action( 'admin_notices', array( $this, 'admin_msg' ) );
		}

		self::$imported = GoFetch_WPJM_Importer::import( $items, $params );
	}

	/**
	 * Retrieves all the form data that can will be stored in the template.
	 */
	private function handle_template_settings() {

		$params = array(
			'rss_feed_import' => esc_url_raw( $_POST['rss_feed_import'] ),
			'fetch_images'    => (int) $_POST['fetch_images'],
			'force_feed'      => (int) $_POST['force_feed'],
			'tax_input'       => stripslashes_deep( $_POST['tax_input'] ),
			'meta'            => stripslashes_deep( $_POST['meta'] ),
			'source'          => stripslashes_deep( $_POST['source'] ),
		);

		return $params;
	}

	/**
	 * Save the user settings.
	 */
	private function save_template( $template_name ) {
		global $goft_wpjm_options;

		parent::form_handler();

		$templates = $goft_wpjm_options->templates;

		// Get the user params for the current template.
		$params = $this->handle_template_settings();

		$templates[ $template_name ] = $params;

		$goft_wpjm_options->templates = $templates;
	}

	/**
	 * Saves the user settings in a template using AJAX:
	 */
	public function ajax_save_template() {
		$this->save_template( sanitize_text_field( $_POST['template'] ) );
		die(1);
	}

	/**
	 * Deletes an existing template.
	 */
	private function delete_template( $name ) {
		global $goft_wpjm_options;

		$templates = $goft_wpjm_options->templates;

		unset( $templates[ $name ] );

		$goft_wpjm_options->templates = $templates;

		echo scb_admin_notice( __( 'The template was deleted.', 'gofetch-wpjm' ) );
	}

	/**
	 * Retrieve terms key/value pairs given a taxonomy.
	 */
	private function _get_terms_key_value_pairs( $taxonomy ) {
		$terms = get_terms( $taxonomy, array( 'hide_empty' => false ) );

		$terms_kvp[''] = __( 'None', 'gofetch-wpjm' );

		foreach( $terms as $term ) {
			$terms_kvp[ $term->slug ] = $term->name;
		}

		return $terms_kvp;
	}

	/**
	 * Retrieve default values for a given meta field.
	 */
	protected function get_default_value_for_meta( $field ) {

		if ( ! empty( $_POST['meta'][ $field ] ) ) {
			return wp_strip_all_tags( $_POST['meta'][ $field ] );
		}

		$value = '';

		return apply_filters( 'goft_wpjm_default_value_for_field', $value, $field );
	}

	/**
	 * Retrieve default values for a given taxonomy.
	 */
	protected function get_default_value_for_tax( $taxonomy ) {

		if ( ! empty( $_POST['tax_input'][ $taxonomy ] ) ) {
			return wp_strip_all_tags( $_POST['tax_input'][ $taxonomy ] );
		}

		$value = '';

		return apply_filters( 'goft_wpjm_default_value_for_taxonomy', $value, $taxonomy );
	}


	// Output callbacks for the 'render' property.

	/**
	 * Outputs the list of job listers.
	 */
	public function output_job_listers() {

		$job_listers_raw = get_users( array( 'role' => 'job_lister' ) );
		$admins_raw = get_users( array( 'role' => 'administrator' ) );

		$job_listers = array();

		foreach ( array_merge( $job_listers_raw, $admins_raw ) as $job_lister ) {
			$job_listers[ $job_lister->ID ] = $job_lister->display_name;
		}

		$type =  array(
			'title' => __( 'Job Lister', 'gofetch-wpjm' ),
			'type'  => 'select',
			'name'  => 'post_author',
			'extra' => array(
				'id' => 'job_lister',
			),
			'choices'  => $job_listers,
		);

		if ( ! empty( $_POST['post_author'] ) ) {
			$type['selected'] = wp_strip_all_tags( $_POST['post_author'] );
		}

		$output = $this->input( $type );

		return $output;
	}

	/**
	 * Output the list of taxonomies.
	 */
	public function output_taxonomy( $taxonomy ) {

		$default_value = $this->get_default_value_for_tax( $taxonomy->name );

		$type = array(
			'title' => $taxonomy->label,
			'type'  => 'select',
			'name'  => 'tax_input[' . $taxonomy->name . ']',
			'extra' => array(
				'id'      => $taxonomy->name,
				'section' => 'taxonomies',
				'default' => $default_value,
			),
			'choices'  => $this->_get_terms_key_value_pairs( $taxonomy->name ),
			'selected' => $default_value,
		);

		if ( false !== strpos( $taxonomy->name, '_tag' ) ) {
			$type['type'] = 'text';
			$type['extra']['class'] = 'regular-text';
			$type['extra']['section'] = 'taxonomies';
			$type['value'] = $default_value;
		}

		return $this->input( $type );
	}

	/**
	 * Outputs the date interval settings.
	 */
	public function output_date_span() {
		return apply_filters( 'goft_wpjm_setting_date_span', false );
	}

	/**
	 * Outputs the affiliate parameter.
	 */
	public function output_affiliate_param() {
		return apply_filters( 'goft_wpjm_setting_url_params', false );
	}

	/**
	 * Outputs the main fields table.
	 */
	public function output_sample_table() {
		return GoFetch_WPJM_Sample_Table::display();
	}

	/**
	 * Overrides the parent method to provide a different form button.
	 */
	public function form_table_wrap( $content ) {

		$args = array(
			'class' => 'button-primary import-posts',
			'value' => __( 'Go Fetch Jobs!', 'gofetch-wpjm' ),
		);

		$output = self::table_wrap( $content );
		$output = self::extra_content( $output );
		$output = $this->form_wrap( $output, $args );

		return $output;
	}

	/**
	 * Outputs the <table> wrapper.
	 */
	public static function table_wrap( $content ) {
		return html( "table class='form-table goft'", $content );
	}

	/**
	 * Outputs additional content within the main form.
	 */
	public static function extra_content( $content ) {

		$content = $content . self::get_geocomplete_fields();

		return apply_filters( 'goft_wpjm_form_extra_content', $content );
	}

	/**
	 * The admin messages to display after user actions.
	 */
	public function admin_msg( $msg = '', $class = 'updated' ) {

		if ( self::$imported > 0 ) {
			$msg .= sprintf( __( 'Imported <strong>%d NEW %s</strong>.', 'gofetch-wpjm' ), self::$imported, _n( 'Job', 'Jobs', 'gofetch-wpjm', self::$imported ) );
		} else {
			$msg .= __( 'No new jobs found.', 'gofetch-wpjm' );
		}

		if ( ! empty( $_POST['save_template'] ) ) {

			if ( $msg ) {
				$msg .= '<br/><br/>';
			}

			$msg .= __( 'Template <strong>saved</strong>.', 'gofetch-wpjm' );
		}

		echo scb_admin_notice( $msg, $class );
	}

}

new GoFetch_WPJM_Admin_Settings( GOFT_WPJM_PLUGIN_FILE, $goft_wpjm_options );
