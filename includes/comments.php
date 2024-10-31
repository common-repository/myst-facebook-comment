<?php
/**
 * The template for displaying Facebook plugin comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 */

global $post;
?>

<div id="comments" class="comments-area">
    <div class="fb-comments" 
        data-colorscheme="<?php echo get_option('myst_fbc_colour_scheme') ?>" 
        data-href="<?php echo get_permalink($post) ?>" 
        data-mobile="<?php echo get_option('myst_fbc_comment_responsive')  ? 'auto' : 'false'; ?>" 
        data-numposts="<?php echo get_option('myst_fbc_num_posts')  ? get_option('myst_fbc_num_posts') : 5; ?>" 
        data-width="<?php echo get_option('myst_fbc_comment_width')  ? get_option('myst_fbc_comment_width') : '550'; ?>"
        ></div>
</div>