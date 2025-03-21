<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('请勿直接加载此页。谢谢！');
	if ( post_password_required() ) { ?>
		<p class="no-comments">必须输入密码，才能查看评论！</p>
	<?php
		return;
	}
?>
<div id="comments" class="comments-area">
<?php if ( have_comments() ) : ?>
	<h3 class="subtitle"><span class="comments_title"><?php the_title(); ?>：</span>目前有 <?php comments_number('', '1 条评论', '% 条评论' );?></h3>
	<span id="cp_post_id" style="display:none;"><?php the_ID(); ?></span>
	<div id="pagetext">
	<ol class="comment-list">
	<?php wp_list_comments('type=comment&callback=weisay_comment&end-callback=weisay_end_comment&max_depth=' .get_option('thread_comments_depth'). ' '); ?>
	</ol>
	<div class="pagination" id="commentpager"><?php paginate_comments_links(); ?></div>
	<div class="clear"></div>
	</div>
	<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function comment_page_ajas(){
	jQuery('#commentpager a').click(function(){
		var post_id = jQuery('#cp_post_id').html() //当前文章ID-- post_id
		//文章ID获得
		//获取欲取得的评论页页码
		var compageUrl = jQuery(this).attr("href");
		var page_id = compageUrl.match(/page[\-|=][0-9]{1,4}/);
		var arr_temp_1 = page_id[0].split(/\-|=/);
		var page_id = arr_temp_1[1];
		jQuery.ajax({
			url: compageUrl,
			type:"POST",
			data:"action=compageajax&postid="+post_id+"&pageid="+page_id,
			beforeSend:function() {
				document.body.style.cursor = 'wait';
				if (jQuery("#cancel-comment-reply-link")) // 取消未回复的评论
				jQuery("#cancel-comment-reply-link").trigger("click");
				jQuery('#commentpager').html('<font class="ajaxcomm">正在努力为您载入中...</font>');
			},
			error:function (xhr, textStatus, thrownError) { 
				alert("readyState: " + xhr.readyState + " status:" + xhr.status + " statusText:" + xhr.statusText +" responseText:" +xhr.responseText + " responseXML:" + xhr.responseXML + " onreadystatechange" +xhr.onreadystatechange);
				alert(thrownError);
			},
			success: function (data) {
				jQuery('#pagetext').html(data);
				document.body.style.cursor = 'auto';
				jQuery('html,body').animate({scrollTop:jQuery('#comments').offset().top}, 800);
				comment_page_ajas();
			}
		});
		return false;
	});
})
// ]]>
</script>
<?php endif; // have_comments() ?>
<?php if ( ('0' == $post->comment_count) && comments_open() ) : ?>
<h3 class="subtitle"><span class="comments_title"><?php the_title(); ?>：等您坐沙发呢！</span></h3>
<?php endif; ?>
<?php if ( comments_open() ) : ?>
<div id="respond_box">
<div id="respond" class="comment-respond">
<h3 id="reply-title" class="comment-reply-title subtitle">发表评论</h3><small><?php cancel_comment_reply_link('[点击取消回复]'); ?></small>
<?php if (weisay_option('wei_gravatar') == 'two') : ?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function() {
var gravatarurl= 'https://weavatar.com/avatar/';
jQuery('#email').blur(function() {
jQuery('#real-avatar .avatar').attr('src', gravatarurl + hex_md5(jQuery('#email').val()) + '?s=50&d=mm&r=g');
jQuery('#real-avatar .avatar').attr('srcset', gravatarurl + hex_md5(jQuery('#email').val()) + '?s=100&d=mm&r=g 2x');
jQuery('#Get_Gravatar').fadeOut().html('看看右边头像对不对？').fadeIn('slow');
});
});
//]]>
</script>
<?php elseif (weisay_option('wei_gravatar') == 'three') : ?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function() {
var gravatarurl= 'https://gravatar.loli.net/avatar/';
jQuery('#email').blur(function() {
jQuery('#real-avatar .avatar').attr('src', gravatarurl + hex_md5(jQuery('#email').val()) + '?s=50&d=mm&r=g');
jQuery('#real-avatar .avatar').attr('srcset', gravatarurl + hex_md5(jQuery('#email').val()) + '?s=100&d=mm&r=g 2x');
jQuery('#Get_Gravatar').fadeOut().html('看看右边头像对不对？').fadeIn('slow');
});
});
//]]>
</script>
<?php elseif (weisay_option('wei_gravatar') == 'four') : ?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function() {
var gravatarurl= 'https://cdn.sep.cc/avatar/';
jQuery('#email').blur(function() {
jQuery('#real-avatar .avatar').attr('src', gravatarurl + hex_md5(jQuery('#email').val()) + '?s=50&d=mm&r=g');
jQuery('#real-avatar .avatar').attr('srcset', gravatarurl + hex_md5(jQuery('#email').val()) + '?s=100&d=mm&r=g 2x');
jQuery('#Get_Gravatar').fadeOut().html('看看右边头像对不对？').fadeIn('slow');
});
});
//]]>
</script>
<?php else: ?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function() {
var gravatarurl= 'https://cravatar.cn/avatar/';
jQuery('#email').blur(function() {
jQuery('#real-avatar .avatar').attr('src', gravatarurl + hex_md5(jQuery('#email').val()) + '?s=50&d=mm&r=g');
jQuery('#real-avatar .avatar').attr('srcset', gravatarurl + hex_md5(jQuery('#email').val()) + '?s=100&d=mm&r=g 2x');
jQuery('#Get_Gravatar').fadeOut().html('看看右边头像对不对？').fadeIn('slow');
});
});
//]]>
</script>
<?php endif; ?>
<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p class="must-log-in"><?php print '您必须'; ?><a href="<?php bloginfo('url'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"> [ 登录 ] </a>才能发表评论！</p>
<?php else : ?>
<form action="<?php bloginfo('url'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( is_user_logged_in() ) : ?>
	<p><?php print '登录者：'; ?> <a href="<?php bloginfo('url'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>&nbsp;&nbsp;<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="注销登录"><?php print '[ 注销 ]'; ?></a></p>
	<?php elseif ( '' != $comment_author ): ?>
	<div class="author"><?php printf(__('欢迎回来 <strong>%s</strong>'), $comment_author); ?>
		<a href="javascript:toggleCommentAuthorInfo();" id="toggle-comment-author-info">[ 更改 ]</a></div>
		<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			var changeMsg = "[ 更改 ]";
			var closeMsg = "[ 隐藏 ]";
			function toggleCommentAuthorInfo() {
				jQuery('#comment-author-info').slideToggle('slow', function(){
					if ( jQuery('#comment-author-info').css('display') == 'none' ) {
					jQuery('#toggle-comment-author-info').text(changeMsg);
					} else {
					jQuery('#toggle-comment-author-info').text(closeMsg);
					}
				});
			}
			jQuery(document).ready(function(){
				jQuery('#comment-author-info').hide();
			});
			//]]>
		</script>
	<?php endif; ?>
	<div id="real-avatar">
	<?php if ( is_user_logged_in() ) : ?>
		<?php $current_user = wp_get_current_user(); echo get_avatar( $current_user->user_email, 50, '', $current_user->display_name ); ?>
	<?php elseif(isset($_COOKIE['comment_author_email_'.COOKIEHASH])) : ?>
		<?php echo get_avatar( $comment_author_email, 50, '', 'gravatar' );?>
	<?php else :?>
		<?php global $user_email;?><?php echo get_avatar( $user_email, 50, '', 'gravatar' ); ?>
	<?php endif;?>
	</div>
	<?php if ( !is_user_logged_in() ) : ?>
	<div id="comment-author-info">
		<p>
			<input type="text" name="author" id="author" class="commenttext" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
			<label for="author">昵称<?php if ($req) echo "<span class='required'> *</span>"; ?></label>
		</p>
		<p>
			<input type="text" name="email" id="email" class="commenttext" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
			<label for="email">邮箱<?php if ($req) echo "<span class='required'> *</span>"; ?> <a id="Get_Gravatar"  title="查看如何申请一个自己的Gravatar全球通用头像" target="_blank" href="https://www.weisay.com/blog/apply-gravatar.html">（教你设置自己的个性头像）</a></label>
		</p>
		<p>
			<input type="text" name="url" id="url" class="commenttext" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
			<label for="url">网址</label>
		</p>
	</div>
	<?php endif; ?>
	<div class="clear"></div>
	<?php if (weisay_option('wei_smilies') == 'hide') { ?>
	<?php { echo ''; } ?>
	<?php } else { include('includes/smilies.php'); } ?>
	<textarea name="comment" id="comment" placeholder="互动可以先从评论开始…" tabindex="4" cols="50" rows="5"></textarea>
	<p class="form-submit">
		<input class="submit" name="submit" type="submit" id="submit" tabindex="5" value="提交留言" />
		<input class="reset" name="reset" type="reset" id="reset" tabindex="6" value="<?php esc_attr_e( '重写' ); ?>" />
		<?php comment_id_fields(); ?>
	</p>
	<script type="text/javascript">	//Crel+Enter
	//<![CDATA[
		jQuery(document).keypress(function(e){
			if(e.ctrlKey && e.which == 13 || e.which == 10) {
				jQuery(".submit").click();
				document.body.focus();
			} else if (e.shiftKey && e.which==13 || e.which == 10) {
				jQuery(".submit").click();
			}
		})
	// ]]>
	</script>
	<?php do_action('comment_form', $post->ID); ?>
	<p class="shortcut">快捷键：Ctrl+Enter</p>
</form>
<div class="clear"></div>
<?php endif; // If registration required and not logged in ?>
</div>
</div>
<?php endif; // comments_open() ?>
<?php if ( ! comments_open() ) : ?>
<p class="comments-closed">报歉！评论已关闭。</p>
<?php endif; ?>
</div>