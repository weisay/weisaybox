<?php

// ==================== 统一 SMTP 发件人 ====================
add_filter('wp_mail_from', function($from){
	return weisay_option('wei_smtp_username');
}, 9999);

// ==================== SMTP 配置 ====================
add_action('phpmailer_init', 'custom_phpmailer_smtp');
function custom_phpmailer_smtp($phpmailer) {

	// 从后台获取配置
	$host = weisay_option('wei_smtp_host');
	$port = intval(weisay_option('wei_smtp_port'));
	$secure = weisay_option('wei_smtp_secure');
	$username = weisay_option('wei_smtp_username');
	$password = weisay_option('wei_smtp_password');
	$fromname = weisay_option('wei_smtp_from_name') ?: get_bloginfo('name');

	if (!$host || !$port || !$username || !$password) {
		return; // 未配置跳过
	}

	// 设置 SMTP
	$phpmailer->isSMTP();
	$phpmailer->Host = $host;
	$phpmailer->SMTPAuth = true;
	$phpmailer->Port = $port;
	$phpmailer->SMTPSecure = $secure;
	$phpmailer->Username = $username;
	$phpmailer->FromName = $fromname;
	$phpmailer->Password = $password;
	$phpmailer->setFrom($username, $fromname, false);
	$phpmailer->Sender = $username;
}

// ==================== 评论回复模板 ====================
function comment_mail_notify($comment_id) {
	$comment = get_comment($comment_id);
	if (!$comment) { return; }
	$admin_email = get_option('admin_email');
	$comment_author_email = trim($comment->comment_author_email);
	$parent_id = (int) $comment->comment_parent;
	// 只在「管理员回复他人评论」时发送
	if (!$parent_id) { return; }
	$parent_comment = get_comment($parent_id);
	if (!$parent_comment) { return;	}
	$to = trim($parent_comment->comment_author_email);
	// 跳过垃圾评论 & 自己给自己发
	if ($comment->comment_approved === 'spam' || $to === $admin_email || $comment_author_email !== $admin_email	) { return;	}
	$post_id = $comment->comment_post_ID;
	$subject = '您在 [' . get_option('blogname') . '] 的评论有新回复';
	$message = '
	<div style="font-size:1.025rem;font-family:Microsoft Yahei,sans-serif;color:#111;margin:0 auto;padding:30px 20px;max-width:1000px;">
	<div style="text-align: right; margin:0 5px 10px;"><a style="color:#222;text-decoration:none;font-weight:bold" href="' . esc_url(home_url('/')) . '" target="_blank">' . get_option('blogname') . '</a> · 评论回复通知</div>
	<div style="background:#fff;padding:30px 24px;border-top:3px solid #111;border-left:1px solid #eee;border-right:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:#ddd 2px 2px 4px;border-radius:2px;">
	<p><strong>' . trim($parent_comment->comment_author) . '</strong>, 您好!</p>
	<p>你在<strong>《<a style="color:#0088dd;text-decoration:none;font-weight:bold;" href="' . get_permalink($comment->comment_post_ID) . '" target="_blank">' . get_the_title($comment->comment_post_ID) . '</a>》</strong>一文中的评论有了新的回复。</p>
	<p style="margin-top: 30px;margin-right: 50px;"><strong>你</strong> 在评论中说：</p>
	<p style="background-color: #f5f5f5;padding: 20px;margin: 0 50px 30px 0;border-radius: 4px;">' . nl2br(esc_html($parent_comment->comment_content)) . '</p>
	<p style="margin-left:50px;text-align: right;"><strong>' . trim($comment->comment_author) . '</strong>&nbsp;给你的回复：<br>
	</p><p style="background-color: #e7f2ff;padding: 20px;margin: 0 0 20px 50px;text-align: right;border-radius: 4px;">' . nl2br(esc_html($comment->comment_content)) . '</p>
	<p>可点击 <a style="color:#0088dd;text-decoration:none;font-weight:bold;" href="' . esc_url(get_comment_link($parent_id)) . '" target="_blank">查看回复的完整内容</a>。</p>
	<p>欢迎再次光临 <strong><a style="color:#0088dd;text-decoration:none;" href="' . esc_url(home_url('/')) . '" target="_blank">' . get_option('blogname') . '</a></strong></p>
	</div>
	</div>
	<div style="margin:10px 0 40px;">
	<p style="text-align:center;color:#666;font-size:1rem;line-height:1;">© ' . get_option('blogname') . '</p>
	<p style="text-align:center;color:#888;font-size:0.875rem;line-height:1;">(此邮件由系统自动发送，请勿回复！) </p>
	</div>
	<div style="clear:both;font-size:0;height:1px;overflow:hidden;"></div>';
	$message = convert_smilies($message);
	$headers = ['Content-Type: text/html; charset=' . get_option('blog_charset'),];
	wp_mail($to, $subject, $message, $headers);
}
add_action('comment_post', 'comment_mail_notify', 20);