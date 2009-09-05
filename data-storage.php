<?php
if ( !function_exists( 'rsscloud_get_ping_url' ) ) {
	function rsscloud_get_ping_url( ) {
		return get_option( 'rsscloud_ping_url' );
	}
}

if ( !function_exists( 'rsscloud_get_ping_register_procedure' ) ) {
	function rsscloud_get_register_procedure( ) {
		return get_option( 'rsscloud_ping_register_procedure' );
	}
}

if ( !function_exists( 'rsscloud_get_ping_protocol' ) ) {
	function rsscloud_get_ping_protocol( ) {
		return get_option( 'rsscloud_ping_protocol' );
	}
}

if ( !function_exists( 'rsscloud_get_hub_notifications' ) ) {
	function rsscloud_get_hub_notifications( ) {
		return get_option( 'rsscloud_hub_notifications' );
	}
}

if ( !function_exists( 'rsscloud_update_hub_notifications' ) ) {
	function rsscloud_update_hub_notifications( $notifications ) {
		return update_option( 'rsscloud_hub_notifications', (array) $notifications );
	}
}
