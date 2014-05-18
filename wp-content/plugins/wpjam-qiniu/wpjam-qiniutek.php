<?php
/*
Plugin Name: WPJAM 七牛镜像存储
Description: 使用七牛云存储实现 WordPress 博客静态文件 CDN 加速！
Plugin URI: http://blog.wpjam.com/project/wpjam-qiniutek/
Author: Denis
Author URI: http://blog.wpjam.com/
Version: 1.1.1
*/

define('WPJAM_QINIUTEK_PLUGIN_URL', plugins_url('', __FILE__));
define('WPJAM_QINIUTEK_PLUGIN_DIR', WP_PLUGIN_DIR.'/'. dirname(plugin_basename(__FILE__)));

if(!function_exists('wpjam_option_page')){
	include(WPJAM_QINIUTEK_PLUGIN_DIR.'/include/wpjam-setting-api.php');
}

if(!function_exists('get_term_meta')){
	include(WPJAM_QINIUTEK_PLUGIN_DIR.'/include/simple-term-meta.php');
	register_activation_hook( __FILE__,'simple_term_meta_install');
}

include(WPJAM_QINIUTEK_PLUGIN_DIR.'/qiniutek-options.php');

if(wpjam_qiniutek_get_setting('advanced')){
	include(WPJAM_QINIUTEK_PLUGIN_DIR.'/term-thumbnail.php');
}
include(WPJAM_QINIUTEK_PLUGIN_DIR.'/wpjam-posts.php');

if(!function_exists('wpjam_post_thumbnail')){
	include(WPJAM_QINIUTEK_PLUGIN_DIR.'/wpjam-thumbnail.php');
}

function wpjam_qiniutek_get_setting($setting_name){
	$option = wpjam_qiniutek_get_option();
	return wpjam_get_setting($option, $setting_name);
}

function wpjam_qiniutek_get_option(){
	$defaults = wpjam_qiniutek_get_default_option();
	return wpjam_get_option('wpjam-qiniutek',$defaults);
}

//定义在七牛绑定的域名。
if(wpjam_qiniutek_get_setting('host')){
	define('CDN_HOST',wpjam_qiniutek_get_setting('host'));
}else{
	define('CDN_HOST',home_url());
}
if(wpjam_qiniutek_get_setting('local')){
	define('LOCAL_HOST',wpjam_qiniutek_get_setting('local'));
}else{
	define('LOCAL_HOST',home_url());
}

if(!is_admin()){
	add_action("wp_loaded", 'wpjam_qiniutek_start_ob_cache');

	if(wpjam_qiniutek_get_setting('remote') && get_option('permalink_structure')){
		add_filter('the_content', 'wpjam_qiniutek_content',1);
	}
}

if(get_option('permalink_structure')){
	add_action('generate_rewrite_rules',	'wpjam_qiniutek_generate_rewrite_rules');
	add_filter('query_vars', 				'wpjam_qiniutek_query_vars');
	add_action('template_redirect', 		'wpjam_qiniutek_template_redirect', 5);
}

function wpjam_qiniutek_start_ob_cache(){
	ob_start('wpjam_qiniutek_cdn_replace');
}

function wpjam_qiniutek_cdn_replace($html){
	$cdn_exts	= wpjam_qiniutek_get_setting('exts');
	$cdn_dirs	= str_replace('-','\-',wpjam_qiniutek_get_setting('dirs'));

	$html = apply_filters('wpjam_html_replace',$html);

	if($cdn_dirs){
		$regex	=  '/'.str_replace('/','\/',LOCAL_HOST).'\/(('.$cdn_dirs.')\/[^\s\?\\\'\"\;\>\<]{1,}.('.$cdn_exts.'))([\"\\\'\s\?]{1})/';
		$html =  preg_replace($regex, CDN_HOST.'/$1$4', $html);
	}else{
		$regex	= '/'.str_replace('/','\/',LOCAL_HOST).'\/([^\s\?\\\'\"\;\>\<]{1,}.('.$cdn_exts.'))([\"\\\'\s\?]{1})/';
		$html =  preg_replace($regex, CDN_HOST.'/$1$3', $html);
	}
	return $html;
}

function wpjam_qiniutek_content($content){
	if(get_post_meta(get_the_ID(),'disable_remote_image','true')){
		return $content;
	}else{
		return preg_replace_callback('|<img.*?src=[\'"](.*?)[\'"].*?>|i','wpjam_qiniutek_replace_remote_image',do_shortcode($content));
	}
}

function wpjam_qiniutek_replace_remote_image($matches){
	$image_url = $matches[1];

	if(empty($image_url)) return;

	$pre = apply_filters('pre_qiniu_remote',false,$image_url);

	if($pre == false && strpos($image_url,LOCAL_HOST) === false && strpos($image_url,CDN_HOST) === false && strpos($image_url,'.gif') === false){
		$md5 = md5($image_url);
		return str_replace($image_url, CDN_HOST.'/qiniu/'.get_the_ID().'/image/'.$md5.'.jpg', $matches[0]);
	}

	return $matches[0];
}

function wpjam_qiniutek_generate_rewrite_rules($wp_rewrite){
    $new_rules['qiniu/([^/]+)/image/([^/]+).jpg?$']	= 'index.php?p=$matches[1]&qiniu_image=$matches[2]';
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

function wpjam_qiniutek_query_vars($public_query_vars) {
    $public_query_vars[] = 'qiniu_image';
    return $public_query_vars;
}

function wpjam_qiniutek_template_redirect(){
    $qiniu_image = get_query_var('qiniu_image');

    if($qiniu_image){
    	include(WPJAM_QINIUTEK_PLUGIN_DIR.'/template/image.php');
    	exit;
	}
}

add_action( 'wp_enqueue_scripts', 'wpjam_qiniutek_enqueue_scripts', 1 );
function wpjam_qiniutek_enqueue_scripts() {

	if(wpjam_qiniutek_get_setting('jquery')){
		wp_deregister_script( 'jquery' );
	    wp_register_script( 'jquery', 'http://cdn.staticfile.org/jquery/2.1.0/jquery.min.js', array(), '2.1.0' );
	}else{
		wp_deregister_script( 'jquery-core' );
	    wp_register_script( 'jquery-core', 'http://cdn.staticfile.org/jquery/1.11.0/jquery.min.js', array(), '1.10.2' );

		wp_deregister_script( 'jquery-migrate' );
	    wp_register_script( 'jquery-migrate', 'http://cdn.staticfile.org/jquery-migrate/1.2.1/jquery-migrate.min.js', array(), '1.2.1' );
	}
}

//使用七牛缩图 API 进行裁图
add_filter('wpjam_thumbnail','wpjam_get_qiniu_thumbnail',10,4);
function wpjam_get_qiniu_thumbnail($img_url, $width=0, $height=0, $crop=1, $quality='',$format=''){
	if(CDN_HOST != home_url()){
		$img_url = str_replace(LOCAL_HOST, CDN_HOST, $img_url);

		if($width || $height){
			$arg = 'imageView/';

			$crop_arg	= $crop?'1':'2';
			$arg 		.= $crop_arg;

			if($width)		$arg .= '/w/'.$width;
			if($height) 	$arg .= '/h/'.$height;
			if($quality)	$arg .= '/q/'.$quality;
			if($format)		$arg .= '/format/'.$format;

			$img_url = add_query_arg( array($arg => ''), $img_url );
		}

		$img_url = apply_filters('qiniu_thumb',$img_url,$width,$height,$crop,$quality,$format);
	}elseif(wpjam_qiniutek_get_setting('timthumb')){
		$timthumb_url = WPJAM_QINIUTEK_PLUGIN_URL.'/include/timthumb.php';

		if($width || $height){
			$arg = array();
			$arg['src']	= $img_url;
			$arg['zc']	= 0;
			if($crop)	$arg['zc']	= 1;
			if($width)	$arg['w']	= $width;
			if($height)	$arg['h']	= $height;

			$img_url = add_query_arg($arg,$timthumb_url);
		}
	}

	return $img_url;
}