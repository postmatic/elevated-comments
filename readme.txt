=== Elevated Comments ===
Contributors: vernal, carlalexander, ronalfy
Tags: comments, engagement, long-tail
Donate link: http://gopostmatic.com
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.1.6
License: GPLv2

Bring the best conversation to the top of the page with Elevated Comments.

== Description ==
Comments can be the best part of a post. So why are they always buried?

Elevated comments uses language analysis and machine learning to identify the most relevant and thoughtful comment on each of your posts. The comment is then automatically inserted near the top of the post as a simple sidebar pull quote. 

= Learn more on our site =
Find out more about how Elevated Comments work at [http://elevated.gopostmatic.com](http://elevated.gopostmatic.com).

== Installation ==
Elevated comments is quite simple. Once activated, it will run automatically. It does not affect your old posts, you will only notice it our new posts and comments moving forward.

After a new post receives more than three comments the best will automatically be chosen and displayed at about 1/3 of the way through the post.

= An important consideration =

The contents of your comments will be sent offsite for evaluation. Elevated Comments utilizes the [Comment-IQ API](comment-iq.com) for rating both your posts and comments. Please be sure you are adhering to your local privacy laws.


There are a few options available:

= Enable or disable elevated comments =
Visit Settings > Elevated Comments in order to set the default behaviour to insert elevated comments automatically or not.

= Manually place the comment =
Comments can be manually placed on posts by using the following shortcode:
[elevated-comment]

= Disable comment elevation per post =
If you would like to disable comment elevation on a post you can do so using a checkbox on the Discussion Settings metabox on the Edit Post screen.

== Frequently Asked Questions ==
= Will elevated comments show up on my old posts? =
No. The plugin does not retroactively rate your old posts or comments. It will only affect new content moving forward.

= Can I change the formatting or markup of the elevated comment? =
Yes. There are a number of classes in place for modifying the styling. You can also override the default template by copying it to your theme directory.

= Can I manually choose which comment gets elevated? =
Not at this time.

= How is comment relevance determined? Can my users vote? =
Computers are so much better at this than humans. Trust us.

= Can I have more than one elevated comment proposed? =
Not yet. There is only one winner. The best comment.

== Screenshots ==
1. The best comment from a post is automatically determined by magical robots and placed into the post content about 1/3 of the way down the page.
2. A checkbox on the Discussion Settings metabox allows you to enable or disable elevated comments per post
3. Or, use a shortcode to place the elevated comment manually.

== Changelog ==

= 1.1.6 =
* Released 2017-05-08
* Bug Fix: Fixed divided by zero bug.
* Tweak: Removed the default background image from the elevated comment.
* New: Added filter to include comments by the post author, disabled them by default (elevated_allow_post_author)

= 1.1.5 =
* Released 2016-06-12
* Bug Fix: Fixing HTML being stripped out of content.

= 1.1.3 =
* Released 2016-05-26
* Bug Fix: Elevated Comments stripped out shortcodes.

= 1.1.2 =
* Released 2016-05-10
* Fixed bug where elevated comments aren't automatically showing.

= 1.1.1 = 
* Released 2016-05-09
* New settings screen with universal option for enabling/disabling automatic elevation
* Better styling of elevated comments in Postmatic emailed posts

= 1.1.0 = 
* Released 2016-04-28
* Switching to self-contained API for better reliability
* Filter to create your own API endpoint
* Add better support for nicknames/display names elevated comment
* Bug fix: Commenting on a post will now update the post and the comment with the API
* Bug fix: Changing comment insertion to wp_insert_comment to prevent conflicts
* CSS fixes
* Can now render in multiple contexts other than in the main query

= 1.0.0 =
* Released 2015-04-15
* Initial Release