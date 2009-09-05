<?php

add_action( 'publish_post', 'rsscloud_schedule_post_notifications' );
function rsscloud_schedule_post_notifications( ) {
	if ( !defined( 'RSSCLOUD_NOTIFICATIONS_INSTANT' ) || !RSSCLOUD_NOTIFICATIONS_INSTANT )
		wp_schedule_single_event( time( ), 'rsscloud_send_post_notifications_action' );
	else
		rsscloud_send_post_notifications( );

}

add_action( 'rsscloud_send_post_notifications_action', 'rsscloud_send_post_notifications' );
function rsscloud_send_post_notifications( ) {
	$rss2_url = get_bloginfo( 'rss2_url' );
	$notify = rsscloud_get_hub_notifications( );
	if ( !is_array( $notify ) )
		$notify = array( );

	foreach ( $notify[$rss2_url] as $notify_url => $n ) {
		if ( $n['status'] == 'active' ) {
			if ( $n['protocol'] == 'http-post' ) {
				$result = wp_remote_post( $notify_url, array( 'method' => 'POST', 'timeout' => RSSCLOUD_HTTP_TIMEOUT, 'user-agent' => RSSCLOUD_USER_AGENT, 'body' => array( 'url' => $rss2_url ) ) );

				if ( $result['response']['code'] != 200 )
					$notify[$rss2_url][$notify_url]['failure_count']++;

				if ( $notify[$rss2_url][$notify_url]['failure_count'] > RSSCLOUD_MAX_FAILURES )
					$notify[$rss2_url][$notify_url]['status'] = 'suspended';

			}
		}
	} // foreach

	rsscloud_update_hub_notifications( $notify );
}
