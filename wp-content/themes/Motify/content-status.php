<div class="date"><a href="<?php the_permalink() ?>"><?php the_time(' j  F,  Y'); ?></a></div>
<div class="post-content">
	<a href="<?php the_permalink() ?>"><?php echo get_avatar($post->post_author, 28); ?></a>
	<?php the_content(''); ?>
</div>