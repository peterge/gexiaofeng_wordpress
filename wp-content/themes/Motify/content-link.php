<?php
	$desc = false;
	$anchor_text = $href = filter_var(trim($post->post_content), FILTER_VALIDATE_URL);
	$matches = array();
	if(!$href && preg_match('/<a [^>]*href=[\"\']?([^\"\'\s]+)/i', $post->post_content, $matches)) {
		$anchor_text = $href = $matches[1];
		$desc = get_the_excerpt();
	}
	if($post->post_title) {
		$anchor_text = $post->post_title;
	}
?>
<div class="date"><a href="<?php the_permalink() ?>"><?php the_time(' j  F,  Y'); ?></a></div>
<h2><a href="<?php echo $href; ?>" title="<?php echo $anchor_text; ?>"><?php echo $anchor_text; ?></a></h2>
<div class="post-content"><?php the_content(''); ?></div>
<div class="post-footer">
	<div class="note-count"><?php comments_popup_link(__('0 note'), __('1 note'), __('%  note')); ?></div>
</div>