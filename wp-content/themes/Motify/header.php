<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.2.3.min.js"></script>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php global $page, $paged;wp_title( '|', true, 'right' );bloginfo( 'name' );$site_description = get_bloginfo( 'description', 'display' );if ( $site_description && ( is_home() || is_front_page() ) ) echo " | $site_description";if ( $paged >= 2 || $page >= 2 ) echo ' | ' . sprintf( __( '第 %s 页'), max( $paged, $page ) );?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="Feedsky RSS 2.0" href="<?php bloginfo('rss2_url'); ?>"/>
<?php wp_head(); ?>
</head>

<body>

<div id="header">
    <div class="header-container">
        <div class="nav">
            <h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a></h1>
            <?php wp_nav_menu( array( 'container' => 'false','items_wrap' => '%3$s')); ?>
        </div>
        <div class="description"><?php bloginfo('description'); ?></div>
        <ul class="social-links">
            <li><a href="https://twitter.com/linmumu" target="_blank" rel="nofollow"><i class="icon-twitter"></i><span>Twitter</span></a></li>
            <li><a href="http://weibo.com/41548682" target="_blank" rel="nofollow"><i class="icon-sina-weibo" target="_blank" rel="nofollow"></i><span>Weibo</span></a></li>
            <li><a href="https://instagram.com/lmm214" target="_blank" rel="nofollow"><i class="icon-instagram" target="_blank" rel="nofollow"></i><span>Instagram</span></a></li>
            <li><a href="http://feed.immmmm.com/" target="_blank"><i class="icon-feed" target="_blank" rel="nofollow"></i><span>Feed</span></a></li>
        </ul>
    </div>
</div>

<div class="wrapper">
    <div class="content">