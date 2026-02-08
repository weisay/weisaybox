<?php
/**
 * WordPress Weisay-Send-Comment-Email v1.1 by Weisay.
 * URI: https://www.weisay.com/blog/
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// ==================== 1. SMTP 配置 ====================
add_filter('wp_mail_from', function($from){
	return weisay_option('wei_smtp_username');
}, 9999);

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

// ==================== 2. 发送邮件函数 ====================
function send_comment_email($to, $subject, $message) {
	$from_email = weisay_option('wei_smtp_username');
	$from_name = weisay_option('wei_smtp_from_name') ?: get_bloginfo('name');
	$headers = array(
		"From: $from_name <$from_email>",
		"Reply-To: $from_email",
		"Content-Type: text/html; charset=" . get_option('blog_charset')
	);
	return wp_mail($to, $subject, $message, $headers);
}

// ==================== 3. 核心逻辑：判断与异步调度 ====================

// 注册异步发送任务
function schedule_comment_email($comment_id) {
	$comment = get_comment($comment_id);
	if (!$comment || $comment->comment_approved !== '1') return;
	$parent_id = $comment->comment_parent;
	if (!$parent_id) return;
	$parent_comment = get_comment($parent_id);
	if (!$parent_comment) return;

	// 排除逻辑：自回复、回复管理员
	$reply_to_self = ($comment->comment_author_email === $parent_comment->comment_author_email);
	$parent_is_admin = (! empty($parent_comment->user_id) && user_can((int) $parent_comment->user_id, 'manage_options'));
	if ($reply_to_self || $parent_is_admin) return;

	// 开关控制逻辑
	$is_admin_reply = (! empty($comment->user_id) && user_can((int) $comment->user_id, 'manage_options'));
	
	// 如果 [不是管理员在回复] 并且 [开关关闭] -> 不发邮件
	if (!$is_admin_reply && (weisay_option('wei_notify_user') !== '1')) {
		return;
	}

	// 尝试注册异步任务（12秒后）
	$args = array($comment_id);
	$scheduled = wp_schedule_single_event(time() + 12, 'async_send_email_event', $args);
	// 保险逻辑：如果明确注册失败，或者网站禁用了 Cron 功能
	if ($scheduled === false || (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON)) {
		wp_clear_scheduled_hook('async_send_email_event', $args);
		execute_async_send($comment_id);
	}

}

// 安排异步调用
add_action('async_send_email_event', 'execute_async_send');
function execute_async_send($comment_id) {
	$comment = get_comment($comment_id);
	if (!$comment || $comment->comment_approved !== '1') return;
	$parent_comment = get_comment($comment->comment_parent);
	if (!$parent_comment) return;
	// 准备邮件内容
	$subject = '✨您在 [' . esc_html(get_option('blogname')) . '] 的评论有了新回复';
	$message = generate_comment_email_message($comment, $parent_comment);
	$message = convert_smilies($message);

	send_comment_email($parent_comment->comment_author_email, $subject, $message);
}

// ==================== 4. 评论钩子挂载 ====================

// 场景 A: 评论发布时 (直接通过审核)
add_action('comment_post', function($comment_id, $approved) {
	if ($approved === 1 || $approved === '1' || $approved === 'approve') {
		schedule_comment_email($comment_id);
	}
}, 10, 2);

// 场景 B: 评论从待审变为通过时
add_action('wp_set_comment_status', function($comment_id, $status) {
	if ($status === 'approve') {
		schedule_comment_email($comment_id);
	}
}, 10, 2);

// ==================== 5. 邮件模板 ====================
function generate_comment_email_message($comment, $parent_comment) {
	if ( empty($parent_comment) && ! empty($comment->comment_parent) ) {
		$parent_comment = get_comment( intval($comment->comment_parent) );
	}
	$home_url = esc_url(home_url('/'));
	$blog_name = esc_html( get_option('blogname') );
	$post_link = esc_url( get_permalink($comment->comment_post_ID) );
	$post_title = esc_html( get_the_title($comment->comment_post_ID) );
	$parent_author = $parent_comment ? esc_html( trim($parent_comment->comment_author) ) : '';
	$parent_content = $parent_comment ? wpautop(esc_html( $parent_comment->comment_content )) : '';
	$comment_author = esc_html( trim($comment->comment_author) );
	$comment_content = wpautop( esc_html( $comment->comment_content ) );
	$parent_comment_link = $parent_comment ? esc_url( get_comment_link($parent_comment->comment_ID) ) : esc_url( get_comment_link($comment->comment_ID) );

	ob_start();
	?>
	<div style="font-size:1.025rem;font-family:Microsoft Yahei,sans-serif;color:#111;margin:0 auto;padding:30px 20px;max-width:1000px;">
	<div style="text-align:right; margin:0 5px 10px;"><a style="color:#222;text-decoration:none;font-weight:bold" href="<?php echo $home_url; ?>" target="_blank"><?php echo $blog_name; ?></a> · 评论回复通知</div>
	<div style="background:#fff;padding:30px 24px;border-top:3px solid #111;border-left:1px solid #eee;border-right:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:#ddd 2px 2px 4px;border-radius:2px;">
	<p><strong><?php echo $parent_author; ?></strong>，您好！</p>
	<p>您在<strong>《<a style="color:#0088dd;text-decoration:none;font-weight:bold;" href="<?php echo $post_link; ?>" target="_blank"><?php echo $post_title; ?></a>》</strong>一文中的评论有了新的回复。</p>
	<p style="margin-top:30px;margin-right:50px;"><strong>您</strong> 在评论中说：</p>
	<div style="background-color:#f5f5f5;padding:10px 20px;margin:0 50px 30px 0;line-height:1.8;border-radius:4px;"><?php echo $parent_content; ?></div>
	<p style="margin-left:50px;text-align:right;"><strong><?php echo $comment_author; ?></strong> 给您的回复：</p>
	<div style="background-color:#e7f2ff;padding:10px 20px;margin:0 0 20px 50px;line-height:1.8;text-align:right;border-radius:4px;"><?php echo $comment_content; ?></div>
	<p>可点击 <a style="color:#0088dd;text-decoration:none;font-weight:bold;" href="<?php echo $parent_comment_link; ?>" target="_blank">查看回复的完整内容</a>。</p>
	<p>欢迎再次光临 <strong><a style="color:#0088dd;text-decoration:none;" href="<?php echo $home_url; ?>" target="_blank"><?php echo $blog_name; ?></a></strong></p>
	</div>
	</div>
	<div style="margin:10px 0 40px;">
	<p style="text-align:center;color:#666;font-size:1rem;line-height:1;">© <?php echo $blog_name; ?></p>
	<p style="text-align:center;color:#888;font-size:0.875rem;line-height:1;">(此邮件由系统自动发送，请勿回复！) </p>
	</div>
	<div style="clear:both;font-size:0;height:1px;overflow:hidden;"></div>
	<?php
	return ob_get_clean();
}

// ==================== 6. 后台测试邮件逻辑 ====================
add_action('wp_ajax_wei_send_test_mail', function() {
	check_ajax_referer('wei_send_test_mail_nonce', 'nonce'); // AJAX nonce 检查
	if (!current_user_can('manage_options')) {
		wp_send_json_error('没有权限发送测试邮件');
	}

	$smtp_host = weisay_option('wei_smtp_host');
	$smtp_user = weisay_option('wei_smtp_username');
	$smtp_pass = weisay_option('wei_smtp_password');
	if (!$smtp_host || !$smtp_user || !$smtp_pass) {
		wp_send_json_error('SMTP 信息不完整，请先填写 SMTP 服务器、邮箱和授权码/密码等信息。');
	}

	// 发送测试邮件
	$mail_sent = send_comment_email(
		get_option('admin_email'),
		'SMTP 测试邮件',
		'<h3>这是一封测试邮件</h3>
		 <p>恭喜！您的WordPress网站已成功配置SMTP邮件发送功能。</p>
		 <p>发送时间：' . date('Y-m-d H:i:s', current_time('timestamp')) . '</p>
		 <p>（来自Weisay Box主题设置）</p>'
	);

	if ($mail_sent) {
		wp_send_json_success('测试邮件已发送成功，请检查管理员邮箱。');
	} else {
		wp_send_json_error('邮件发送失败，请检查 SMTP 配置是否正确。');
	}
});