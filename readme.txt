Plugin Name: Hajujo Plugin
Description: A plugin to add a custom post type, add a widget to display set number of posts from the custom post type (Daily Food Specials), and to add a shortcode - [hajujoshortcode].
Plugin URI: http://www.hajujo.ca
Author: Harinder Mundh, Junaid Siddiqui, Joseph Adamu
Author URI: http://www.hajujo.ca
License: HJJ
Version: 1.0


== WARNING (ISSUES WE EXPERIENCED) ==
- If your custom post type posts give you a 404 - No Page Found error, please complete the following steps:
	1. Hover over "Settings"
	2. Click "Permalinks"
	3. Select either "Plain", or "Custom Structure" and input /%post_id%/%postname%/

- If your images don't work in the plugin widget, please try another browswer, or clear your cache, browsing history, etc. 


== Description ==

This Plugin has been created specifically for the user/our client to:
	- Add a custom post type (Daily Food Specials for our client - Hajujo)
	- Add a widget via the admin tool/screen in Wordpress that will display a set number of the custom post type posts (1-7) in the user/client's sidebar
	- Add a shortcode ([hajujoshortcode]) to any page/post that will display, and link to, all custom post type posts.


== Instructions (Assuming user/client has already installed the plugin) ==

- To Add Custom Post Type (Daily Food Special) Posts that will be queried and displayed in the Widget/Shortcode:
	1. On your WP-Admin page (Dashboard), you will see the custom post type on the left side panel (Daily Food Specials).
	2. If you hover over it, it will give you the option to view "Daily Food Specials", or to "Add New".
	3. You will click "Add New" and enter the title of the post, the content related to the post, and set a feature image by clicking "Set feature image" on the right side of the screen under the "Publish" settings.

- To Add the Widget that will display custom post type (Daily Food Specials) posts:
	1. Hover over "Appearance" and select "Widgets"
	2. On the left side of the page, you will find the custom post type widget called "Daily Food Specials". Click the Daily Food Specials, and select "Add Widget". This will add it to the sidebar.
	3. Once added, the user/client will be prompted to enter a widget "title" that will display on the sidebar, and the amount of custom post type posts that they would like to be displayed (Between 1 and 7).
	4. Widget with the custom post type posts should now be visible on the homepage sidebar

- To use the Shortcode to display a list of the custom post type posts
	1. On your WP-Admin (Dashboard), hover over "Posts" and click "Add New Post".
	2. Give your post a title at the top
	3. In the body of the post, use the shortcode "[hajujoshortcode]" to display all the custom post type posts (Daily Food Specials).

== Frequently Asked Questions ==

Q:Do I need to learn coding to operate/use this?

A:No, all you need is to simply follow the examples and steps. Once the plugin is installed, it is extremely easy to use.

Q:I heard from someone they will make my website slow?
A:No, the plugin will not affect any speeds or data usage.