<?php get_header(); ?>
	<div id="posts">
		<?php while ( have_posts() ) : the_post(); ?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<div class="postContent">
				<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
				<?php the_content(''); ?>
			</div>
			<div class="postInfo">
				<div class="postTags"><?php the_time('Y-m-d'); ?></div>
				<br class="clear">
			</div>
		</div>
		<?php endwhile; ?>
	</div>
<?php get_footer(); ?>