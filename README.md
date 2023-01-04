# Year Updater

A WordPress plugin to update the titles of posts with a specific year in their title.

## Description

The Year Updater plugin allows you to update the titles of posts with a specific year in their title. For example, you can use the plugin to replace the year 2022 in the post titles with the currrent year.

To use the plugin, go to the "Year Updater" menu in the WordPress dashboard under Settings, enter the current year and click the "Continue" button. The plugin will display a list of posts with the old year in their title, and it will allow you to update the post titles by replacing the year 2019 with the current year.

## Installation

1. Download the plugin files from the repository.
2. Upload the `update-post-titles` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Go to the "Year Updater" menu in the WordPress dashboard to use the plugin.

## Changelog

### 1.1

The preview.php file was updated to show a table of the old and new post titles rather than a list.
The update.php file was updated to only update the post titles and not the post content. It was also updated to use the wp_update_post() function and the update_post_meta() function to update the post data and add a custom field to mark the post as updated.
Inline documentation was added to both the preview.php and update.php files to explain the code and its purpose.

### 1.0

* Initial release
