<?php
function optionsframework_option_name() {
	return 'weisaybox';
}

// 读取changelog.txt 更新日志文件
function get_changelog_content() {
	$changelog_file = get_template_directory() . '/changelog.txt';
	if (!file_exists($changelog_file)) return '<div class="update-item"><p>暂无更新日志</p></div>';
	$lines = file($changelog_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$changelog_html = '';
	$current_version = '';
	foreach ($lines as $line) {
		$line = trim($line);
		if (empty($line)) continue;
		if (strpos($line, "\t\t") !== false) {
			if (!empty($current_version)) $changelog_html .= '</ol></div>';
			list($version, $date) = explode("\t\t", $line, 2);
			$changelog_html .= '<div class="update-item"><h4 class="heading">版本 ' 
				. esc_html(trim($version)) . '<span class="update-date">' 
				. esc_html(trim($date)) . '</span></h4><ol class="changelog">';
			$current_version = $version;
		} else {
			$changelog_html .= '<li>' . esc_html($line) . '</li>';
		}
	}
	return !empty($current_version) ? $changelog_html . '</ol></div>' : '<div class="update-item"><p>暂无更新日志</p></div>';
}

function optionsframework_options() {
	$editor_setting = array(
		'quicktags' => 1,
		'tinymce' => 0,
		'media_buttons' => 0,
		'textarea_rows' => 4
	);
	
	$show_hide = array(
		'hide' => __( '隐藏', 'theme-textdomain' ),
		'display' => __( '显示', 'theme-textdomain' )
	);

	$on_off = array(
		'close' => __( '禁用', 'theme-textdomain' ),
		'open' => __( '启用', 'theme-textdomain' )
	);

	$options = array();

	$options[] = array(
		'name' => __( '全局设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
	
	$options[] = array(
		'name' => __( '主题使用说明', 'theme-textdomain' ),
		'desc' => sprintf( __( '详细使用说明点击 <a href="%1$s" target="_blank">WordPress主题『Weisay Box』</a>，若有疑问可以评论留言。', 'theme-textdomain' ), 'https://www.weisay.com/blog/wordpress-theme-weisay-box.html?weisaybox' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => __( '网站运营年份', 'theme-textdomain' ),
		'desc' => __( '填写网站运营年份，格式示例：2018-2023', 'theme-textdomain' ),
		'id' => 'wei_websiteyear',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'SEO相关', 'theme-textdomain' ),
		'id' => 'wei_about_seo',
		'class' => 'separate',
		'type' => 'info',
	);

	$options[] = array(
		'name' => __( '描述（Description）', 'theme-textdomain' ),
		'desc' => __( '输入你的网站描述，一般不超过200个字符', 'theme-textdomain' ),
		'id' => 'wei_description',
		'std' => '',
		'type' => 'textarea'
	);
	
	$options[] = array(
		'name' => __( '关键词（KeyWords）', 'theme-textdomain' ),
		'desc' => __( '输入你的网站关键字，一般不超过100个字符', 'theme-textdomain' ),
		'id' => 'wei_keywords',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'distinguish',
		'class' => 'separate',
		'type' => 'info',
	);

	$options[] = array(
		'name' => __( '网站页头自定义', 'theme-textdomain' ),
		'desc' => __( '在页面 <code>&lt;head&gt;</code> 中添加自定义代码。可用于插入内联 CSS、JavaScript 或其他代码，如统计脚本', 'theme-textdomain' ),
		'id' => 'wei_headcustom',
		'std' => '',
		'type' => 'editor',
		'settings' => $editor_setting
	);

	$options[] = array(
		'name' => __( '网站底部第二行自定义', 'theme-textdomain' ),
		'desc' => __( '输入你的自定义内容，支持html', 'theme-textdomain' ),
		'id' => 'wei_footercustom',
		'std' => '',
		'type' => 'editor',
		'settings' => $editor_setting
	);

	$options[] = array(
		'name' => __( '备案相关', 'theme-textdomain' ),
		'id' => 'wei_about_beian',
		'class' => 'separate',
		'type' => 'info',
	);
	
	$options[] = array(
		'name' => __( '显示 ICP 备案号', 'theme-textdomain' ),
		'desc' => __( '默认隐藏', 'theme-textdomain' ),
		'id' => 'wei_beian',
		'std' => 'hide',
		'type' => 'select',
		'options' => $show_hide
	);

	$options[] = array(
		'name' => __( 'ICP 备案号', 'theme-textdomain' ),
		'desc' => __( '填写备案号，如：沪ICP备20221105号', 'theme-textdomain' ),
		'id' => 'wei_beianhao',
		'std' => '',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => __( '显示公网安备案号', 'theme-textdomain' ),
		'desc' => __( '默认隐藏', 'theme-textdomain' ),
		'id' => 'wei_gwab',
		'std' => 'hide',
		'type' => 'select',
		'options' => $show_hide
	);	
	
	$options[] = array(
		'name' => __( '公网安备案号', 'theme-textdomain' ),
		'desc' => __( '填写公网安备案号，如：京公网安备 11010102002019号', 'theme-textdomain' ),
		'id' => 'wei_gwabh',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( '基础功能设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
	
	$options[] = array(
		'name' => __( '启用旧版小工具', 'theme-textdomain' ),
		'desc' => __( '默认禁用。相比新的区块小工具，旧版小工具操作更简单', 'theme-textdomain' ),
		'id' => 'wei_widgets',
		'std' => 'close',
		'type' => 'select',
		'options' => $on_off
	);

	$options[] = array(
		'name' => __( '启用经典编辑器', 'theme-textdomain' ),
		'desc' => __( '默认禁用。开启后请勿同时启用经典编辑器（Classic Editor）插件，以免产生冲突', 'theme-textdomain' ),
		'id' => 'wei_editor',
		'std' => 'close',
		'type' => 'select',
		'options' => $on_off
	);

	$options[] = array(
		'name' => __( '加载区块编辑器内联样式', 'theme-textdomain' ),
		'desc' => __( '默认启用，前台页面 <code>&lt;head&gt;</code> 中会加载区块编辑器相关的内联样式。如果你只使用经典编辑器，建议禁用此选项。', 'theme-textdomain' ),
		'id' => 'wei_gutenberg_css',
		'std' => 'open',
		'type' => 'select',
		'options' => $on_off
	);

	$options[] = array(
		'name' => __( '显示导航栏搜索框', 'theme-textdomain' ),
		'desc' => __( '默认显示', 'theme-textdomain' ),
		'id' => 'wei_search',
		'std' => 'display',
		'type' => 'select',
		'options' => $show_hide
	);

	$options[] = array(
		'name' => __( '显示评论表情', 'theme-textdomain' ),
		'desc' => __( '默认显示', 'theme-textdomain' ),
		'id' => 'wei_smilies',
		'std' => 'display',
		'type' => 'select',
		'options' => $show_hide
	);
	
	$options[] = array(
		'name' => __( '显示侧边栏读者墙', 'theme-textdomain' ),
		'desc' => __( '默认显示', 'theme-textdomain' ),
		'id' => 'wei_hotreviewer',
		'std' => 'display',
		'type' => 'select',
		'options' => $show_hide
	);
	
	$options[] = array(
		'name' => __( '显示侧边栏标签云集', 'theme-textdomain' ),
		'desc' => __( '默认隐藏', 'theme-textdomain' ),
		'id' => 'wei_tags',
		'std' => 'hide',
		'type' => 'select',
		'options' => $show_hide
	);

	$options[] = array(
		'name' => __( '侧边栏热门日志天数范围', 'theme-textdomain' ),
		'desc' => __( '默认选择最近365天的文章，可以根据文章发布频次自行调整选择的天数范围', 'theme-textdomain' ),
		'id' => 'wei_hotpostno',
		'std' => '365',
		'class' => 'mini',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => __( '首页显示某分类友链', 'theme-textdomain' ),
		'desc' => __( '如果友链有分类，可以在首页显示某分类的友链，填写分类ID，全显示则不需要修改', 'theme-textdomain' ),
		'id' => 'wei_linkid',
		'std' => '0',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( '友情链接页面显示', 'theme-textdomain' ),
		'desc' => __( '完整链接可以显示图片、名称和描述（需先填写），基础链接则不显示描述', 'theme-textdomain' ),
		'id' => 'wei_linkpage',
		'std' => 'one',
		'type' => 'select',
		'options' => array(
			'one' => __( '完整链接信息', 'theme-textdomain' ),
			'two' => __( '基础链接信息', 'theme-textdomain' ),
		)
	);
	
	$options[] = array(
		'name' => __( '特色功能设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
	
	$options[] = array(
		'name' => __( '缩略图类型', 'theme-textdomain' ),
		'desc' => __( '选择缩略图显示的优先级，> 符号前面的优先显示', 'theme-textdomain' ),
		'id' => 'wei_thumbnail',
		'std' => 'one',
		'type' => 'select',
		'options' => array(
			'one' => __( '随机缩略图', 'theme-textdomain' ),
			'two' => __( '特色图片>自定义缩略图>随机缩略图', 'theme-textdomain' ),
			'three' => __( '特色图片>自定义缩略图>文章第一张图>随机缩略图', 'theme-textdomain' ),
		)
	);
	
	$options[] = array(
		'name' => __( 'Gravatar头像替换源', 'theme-textdomain' ),
		'desc' => __( '解决Gravatar可能无法显示的问题，默认使用Weavatar', 'theme-textdomain' ),
		'id' => 'wei_gravatar',
		'std' => 'one',
		'type' => 'select',
		'options' => array(
			'zero' => __( '官方源', 'theme-textdomain' ),
			'one' => __( 'Weavatar源', 'theme-textdomain' ),
			'two' => __( 'Cravatar源', 'theme-textdomain' ),
			'three' => __( 'Loli.net源', 'theme-textdomain' ),
			'four' => __( 'sep.cc源', 'theme-textdomain' ),
		)
	);

	$options[] = array(
		'name' => __( '启用代码高亮(Prism.js)', 'theme-textdomain' ),
		'desc' => __( '默认禁用', 'theme-textdomain' ),
		'id' => 'wei_prismjs',
		'std' => 'close',
		'type' => 'select',
		'options' => $on_off
	);

	$options[] = array(
		'name' => __( '屏蔽非中文评论', 'theme-textdomain' ),
		'desc' => __( '默认禁用，开启后，评论内容必须包含中文，否则无法提交', 'theme-textdomain' ),
		'id' => 'wei_chinese',
		'std' => 'close',
		'type' => 'select',
		'options' => $on_off
	);

	$options[] = array(
		'name' => __( '走心评论相关', 'theme-textdomain' ),
		'id' => 'wei_about_touching',
		'class' => 'separate',
		'type' => 'info',
	);

	$options[] = array(
		'name' => __( '启用走心评论', 'theme-textdomain' ),
		'desc' => __( '默认禁用', 'theme-textdomain' ),
		'id' => 'wei_touching',
		'class' => 'sub-level',
		'std' => 'close',
		'type' => 'select',
		'options' => $on_off
	);

	$options[] = array(
		'name' => __( '显示走心评论页顶部随机图', 'theme-textdomain' ),
		'desc' => __( '默认显示', 'theme-textdomain' ),
		'id' => 'wei_tcbgimg',
		'class' => 'sub-level',
		'std' => 'display',
		'type' => 'select',
		'options' => $show_hide
	);

	$options[] = array(
		'name' => __( '走心评论页子标题', 'theme-textdomain' ),
		'desc' => __( '自定义子标题，仅在显示随机背景图片时生效，未填写时显示默认文案：「每一条评论，都是一个故事！」', 'theme-textdomain' ),
		'id' => 'wei_tctagline',
		'class' => 'sub-level',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( '走心评论页链接', 'theme-textdomain' ),
		'desc' => __( '评论中入选走心评论的链接。可不填写，如填写，请使用完整 URL，需包含 http:// 或 https://', 'theme-textdomain' ),
		'id' => 'wei_touchingurl',
		'class' => 'sub-level',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( '走心评论页列数', 'theme-textdomain' ),
		'desc' => __( '此设置只针对pc端，移动端根据宽度自适应', 'theme-textdomain' ),
		'id' => 'wei_touchingcol',
		'class' => 'sub-level',
		'std' => '4',
		'type' => 'radio',
		'options' => array(
			'1' => '1列',
			'2' => '2列',
			'3' => '3列',
			'4' => '4列',
		)
	);

	$options[] = array(
		'name' => __( '评论邮件设置', 'theme-textdomain' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '',
		'desc' => __( '提示：如果使用本主题自带的评论回复邮件通知功能，请勿同时启用类似插件，以免产生冲突。', 'theme-textdomain' ),
		'class' => 'tips',
		'type' => 'info'
	);

	$options[] = array(
		'name' => __( '启用评论邮件通知', 'theme-textdomain' ),
		'desc' => __( '默认禁用，开启后，如 SMTP 功能正常，管理员回复评论时将邮件通知原评论用户', 'theme-textdomain' ),
		'id' => 'wei_smtp',
		'std' => 'close',
		'type' => 'select',
		'options' => $on_off
	);

	$options[] = array(
		'name' => __( '发送测试邮件', 'theme-textdomain' ),
		'id' => 'wei_test_mail_info',
		'type' => 'info',
		'desc' => '完成下方的 SMTP 配置后，点击右侧按钮发送测试邮件到管理员邮箱（<strong>确保已开启上方的评论邮件通知</strong>）<br><br>
			<button type="button" id="wei_send_test_mail" class="button button-primary">发送测试邮件</button>
			<span id="wei_test_mail_result" style="margin-left:10px;"></span>'
	);

	$options[] = array(
		'name' => __( '普通用户回复他人时通知对方', 'theme-textdomain' ),
		'desc' => __( '默认不通知，开启后请谨慎使用，以避免垃圾评论打扰用户', 'theme-textdomain' ),
		'id' => 'wei_notify_user',
		'std' => '2',
		'type' => 'radio',
		'options' => array(
			'1' => '通知',
			'2' => '不通知',
		)
	);

	$options[] = array(
		'name' => __( 'SMTP 邮件设置', 'theme-textdomain' ),
		'id' => 'wei_about_smtp',
		'class' => 'separate',
		'type' => 'info',
	);

	$options[] = array(
		'name' => __( 'SMTP 服务器地址', 'theme-textdomain' ),
		'desc' => __( '常见的SMTP服务器地址包括smtp.qq.com、smtp.163.com、smtp.126.com、smtp.gmail.com等', 'theme-textdomain' ),
		'id' => 'wei_smtp_host',
		'class' => 'sub-level',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'SMTP 端口', 'theme-textdomain' ), 'SMTP 端口',
		'desc' => __( 'SMTP服务器的端口号通常为465或者587，具体取决于您的邮件服务提供商要求的设置', 'theme-textdomain' ),
		'id' => 'wei_smtp_port',
		'class' => 'sub-level',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'SMTP 加密方式', 'theme-textdomain' ),
		'desc' => __( '通常可以选择SSL/TLS加密方式来确保邮件传输的安全性', 'theme-textdomain' ),
		'id' => 'wei_smtp_secure',
		'class' => 'sub-level',
		'std' => 'ssl',
		'type' => 'select',
		'options' => array(
			'ssl' => 'SSL',
			'tls' => 'TLS',
			'' => '无加密'
		)
	);

	$options[] = array(
		'name' => __( 'SMTP 登录邮箱', 'theme-textdomain' ),
		'desc' => __( '请输入SMTP邮箱地址', 'theme-textdomain' ),
		'id' => 'wei_smtp_username',
		'class' => 'sub-level',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'SMTP 授权码/密码', 'theme-textdomain' ),
		'desc' => __( '建议使用授权码', 'theme-textdomain' ),
		'id' => 'wei_smtp_password',
		'class' => 'sub-level',
		'std' => '',
		'type' => 'password'
	);

	$options[] = array(
		'name' => __( '发件人名称', 'theme-textdomain' ),
		'id' => 'wei_smtp_from_name',
		'class' => 'sub-level',
		'std' => get_bloginfo('name'),
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( '打赏设置', 'theme-textdomain' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( '显示文章底部打赏', 'theme-textdomain' ),
		'desc' => __( '默认隐藏，开启此功能前，请确保文章底部作者信息已显示', 'theme-textdomain' ),
		'id' => 'wei_reward',
		'std' => 'hide',
		'type' => 'select',
		'options' => $show_hide
	);

	$options[] = array(
		'name' => __( '支付宝收款二维码图片', 'theme-textdomain' ),
		'desc' => __( '支付宝收款二维码图片，大小建议：170px*170px', 'theme-textdomain' ),
		'id' => 'wei_alipay',
		'type' => 'upload'
	);
	
	$options[] = array(
		'name' => __( '微信收款二维码图片', 'theme-textdomain' ),
		'desc' => __( '微信收款二维码图片，大小建议：170px*170px', 'theme-textdomain' ),
		'id' => 'wei_wxpay',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __( '更新日志', 'theme-textdomain' ),
		'type' => 'heading'
	);


	$options[] = array(
		'desc' => get_changelog_content(),
		'id' => 'wei_changelog',
		'type' => 'info'
	);

	return $options;
}