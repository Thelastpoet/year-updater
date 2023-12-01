=== Year Updater ===
Contributors: smartkenyan
Tags: title, year, updater
Requires at least: 4.7
Tested up to: 6.4.1
Stable tag: 1.3.0
Requires PHP: 8.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Change the year in the title easily with a click of a button.

A WordPress plugin to update the titles of posts with a specific year in their title.

== Description ==

The Year Updater plugin allows you to update the titles of posts with a specific year in their title. For example, you can use the plugin to replace the year 2023 or previous years in the post titles with the current year.

Updating year in blog post titles is an Old SEO trick that might give you a boost in the SERPS.

== Installation ==

There are two ways to install Year Updater.

1. Go to "Plugins > Add New" in your Dashboard and search for: Year Updater
2. Download the .zip from WordPress.org and upload the folder to the `/wp-content/plugins/` directory via FTP.

Option one is easy and faster.

== Frequently Asked Questions ==

= Do I need to keep the plugin after updating the year? =

No, you don't. After you update, you can remove the plugin. The changes will remain on site.

== Screenshots ==
1. When you visit settings, year update, you get to input the current year screenshot-1.png
2. A preview of how your posts will look after you update screenshot-2.png

== Changelog ==

= 1.3.0 =
* Organized the main plugin file for better readability and maintenance.
* Refined the method for updating the year in post titles.
* Introduced namespace usage for better code organization and to avoid name conflicts.
* Modified the approach of querying posts to enhance performance, particularly for large datasets
* Minor code improvements

= 1.2.0 =
* Better and faster code implementation
* Added a new settings page in the WordPress admin area for the plugin.
* Implemented a system to query all posts of a given type and display them on the settings page.
* Added functionality to update the year in post titles, edit the post's modified date, and add a post meta field 'year_updated' with the new year.
* Included error handling and success/error notifications.
* Added a check to skip posts with the current year in their title.
* Added a message above the 'Update Posts' button to inform users that posts with the current year in their title will not be updated.

= 1.1 =
* The preview.php file was updated to show a table of the old and new post titles rather than a list. 
* The update.php file was updated only to update the post titles and not the post content. It was also updated to use the wp_update_post() function and the update_post_meta() function to update the post data and add a custom field to mark the post as updated. Inline documentation was added to both the preview.php and update.php files to explain the code and its purpose.

= 1.0 =
* Initial release
