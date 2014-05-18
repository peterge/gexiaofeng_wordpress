<?php
//自定义背景
add_custom_background();

// 特色图片
add_theme_support( 'post-thumbnails' );
add_image_size( 'home-tb', 500, 280, true);
add_image_size( 'home-tb', 750, 480, true);

// 文章形式
add_theme_support( 'post-formats', array( 'aside','gallery','link','image','quote','status','video','audio','chat' ) );

// 链接自动识别播放
function auto_player_urls($c) {
    $s = array('/^<p>(http:\/\/.*\.mp3)<\/p>$/m' => '<p><embed class="mp3_player" src="'.get_bloginfo("template_url").'/mp3_player.swf?audio_file=$1&amp;color=ffffff" width="207" height="30" type="application/x-shockwave-flash"></embed></p>',
    '/^<p>(http:\/\/.*\.swf)<\/p>$/m' => '<p><embed class="swf_player" src="$1" width="500" height="280" type="application/x-shockwave-flash"></embed></p>');
    foreach($s as $p => $r){
        $c = preg_replace($p,$r,$c);
    }
    return $c;
}
add_filter( 'the_content', 'auto_player_urls' );

// 去除默认相册样式
add_filter( 'use_default_gallery_style', '__return_false' );

// 自定义HTML编辑器按钮
add_action('admin_print_scripts', 'my_quicktags');
function my_quicktags() {
    wp_enqueue_script(
        'my_quicktags',
        get_stylesheet_directory_uri().'/my-quicktags.js',
        array('quicktags')
    );
}

//自定义表情路径
function custom_smilies_src($src, $img){
    return get_option('home') . '/wp-content/themes/Motify/smilies/' . $img;
}
add_filter('smilies_src', 'custom_smilies_src', 10, 2);

//注册头部导航
if ( function_exists('register_nav_menus') ) {
	register_nav_menus(array('primary' => '头部导航栏'));
}

//自定义评论结构
function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
   global $commentcount;
   if(!$commentcount) {
	   $page = ( !empty($in_comment_loop) ) ? get_query_var('cpage')-1 : get_page_of_comment( $comment->comment_ID, $args )-1;
	   $cpp=get_option('comments_per_page');
	   $commentcount = $cpp * $page;
	}
?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-body">
			<div class="comment-author"><?php echo get_avatar( $comment, $size = '26'); ?></div>
			<div class="comment-head">
				<span class="name"><?php printf(__('%s'), get_comment_author_link()) ?> ： </span>
				<div class="date"><?php if(!$parent_id = $comment->comment_parent) {printf(__('%1$s %2$s'), get_comment_date('Y/n/j'),  get_comment_time('H:i:G'));} ?> <?php if(!$parent_id = $comment->comment_parent) {printf('#%1$s', ++$commentcount);} ?></div>
				<div class="comment-entry"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __('回复')))) ?> <?php comment_text() ?> </div>
			</div>

     </div>
<?php
}

//* Mini Pagenavi v1.0 by Willin Kan.
function pagenavi( $p = 2 ) {
  if ( is_singular() ) return;
  global $wp_query, $paged;
  $max_page = $wp_query->max_num_pages;
  if ( $max_page == 1 ) return;
  if ( empty( $paged ) ) $paged = 1;
echo "<div id=\"pagenavi\">\n";
  if ( $paged > $p + 1 ) p_link( 1, '最前页' );
  if ( $paged > $p + 2 ) echo ' <span class="dots"> ... </span>';
  for( $i = $paged - $p; $i <= $paged + $p; $i++ ) {
    if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<a class='page-numbers current'>{$i}</a> " : p_link( $i );
  }
  if ( $paged < $max_page - $p - 1 ) echo ' <span class="dots"> ... </span>';
  if ( $paged < $max_page - $p ) p_link( $max_page, '最后页' );
echo "</div>\n";
}
function p_link( $i, $title = '' ) {
  if ( $title == '' ) $title = "第 {$i} 页";
  echo "<a class='page-numbers' href='", esc_html( get_pagenum_link( $i ) ), "' title='{$title}'>{$i}</a> ";
}
	
?>