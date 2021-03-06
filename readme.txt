=== Mark New Posts ===
Contributors: tssoft
Tags: new posts, unread posts, statistics, title, easy
Requires at least: 3.3
Tested Up To: 4.2.2
Stable tag: 5.6.4
License: MIT


== Description ==

Highlight and count unread WordPress posts.

Features:

 * Works right out of the box
 * 3 default ways of highlighting a post: an orange circle, a "New" label or an image near the post's title
 * It doesn't simply compare dates of posts with the date of user's last visit, but checks for each individual post that the user have seen it
 * Posts may be marked as read after showing the excerpt or only after opening the full version
 * A function that can be called from WordPress themes to determine if a post is unread
 * A function to show total number of unread posts


== Frequently Asked Questions ==

= 1. How can I see that the plugin works? =

1. Install the plugin and make sure that it is activated on your website.
2. Visit your blog's main page, or the page where the posts are shown.
3. Add a new post to your blog.
4. Look at the posts page once again. The new post will have an orange circle to the left of its title. Once you visit the page once again, it will disappear.

= 2. What do I need the mnp_is_new_post() and mnp_new_posts_count() functions for? =

These two functions can be useful if you are developing a WordPress theme.
~~~~
mnp_is_new_post($post);
~~~~
Returns true if specific post is unread, otherwise false.
Parameters: $post (optional) - post ID or object.
~~~~
mnp_new_posts_count($query);
~~~~
Returns total number of unread posts.
Parameters: $query (optional) - WP_Query query string.
Example:
~~~~
echo mnp_new_posts_count('cat=1');
~~~~
This will show the number of unread posts in category with id = 1.

== Screenshots ==

1. Settings page
2. Marker type: Circle
3. Marker type: "New" text
4. Marker type: Image (default)

== Changelog ==

= 5.6.4 =
 * New marker type: flag (unicode character)
 * New option: the marker can be placed before or after the title of a post
 * New marker type: custom image
 * Fixed bug: after opening a post's preview it's getting marked as read
 * Fixed bug: sometimes the marker falls on another line
 * Fixed: marker gets wrapped on new line in post's navigation block

= 5.5.12 =
 * i18n
 * Added "Mark post as read only after opening" option
 * New marker type: image. "Label New Blue" icon by [Jack Cai](http://www.doublejdesign.co.uk/), [CC BY-ND 3.0](https://creativecommons.org/licenses/by-nd/3.0/) license

= 5.5.8 =
 * This plugin is based upon [KB New Posts 0.1](http://adambrown.info/b/widgets/tag/kb-new-posts/) by [Adam R. Brown](http://adambrown.info/)
 * New functions for using in WordPress themes: *mnp_is_new_post* and *mnp_new_posts_count*
 * 2 new ways of highlighting unread posts