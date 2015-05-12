=== Mark New Posts ===
Contributors: tssoft
Tags: new posts, unread posts
Requires at least: 3.3
Tested Up To: 4.2.2
Stable tag: 5.5.8
License: MIT



== Description ==

Highlight and count unread WordPress posts.

Features:

 * Works right out of the box
 * Two default ways of highlighting a post: an orange circle or a "New" label to the left of the post's title
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

== Changelog ==

= 5.5.8 =
 * This plugin is based upon [KB New Posts 0.1](http://adambrown.info/b/widgets/tag/kb-new-posts/) by [Adam R. Brown](http://adambrown.info/)
 * New functions for using in WordPress themes: *mnp_is_new_post* and *mnp_new_posts_count*
 * 2 new ways of highlighting unread posts