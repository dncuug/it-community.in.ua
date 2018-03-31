=== Advanced Export for WP & WPMU ===
Contributors: wpmuguru
Tags: export, wordpress, wpmu, multisite 
Requires at least: 2.7
Tested up to: 4.3
Stable tag: trunk

Adds an Advanced Export to the Tools menu which allows selective exporting of pages, posts, specific categories and/or post statuses by date.

== Description ==

*The functionality in this plugin was incorporated into the built in export feature in WordPress 3.0*

Working with a single large export file can be difficult. Use this plugin to create multiple export files that contain sections of your blog. This plugin has been tested on WP  & WPMU versions 2.7 - 2.8.4. The plugin may work on earlier versions of WP/WPMU. 

All restriction options provided in the plugin are *optional*. If no restrictions are selected, this plugin generates the same export file as the export feature built into WP & WPMU.

*Features*

Export by any optional combination of:

*	*Date Range* - Start & end month/year
*	*Author* - Same as WP/WPMU built-in export
*	*Category* - Export a specific category
*	*Content type* - Choose either posts or pages
*	*Post status* - Choose Draft, Published, Scheduled or Private
*	*Blog Tag/Category Terms* - Choose whether to include the blog's complete list of Tags and/or Categories

Support can be obtained through:

[WordPress Forums](http://wordpress.org/tags/advanced-export-for-wp-wpmu?forum_id=10#postform)

== Installation ==

1. Upload the `advanced-export-for-wp-wpmu` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Tools -> Advanced Export

== Changelog ==

= 2.9 =
* Fix deprecated warnings
* Add translation support

= 2.8.3 =
* Changed required permission to be consistent with required export permission in WP.
* Added ability to exclude the blog's Tags/Category lists.

= 2.8.2 =
* Added support for MySQL 4.0.

= 2.8.1 =
* Fixed issue where selecting an author generated an export with no posts.

= 2.8.0.1 =
* Added block to prevent direct calling of file.

= 2.8 =
* Original version.

