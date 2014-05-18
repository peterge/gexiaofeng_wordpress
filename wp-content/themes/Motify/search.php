<?php get_header(); ?>
	<div id="posts">
		<div class="hentry"><div class="postContent"><h2><?php _e("Search"); ?>: <?php the_search_query(); ?></h2></div></div>
		<?php while ( have_posts() ) : the_post(); ?>
		<div <?php post_class(); ?>>
				<?php get_template_part( 'content', get_post_format() ); ?>
		</div>
		<?php endwhile; ?>
	</div>
	<?php  if ( $wp_query->max_num_pages > 1 ) : ?>
		<?php pagenavi(); ?>
	<?php endif; ?>
<?php get_footer(); ?>