<?php

namespace BulkWP\Tests\WPCore;

use BulkWP\Tests\WPCore\Helpers\PostHelpers;

/**
 * TestCase base class for writing Core unit tests for Bulk WP plugins.
 *
 * Adds lot of helper functions.
 */
abstract class WPCoreUnitTestCase extends \WP_UnitTestCase {

	use PostHelpers;

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
			'post_status' => $status,
		);

		$args = wp_parse_args( $args, $this->get_default_wp_query_args() );

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
			'post_status' => 'publish',
		);

		$args = wp_parse_args( $args, $this->get_default_wp_query_args() );

		$wp_query = new \WP_Query();

		return $wp_query->query( $args );
	}

	/**
	 * Helper method to get posts by category.
	 *
	 * @param string $cat       Category name.
	 * @param string $post_type Post Type. Optional. Default 'Post'
	 *
	 * @return array Posts that belong to that category.
	 */
	protected function get_posts_by_category( $cat, $post_type = 'post' ) {
		$args = array(
			'category__in' => array( $cat ),
			'post_type'    => $post_type,
			'post_status'  => 'publish',
		);

		$args = wp_parse_args( $args, $this->get_default_wp_query_args() );

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
		);

		$args = wp_parse_args( $args, $this->get_default_wp_query_args() );

		$wp_query = new \WP_Query();

		return $wp_query->query( $args );
	}

	/**
	 * Helper method to get posts by post type.
	 *
	 * @param string $post_type Post Type.
	 *
	 * @return array Posts that belong to the post type.
	 */
	protected function get_posts_by_post_type( $post_type = 'post' ) {
		$args = array(
			'post_type' => $post_type,
		);

		$args = wp_parse_args( $args, $this->get_default_wp_query_args() );

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
	 * Get the list of sticky post ids.
	 */
	protected function get_sticky_post_ids() {
		return get_option( 'sticky_posts' );
	}

	/**
	 * Check if a given taxonomy is a default taxonomy or not.
	 *
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return bool True if $taxonomy is a default Taxonomy, False otherwise.
	 */
	protected function is_default_taxonomy( $taxonomy ) {
		$default_taxonomies = array( 'category', 'post_tag', 'link_category', 'post_format' );

		return in_array( $taxonomy, $default_taxonomies, true );
	}

	/**
	 * Check if a given post type is a default post type or not.
	 *
	 * @param string $post_type Post Type name.
	 *
	 * @return bool True if $post_type is a default Post Type, False otherwise.
	 */
	protected function is_default_post_type( $post_type ) {
		$default_post_types = array( 'post', 'page' );

		return in_array( $post_type, $default_post_types, true );
	}

	/**
	 * Register post type and taxonomy.
	 *
	 * Registers post type and taxonomy only if needed and skips the registration call for built-in taxonomies.
	 *
	 * @param string $post_type Post type.
	 * @param string $taxonomy Taxonomy.
	 */
	protected function register_post_type_and_taxonomy( $post_type, $taxonomy ) {
		$this->register_post_type( $post_type );

		if ( ! $this->is_default_taxonomy( $taxonomy ) ) {
			register_taxonomy( $taxonomy, $post_type );
		}

		if ( ! $this->is_default_post_type( $post_type ) || ! $this->is_default_taxonomy( $taxonomy ) ) {
			register_taxonomy_for_object_type( $taxonomy, $post_type );
		}
	}

	/**
	 * Register post type if needed.
	 *
	 * @param string $post_type Post type.
	 */
	protected function register_post_type( $post_type ) {
		if ( ! $this->is_default_post_type( $post_type ) ) {
			register_post_type( $post_type );
		}
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

	/**
	 * Log-in a specific user.
	 *
	 * @param int $user_id User ID.
	 */
	protected function login_user( $user_id ) {
		$user = get_user_by( 'id', $user_id );

		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );

		do_action( 'wp_login', $user->user_login );
	}

	/**
	 * Call protected method of a class.
	 *
	 * @param object $object      Instantiated object that we will run method on (Passed by Reference).
	 * @param string $method_name Method name to call.
	 * @param array  $parameters  Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 * @throws \ReflectionException Throws an exception if method is not present.
	 */
	protected function invoke_protected_method( &$object, $method_name, array $parameters = array() ) {
		$method = $this->get_method_by_reflection( $object, $method_name );

		if ( ! $method->isProtected() ) {
			$this->fail( $method_name . ' is not a protected method of ' . get_class( $object ) );
		}

		$method->setAccessible( true );

		return $method->invokeArgs( $object, $parameters );
	}

	/**
	 * Call private method of a class.
	 *
	 * @param object $object      Instantiated object that we will run method on (Passed by Reference).
	 * @param string $method_name Method name to call.
	 * @param array  $parameters  Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 * @throws \ReflectionException Throws an exception if method is not present.
	 */
	protected function invoke_private_method( &$object, $method_name, array $parameters = array() ) {
		$method = $this->get_method_by_reflection( $object, $method_name );

		if ( ! $method->isPrivate() ) {
			$this->fail( $method_name . ' is not a private method of ' . get_class( $object ) );
		}

		$method->setAccessible( true );

		return $method->invokeArgs( $object, $parameters );
	}

	/**
	 * Get the method of an object using reflection.
	 *
	 * @param object $object      Instantiated object that we will run method on (Passed by Reference).
	 * @param string $method_name Method name to call.
	 *
	 * @return \ReflectionMethod Method.
	 * @throws \ReflectionException Throws an exception if method is not present.
	 */
	protected function get_method_by_reflection( &$object, $method_name ) {
		$reflection = new \ReflectionClass( get_class( $object ) );
		$method     = $reflection->getMethod( $method_name );

		return $method;
	}

	/**
	 * Set the value of a private/protected property.
	 *
	 * @param object $object        Instantiated object whos property we have to set value (Passed by Reference).
	 * @param string $property_name Property name.
	 * @param mixed  $value         Value to be set.
	 *
	 * @throws \ReflectionException Throws an exception if property is not present.
	 */
	protected function set_protected_property( &$object, $property_name, $value ) {
		$reflection = new \ReflectionClass( get_class( $object ) );
		$property   = $reflection->getProperty( $property_name );

		// TODO: Split private and protected properties into different methods.
		if ( ! $property->isProtected() && ! $property->isPrivate() ) {
			$this->fail( $property_name . ' is not a private or protected property of ' . get_class( $object ) );
		}

		$property->setAccessible( true );
		$property->setValue( $object, $value );
	}
}
