=== Plugin Name ===
Contributors: josephscott
Tags: rss
Requires at least: 2.8
Tested up to: 2.8
Stable tag: 0.4.1

Adds RSSCloud ( http://rsscloud.org/ ) capabilities to your RSS feed.

== Description ==

Adds RSSCloud ( http://rsscloud.org/ ) capabilities to your RSS feed.

== Changelog ==

= 0.4.1 =
* Limit domain characters

= 0.4.0 =
* Add support for the domain parameter in notification requests
* Notification requests that include a domain parameter use HTTP GET that
  inlcudes a challenge field that must be returned exactly as is in the body
  of the response
* Separate out the code for scheduling notifications and sending
  notifications, making it easier to replace just one or the other
* Only update notification URL details if something in the full
  loop has changed
* Add an optional parameter to rsscloud_send_post_notifications()
  for the rss2_url that was updated
* Add do_action() calls for certain events
* Provide a failure response for update requests for any feed URL
  that isn't the feed URL for the blog
* Accept any 2xx HTTP status code for notifications
* Use RSSCLOUD_FEED_URL constant for the blog feed URL if it is defined

= 0.3.2 =
* Escape error text when a notification test has failed

= 0.3.1 =
* Require notification path to start with a slash

= 0.3 =
* Limit path characters
* Fix typo
* Small adjustment to plugin description

= 0.2 =
* Minor improvements and bug fixes

= 0.1 =
* Initial release
