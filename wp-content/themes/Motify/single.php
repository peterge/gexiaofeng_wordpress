<?php get_header(); ?>
	<div class="posts">
		<?php while ( have_posts() ) : the_post(); ?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<?php get_template_part( 'content', get_post_format() ); ?>
		</div>
		<?php endwhile; ?>
		<?php comments_template(); ?>
	</div>
<?php get_footer(); ?>