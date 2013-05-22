=== B09 Link to Existing Content ===
Contributors: BASICS09
Tags: wplink, links, internal, suppress_filters, filter, shortcode, tinymce, wysiwyg, admin, developer
Requires at least: 3.0.0
Tested up to: 3.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Seamless integration of the "Link to existing Content"-functionality in Wordpress with the plugin "Search Everything". Also automatically adds a shortcode for internal links, with id, linktext and target. (deactivatable)


== Description ==

B09 Link to existing Content is a small plugin that is ment to improve the wordpress "Link to existing Content"-functionality.

* Default Behaviour is seamless, just continue using the Link PopUp as always
* Consider installing the plugin "Search Everything" for full control over the search results when using "Link to existing Content"
* Makes internal links more future-proof by using a shortcode with the post id. Just select the post you want to link to and click "Add Link", and the shortcode gets pasted to your editor and automatically handled in your themes.
* Filters for this plugin:
 - Control if the shortcode functionality should be active or not: `link_to_existing_content_use_shortcode`
 - Control which post types should be searched: `link_to_existing_content_post_types`
 - Control which taxonomies should be searched: `link_to_existing_content_taxonomies`

= Please Vote and Enjoy =
Your votes really make a difference! Thanks.


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
* The shortcode appears directly in the url field, so it doesn't suprise the user if it get pasted to the editor
* Changed default shortcode name to the more generic "link". It will stay this in the future
* Added custom css file

= 1.0.0 =
* Initial Release.


== Upgrade Notice ==

= 1.0.0 =
