<?php
/**
 * PHPUnit Bootstrap file for loading WP Core Unit Tests.
 *
 * @package BulkWP\Tests\WPCore;
 */

namespace BulkWP\Tests\WPCore;

/**
 * Load plugins for testing.
 *
 * @param array $bd_plugins_to_load List of plugin files to load.
 */
function load_plugins_for_testing( $bd_plugins_to_load ) {
	if ( ! isset( $bd_plugins_to_load ) || empty( $bd_plugins_to_load ) ) {
		echo 'Could not find the list of plugins to load. Have you defined the `$plugins_to_load` variable in your bootstrap file?' . PHP_EOL;
		exit( 1 );
	}

	// Load core unit Test directory.
	$_tests_dir = getenv( 'WP_TESTS_DIR' );

	if ( ! $_tests_dir ) {
		$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
	}

	if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
		echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh?" . PHP_EOL;
		exit( 1 );
	}

	// Give access to tests_add_filter() function.
	require_once $_tests_dir . '/includes/functions.php';

	/**
	 * Manually load the plugin being tested.
	 */
	tests_add_filter(
		'muplugins_loaded',
		function () use ( $bd_plugins_to_load ) {
			foreach ( $bd_plugins_to_load as $plugin ) {
				require_once $plugin;
			}
		}
	);

	// Start up the WP testing environment.
	require $_tests_dir . '/includes/bootstrap.php';

	// Load BulkWP test tools.
	require_once dirname( __FILE__ ) . '/Helpers/PostHelpers.php';
	require_once dirname( __FILE__ ) . '/WPCoreUnitTestCase.php';
}
