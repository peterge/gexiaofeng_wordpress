<?php
		$matches = array();
		if(preg_match('/<cite(?:>|[^>]+>)((?!<\/cite>)[\w\W]*)<\/cite>/i',  $post->post_content, $matches)) {
			$source = $matches[1];
		} else if($post->post_title) {
			$source = $post->post_title;
		} else {
			$source = null;
		}
		$quote_type = $quote = null;
		$matches = array();
		if(preg_match('/<(blockquote|q)(?:>|[^>]+>)((?!<\/(?:blockquote|q)>)[\w\W]*)?<\/(?:blockquote|q)>/i',  $post->post_content, $matches)) {
			$quote_type = $matches[1];
			$quote = $matches[2];
		} else {
			$quote = $post->post_content;
			$quote_type = 'blockquote';
		}
		$quote = "<$quote_type><span class=\"quote-left\">&ldquo;</span>" . trim($quote, '\u0022\u2018\u2019\u0027\u201c\u201d') . "</$quote_type>";
?>

<div class="date"><a href="<?php the_permalink() ?>"><?php the_time(' j  F,  Y'); ?></a></div>
<h2><cite><?php echo $source; ?></cite></h2>
<div class="post-content"><?php echo $quote; ?></div>
<div class="post-footer">
	<div class="tags"><?php the_tags(' #',' #',''); ?></div>
	<div class="note-count"><?php comments_popup_link(__('0 note'), __('1 note'), __('%  note')); ?></div>
</div>