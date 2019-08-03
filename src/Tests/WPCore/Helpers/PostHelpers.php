<?php

namespace BulkWP\Tests\WPCore\Helpers;

/**
 * Helpers for accessing posts in Test cases.
 */
trait PostHelpers {

	/**
	 * Get posts that were created today.
	 *
	 * @param string $post_type   Post type. Default 'post'.
	 * @param string $post_status Post status. Default 'publish'.
	 *
	 * @return array Today's posts.
	 */
	public function get_today_posts( $post_type = 'post', $post_status = 'publish' ) {
		$today = getdate();

		$args = [
			'post_type'   => $post_type,
			'post_status' => $post_status,
			'date_query'  => [
				[
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				],
			],
		];

		$args     = wp_parse_args( $args, $this->get_default_wp_query_args() );
		$wp_query = new \WP_Query();

		return $wp_query->query( $args );
	}

	/**
	 * Get the default query args.
	 *
	 * @return array List of default query args.
	 */
	protected function get_default_wp_query_args() {
		return [
			'nopaging'            => true,
			'ignore_sticky_posts' => true,
		];
	}
}