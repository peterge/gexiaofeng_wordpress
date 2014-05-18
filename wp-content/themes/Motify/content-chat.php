<?php
	$lines = preg_split("/[\r\n]+/", $post->post_content);
?>
					<div class="date-container"><span class="date"><?php the_time(' j  F,  Y'); ?></span></div>
					<div class="postContent">
					<table><tbody>
			<?php 
			if(is_array($lines)) {
				$i=2;
				foreach($lines as $line) {
					if(trim($line) != '' ) 
			?>
				<p <?php if( $i%2 == 1 ){ echo 'class="even"';}else {echo 'class="odd"';}; ?>><?php echo $line;$i++ ?></p>
			<?php
				}
			}
			?>
					</tbody></table>
					</div>
					<div class="postInfo">
						<div class="postTags"><h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2></div>
						<div class="postNotes"><?php comments_popup_link(__('0 评论'), __('1 评论'), __('%  评论')); ?></div>
						<div class="clear"></div>
					</div>