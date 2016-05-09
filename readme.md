Elevated Comments for WordPress
======================

Bring the best conversation to the top of the page with Elevated Comments.

## Description

Comments can be the best part of a post. So why are they always buried?

Elevated comments uses language analysis and machine learning to identify the most relevant and thoughtful comment on each of your posts. The comment is then automatically inserted near the top of the post as a simple sidebar pull quote. 

Find out more about how Elevated Comments work at <a href="http://elevated.gopostmatic.com">http://elevated.gopostmatic.com</a>.

## Installation

Elevated comments is quite simple. Once activated, it will run automatically. It does not affect your old posts, you will only notice it our new posts and comments moving forward.

After a new post receives more than three comments the best will automatically be chosen and displayed at about 1/3 of the way through the post.

#### An Important Consideration

The contents of your comments will be sent offsite for evaluation. Elevated Comments utilizes the [Comment-IQ API](comment-iq.com) for rating both your posts and comments. Please be sure you are adhering to your local privacy laws.


There are two options available, each can be found on the edit > post screen.

#### Manually place the comment

Comments can be manually placed on posts by using the following shortcode:
`[elevated-comment]`

#### Disable comment elevation per post

If you would like to disable comment elevation on a post you can do so using a checkbox on the Discussion Settings metabox.

## Frequently Asked Questions

#### Will elevated comments show up on my old posts?

No. The plugin does not retroactively rate your old posts or comments. It will only affect new content moving forward.

#### Can I change the formatting or markup of the elevated comment?

Yes. There are a number of classes in place for modifying the styling. You can also override the default template by copying it to your theme directory.

#### Can I manually choose which comment gets elevated?

Not at this time.

#### How is comment relevance determined? Can my users vote?

Computers are so much better at this than humans. Trust us.

#### Can I have more than one elevated comment proposed?

Not yet. There is only one winner. The best comment.

## Hooks (Actions and Filters )

There are several filters.

### elevated_api_endpoint

```php
/**
* Filter: elevated_api_endpoint
*
* Replace the endpoint with your own.
*
* @since 1.1.0
*
* @param string URL to the endpoint.
*/
$this->endpoint_base  = apply_filters( 'elevated_api_endpoint', $this->endpoint_base );
```

You would use the above filter if <a href="https://github.com/comp-journalism/commentIQ/tree/master/CommentAPIcode">you want to use your own API endpoint instead of ours</a>.

Example code would be:

```php
add_filter( 'elevated_api_endpoint', function() {
    return 'http://domain.com/commentIQ/v1';
} );
```

### elevated_show_in_content

```php
/**
 * Filter: elevated_show_in_content
 *
 * Whether to show the elevated comment.
 *
 * @since 1.1.1
 *
 * @param bool  $comment_show_in_content true to show in content, false if not.
 * @param int   Comment ID
 * @param int   Comment Post ID
 */
$comment_show_in_content = (bool)apply_filters( 'elevated_show_in_content', $comment_show_in_content, $comment->comment_ID, $comment->comment_post_ID );
```

Example code would be: 

```php
add_filter( 'elevated_show_in_content', function( $show, $comment_id, $post_id ) {
    //return true or false
    return false;
}, 10, 3 );
