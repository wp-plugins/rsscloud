<?php
/*
Plugin Name: RSS Cloud
Plugin URI:
Description: Ping RSS Cloud servers
Version: 0.1
Author: Joseph Scott
Author URI: http://josephscott.org/
 */

// Uncomment this to not use cron to send out notifications
# define( 'RSSCLOUD_NOTIFICATIONS_INSTANT', true );

if ( !defined( 'RSSCLOUD_USER_AGENT' ) )
	define( 'RSSCLOUD_USER_AGENT', 'WordPress/RSS Cloud 0.1' );

if ( !defined( 'RSSCLOUD_MAX_FAILURES' ) )
	define( 'RSSCLOUD_MAX_FAILURES', 5 );

if ( !defined( 'RSSCLOUD_HTTP_TIMEOUT' ) )
	define( 'RSSCLOUD_HTTP_TIMEOUT', 3 );

require WP_PLUGIN_DIR . '/rsscloud/data-storage.php';

if ( !function_exists( 'rsscloud_hub_process_notification_request' ) )
	require WP_PLUGIN_DIR . '/rsscloud/notification-request.php';

if ( !function_exists( 'rsscloud_schedule_post_notifications' ) )
	require WP_PLUGIN_DIR . '/rsscloud/send-post-notifications.php';

add_filter( 'query_vars', 'rsscloud_query_vars' );
function rsscloud_query_vars( $vars ) {
	$vars[] = 'rsscloud';
	return $vars;
}

add_action( 'parse_request', 'rsscloud_parse_request' );
function rsscloud_parse_request( $wp ) {
	if ( array_key_exists( 'rsscloud', $wp->query_vars ) ) {
		if ( $wp->query_vars['rsscloud'] == 'notify' )
			rsscloud_hub_process_notification_request( );

		exit;
	}
}

function rsscloud_notify_result( $success, $msg ) {
	header( 'Content-Type: text/xml' );
	echo "<?xml version='1.0'?>\n";
	echo "<notifyResult success='{$success}' msg='{$msg}' />\n";
	exit;
}

function rsscloud_get_valid_protocols( ) {
	return array( 'http-post' );
}

add_action( 'rss2_head', 'rsscloud_add_rss_cloud_element' );
function rsscloud_add_rss_cloud_element( ) {
	$cloud = parse_url( get_option( 'home' ) . '/?rsscloud=notify' );

	$cloud['port']		= (int) $cloud['port'];
	if ( empty( $cloud['port'] ) )
		$cloud['port'] = 80;

	$cloud['path']	= preg_replace( '|[^a-zA-Z0-9\/\.]|', '', $cloud['path'] );
	$cloud['path']	.= "?{$cloud['query']}";

	$cloud['host']	= strtolower( $cloud['host'] );
	$cloud['host']	= preg_replace( '|[^a-z\.]|', '', $cloud['host'] );

	$register_procedure = get_option( 'rsscloud_ping_register_procedure' );
	$register_procedure = preg_replace( '|[^a-zA-Z0-9\-]|', '', $register_procedure );

	$valid_protocols = rsscloud_get_valid_protocols( );
	$protocol = get_option( 'rsscloud_ping_protocol' );

	$protocol = strtolower( $protocol );
	if ( !in_array( $protocol, $valid_protocols ) ) {
		$protocol = 'http-post';
	}

	echo "<cloud domain='{$cloud['host']}' port='{$cloud['port']}'";
	echo " path='{$cloud['path']}' registerProcedure='{$register_procedure}'";
	echo " protocol='{$protocol}' />";
	echo "\n";
}
