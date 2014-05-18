<?php
global $post;
$post 	= get_post(get_query_var('p'));
$md5	= get_query_var('qiniu_image');


if($post){
	// do nothing
}else{
	wp_die('该日志不存在','该日志不存在',array( 'response' => 404 ));
}

$preg = preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', do_shortcode($post->post_content), $matches);

$url = '';
if ($preg) {
	foreach ($matches[1] as $image_url) {
		if($md5 == md5($image_url)){
			$url = $image_url;
			break;
		}
	}
}

if(!$url){
	wp_die('该日志没有图片','该日志没有图片',array( 'response' => 404 ));
}

if(isset($_GET['url']) && $_GET['url']){
	echo $url;
}else{
	if($url){

		//header("HTTP/1.1 200 OK");
		//header("Content-Type: image/jpeg");
		//imagejpeg(imagecreatefromjpeg($url));
		//exit;

		$image = wp_remote_get(trim($url));

		if(is_wp_error($image)){
			wp_die('原图不存在','原图不存在',array( 'response' => 404 ));
		}else{
			header("HTTP/1.1 200 OK");
			header("Content-Type: image/jpeg");
			imagejpeg(imagecreatefromstring($image['body']));
		}

	}else{
		wp_die('该日志没有图片','该日志没有图片',array( 'response' => 404 ));
	}
}