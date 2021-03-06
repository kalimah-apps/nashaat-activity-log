<?php
/*
Plugin Name: Nashaat Activity Log
Description: Log and view different WordPress activity on your site
Version: 1.1
Author: Kalimah Apps
Author URI: https://github.com/kalimah-apps
License: GPLv2 or later
Text Domain: nashaat
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define( 'NASHAAT_MAIN_FILE', __FILE__ );

require_once 'constants.php';
require_once 'setup.class.php';

add_action( 'nashaat_init', 'nashaat_initiate_log' );
/**
 * Initiate log classes
 *
 * @return void
 */
function nashaat_initiate_log() {

	try {
		new NashaatPostHooks();
		new NashaatWidgetHooks();
		new NashaatUserHooks();
		new NashaatThemeHooks();
		new NashaatTaxonomyHooks();
		new NashaatSystemHooks();
		new NashaatPluginHooks();
		new NashaatOptionsHooks();
		new NashaatMenuHooks();
		new NashaatMediaHooks();
		new NashaatCommentHooks();

		// WooCommerce
		new NashaatWCSettings();
		new NashaatWCOrders();
		new NashaatWCProduct();
		new NashaatWCCoupon();
		new NashaatWCVariations();
	} catch ( \Throwable $th ) {
		echo esc_html( $th->getMessage() );
	}

}


$includes = array(
	'render-log-info.class',
	'translation.class',
	'functions',
	'util.trait',
	'logs-table-base.class',
	'logs-table.class',
	'settings.class',
	'base-hook.class',
	'nashaat.class'
);

foreach ( $includes as $filename ) {
	require_once NASHAAT_PLUGIN_PATH . "includes/{$filename}.php";
}

$dir_iterator = new RecursiveDirectoryIterator( NASHAAT_PLUGIN_PATH . '/hooks/' );
$iterator = new RecursiveIteratorIterator( $dir_iterator, RecursiveIteratorIterator::SELF_FIRST );

foreach ( $iterator as $file ) {
	if ( ! $file->isFile() ) {
		continue;
	}
	require_once $file->getPathname();
}

try {
	new NashaatLog( new NashaatSettings() );
} catch ( \Throwable $th ) {
	echo esc_html( $th->getMessage() );
}