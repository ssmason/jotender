<?php
/**
 * Specific import code for WP Job Manager.
 *
 * @package GoFetch/WPJM
 */

class GoFetch_WPJM_Specific_Import extends GoFetch_WPJM_Importer {

	public function __construct() {
		add_filter( 'goft_wpjm_item_meta_value', array( $this, 'replace_item_meta_placeholders' ), 10, 5 );
	}

	/**
	 * Retrieves the custom meta fields/known fields key/value pair mappings.
	 */
	public static function meta_mappings() {

		$mappings = array(
			'_job_location'    => 'location',
			'_company_name'    => 'company',
			'_company_logo'    => 'logo',
			'geolocation_lat'  => 'latitude',
			'geolocation_long' => 'longitude',
		);

		return $mappings;
	}

	/**
	 * Replaces string placeholders with valid data on a given meta key.
	 */
	public function replace_item_meta_placeholders( $meta_value, $meta_key, $item, $post_id, $params ) {

		switch ( $meta_key ) {

			case '_application':
				$meta_value = GoFetch_WPJM_Importer::add_query_args( $params, $item['link'] );
				break;

		}

		return $meta_value;
	}

}

new GoFetch_WPJM_Specific_Import;
