</div>
<div class="footer" class="clear">
    © 2014 | <a href="http://immmmm.com/wordpress-theme-motify.html">Motify Theme Moded by Linmumu</a>
    <br><br>
    Powered by <a href="http://wordpress.org/" target="_blank">Wordpress</a>
</div>

<div class="go-top"><a href="#"><i class="icon-arrow-circle-up"></i></a></div>

<script type="text/javascript" src="http://libs.baidu.com/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
	if($('.post-content a[rel!=link]:has(img)').length > 0){
		$.getScript("<?php bloginfo('template_url'); ?>/slimbox2.js");
	};

});
</script>

<?php if( is_singular() ){ ?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/comments-ajax.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('#comments .comment-body').dblclick(function(){
		var crl=$('#cancel-comment-reply-link');
		if($(this).next('#respond').length > 0) {crl.click()
		}else{$(this).find('.comment-reply-link').click();crl.text("取消 @"+$(this).find('.name').text());
		}
		return false
	});
	$('#comments .live').live('dblclick',function(){$(this).next().children('a').click()});
	$(".comment-head .name a").attr({target:"_blank"});
});
</script>

<?php } ?>

<?php wp_footer(); ?>
</div>

</body>
</html>