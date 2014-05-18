<?php get_header(); ?>
	<div id="nopage">
		<h1>404</h1>
		<div id="search">
			<form id="searchform" method="get" action="<?php bloginfo('home'); ?>">
				<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" size="30" />
				<button type="submit"><?php _e("Search"); ?></button>
			</form>
		</div>
	</div>
<?php get_footer(); ?>