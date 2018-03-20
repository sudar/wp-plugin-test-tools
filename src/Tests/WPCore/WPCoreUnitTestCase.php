<?php

namespace BulkWP\Tests\WPCore;

/**
 * TestCase base class for writing Core unit tests for Bulk WP plugins.
 *
 * Adds lot of helper functions.
 */
abstract class WPCoreUnitTestCase extends \WP_UnitTestCase {

	/**
	 * Helper method to get posts by status.
	 *
	 * @param string $status Post status.
	 * @param string $post_type Post Type.
	 *
	 * @return array Posts.
	 */
	protected function get_posts_by_status( $status = 'publish', $post_type = 'post' ) {
		$args = array(
			'post_type'   => $post_type,
			'nopaging'    => 'true',
			'post_status' => $status,
		);

		$wp_query = new \WP_Query();

		return $wp_query->query( $args );
	}

	/**
	 * Helper method to get posts by tag.
	 *
	 * @param string $tag Tag name.
	 *
	 * @return array Posts that belong to that tag.
	 */
	protected function get_posts_by_tag( $tag ) {
		$args = array(
			'tag__in'     => array( $tag ),
			'post_type'   => 'post',
			'nopaging'    => 'true',
			'post_status' => 'publish',
		);

		$wp_query = new \WP_Query();

		return $wp_query->query( $args );
	}

	/**
	 * Helper method to get posts by category.
	 *
	 * @param string $cat Category name.
	 *
	 * @return array Posts that belong to that category.
	 */
	protected function get_posts_by_category( $cat ) {
		$args = array(
			'category__in' => array( $cat ),
			'post_type'    => 'post',
			'nopaging'     => 'true',
			'post_status'  => 'publish',
		);

		$wp_query = new \WP_Query();

		return $wp_query->query( $args );
	}

	/**
	 * Helper method to get posts by custom term.
	 *
	 * @param int    $term_id   Term ID.
	 * @param string $taxonomy  Taxonomy.
	 * @param string $post_type Post Type.
	 *
	 * @return array Posts that belong to that custom term.
	 */
	protected function get_posts_by_custom_term( $term_id, $taxonomy, $post_type = 'post' ) {
		$args = array(
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			),
			'post_type' => $post_type,
			'nopaging'  => 'true',
		);

		$wp_query = new \WP_Query();

		return $wp_query->query( $args );
	}

	/**
	 * Get Pages by post status.
	 *
	 * @param string $status Post status.
	 *
	 * @return array Pages that belong to the post status.
	 */
	protected function get_pages_by_status( $status = 'publish' ) {
		return $this->get_posts_by_status( $status, 'page' );
	}

	/**
	 * Helper method to remove a role.
	 *
	 * The given role is removed only when it exists.
	 *
	 * @param string $role User Role.
	 */
	protected function remove_role( $role ) {
		if ( get_role( $role ) ) {
			remove_role( $role );
		}
	}

	/**
	 * Helper method to assign a role to User by User ID.
	 *
	 * @param int    $user_id User ID.
	 * @param string $role    User Role.
	 */
	protected function assign_role_by_user_id( $user_id, $role ) {
		$u = new \WP_User( $user_id );
		$u->set_role( $role );
	}
}
