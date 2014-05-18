<div class="date"><a href="<?php the_permalink() ?>"><?php the_time(' j  F,  Y'); ?></a></div>
<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
<div class="post-content"><?php the_content(''); ?></div>
<div class="post-footer">
	<div class="tags"><?php the_tags(' #',' #',''); ?></div>
	<div class="note-count"><?php comments_popup_link(__('0 note'), __('1 note'), __('%  note')); ?></div>
</div>