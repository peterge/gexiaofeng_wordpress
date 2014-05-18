<?php get_header(); ?>
	<div class="posts">
		<?php while ( have_posts() ) : the_post(); ?>
			<div <?php post_class(); ?>>
				<?php if ( is_sticky() ) : ?>
					<div class="post-content"><h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">【置顶】 <?php the_title(); ?></a></h2></div>
				<?php else : ?>
					<?php get_template_part( 'content', get_post_format() ); ?>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
	<?php  if ( $wp_query->max_num_pages > 1 ) : ?>
		<div class="pagination"><?php pagenavi(); ?></div>
	<?php endif; ?>
<?php get_footer(); ?>