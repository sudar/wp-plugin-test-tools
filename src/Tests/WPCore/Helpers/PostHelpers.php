<?php

namespace BulkWP\Tests\WPCore\Helpers;

/**
 * Helpers for accessing posts in Test cases.
 */
trait PostHelpers {

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