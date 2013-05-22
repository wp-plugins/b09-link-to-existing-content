=== B09 Link to Existing Content ===
Contributors: BASICS09
Tags: wplink, links, internal, suppress_filters, filter, shortcode, tinymce, wysiwyg, admin, developer
Requires at least: 3.0.0
Tested up to: 3.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Improves the built in "Link to existing content" dialog. Makes filters for post types and taxonomies available, has optional shortcode support and makes "Search Everything" work with the wplink-dialog.


== Description ==

= About this Plugin =
B09 Link to Existing Content is a small plugin that is ment to improve the wordpress "Link to existing content"-functionality.

* Default Behaviour is seamless, just continue using the Link PopUp as always
* Consider installing the plugin <a title='Search Everyting' href='http://wordpress.org/plugins/search-everything/'>Search Everything</a> for full control over the search results when using "Link to existing Content"
* Makes internal links more future-proof by using a shortcode with the post or taxonomy id. Just select the post you want to link to and click "Add Shortcode", and the shortcode gets pasted to your editor and automatically handled in your themes.
* Filters for this plugin:
 - Control if the shortcode functionality should be active or not: `link_to_existing_content_use_shortcode`
 - Control which post types should be searched: `link_to_existing_content_post_types`
 - Control which taxonomies should be searched: `link_to_existing_content_taxonomies`

= A short Note =
This plugin is very young, so there might still be bugs somewhere in there. Before you give it a bad rating, help us to solve eventual issues by posting here to the support forum. That way, you will help the community much more than by just saying "it doesn't work, one star". Thanks.

= Votes =
If you think it works and you like the functionality, don't wait and rate it here! That way, it will stay alive.

== Installation ==

1. Upload 'b09.link-to-existing-content' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Done. The plugin integrates seamlessly with the wordpress interface.
4. Use Filters to customize the plugins behaviour, see source code of for documentation
5. If you have custom fields in your post types, you should consider installing the plugin Search Everything and activate the option "Search every custom field".


== Frequently Asked Questions ==

= Q. How do I make the custom fields of my posts in the link popup searchable? =
A. Install the plugin "Search Everything" and activate the option "Search every custom field"

= Q. Can I disable the shortcode functionality? =
A. Yes you can. View the source code of the plugin for documentation about filters.


== Screenshots ==

1. Seamless integration with the wordpress UI. Control of the post types and taxonomies you want to be able to link to.

2. Shortcut functionality for internal links, so they don't break so easily in the future (server move, rewrite changes, …)

3. An example of how a shortcode link to another post looks like

4. The shortcode gets handled automatically by the plugin. Of course, you can overwrite it from your functions.php, if you want to.


== Changelog ==

= 1.3.0 =
* Now also works if the visual editor is disabled.

= 1.2.0 =
* Added Functionality for linking to taxonomies from the "Link to Existing Content" dialog

= 1.1.0 =
* The plugin uses the built-in admin-ajax functionality now
* The shortcode appears directly in the url field, so it doesn't suprise the user if it gets pasted to the editor
* Added custom css file

= 1.0.0 =
* Initial Release.


== Upgrade Notice ==

= 1.0.0 =
