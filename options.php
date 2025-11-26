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
	$shortname = "wei";
	
	$whether_array = array(
		'hide' => __( '隐藏', 'theme-textdomain' ),
		'display' => __( '展示', 'theme-textdomain' )
	);
	
	$whether_arrays = array(
		'hides' => __( '关闭', 'theme-textdomain' ),
		'displays' => __( '开启', 'theme-textdomain' )
	);

	$options = array();

	$options[] = array(
		'name' => __( '全局设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
	
	$options[] = array(
		'name' => __( '主题使用教程', 'theme-textdomain' ),
		'desc' => sprintf( __( '详细使用教程点击 <a href="%1$s" target="_blank">WordPress主题『Weisay Box』</a>，若有疑问可以评论留言。', 'theme-textdomain' ), 'https://www.weisay.com/blog/wordpress-theme-weisay-box.html?weisaybox' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => __( '描述（Description）', 'theme-textdomain' ),
		'desc' => __( '输入你的网站描述，一般不超过200个字符', 'theme-textdomain' ),
		'id' => $shortname."_description",
		'std' => '',
		'type' => 'textarea'
	);
	
	$options[] = array(
		'name' => __( '关键词（KeyWords）', 'theme-textdomain' ),
		'desc' => __( '输入你的网站关键字，一般不超过100个字符', 'theme-textdomain' ),
		'id' => $shortname."_keywords",
		'std' => '',
		'type' => 'textarea'
	);
	
	$options[] = array(
		'name' => __( '是否展示ICP备案号', 'theme-textdomain' ),
		'desc' => __( '默认隐藏', 'theme-textdomain' ),
		'id' => $shortname."_beian",
		'std' => 'hide',
		'type' => 'select',
		'options' => $whether_array
	);

	$options[] = array(
		'name' => __( '输入您的ICP备案号', 'theme-textdomain' ),
		'desc' => __( '填写备案号，如：沪ICP备20221105号', 'theme-textdomain' ),
		'id' => $shortname."_beianhao",
		'std' => '',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => __( '是否展示公网安备案号', 'theme-textdomain' ),
		'desc' => __( '默认隐藏', 'theme-textdomain' ),
		'id' => $shortname."_gwab",
		'std' => 'hide',
		'type' => 'select',
		'options' => $whether_array
	);	
	
	$options[] = array(
		'name' => __( '输入您的公网安备案号', 'theme-textdomain' ),
		'desc' => __( '填写公网安备案号，如：京公网安备 11010102002019号', 'theme-textdomain' ),
		'id' => $shortname."_gwabh",
		'std' => '',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => __( '网站运营年份', 'theme-textdomain' ),
		'desc' => __( '输入网站运营年份，如：2018-2023', 'theme-textdomain' ),
		'id' => $shortname."_websiteyear",
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( '基础功能设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
	
	$options[] = array(
		'name' => __( '是否启用旧版小工具', 'theme-textdomain' ),
		'desc' => __( '默认关闭。旧版小工具相比块编辑小工具要简单一些', 'theme-textdomain' ),
		'id' => $shortname."_widgets",
		'std' => 'close',
		'type' => 'select',
		'options' => $whether_arrays
	);

	$options[] = array(
		'name' => __( '是否展示导航栏的搜索框', 'theme-textdomain' ),
		'desc' => __( '默认展示', 'theme-textdomain' ),
		'id' => $shortname."_search",
		'std' => 'display',
		'type' => 'select',
		'options' => $whether_array
	);
	
	$options[] = array(
		'name' => __( '是否展示顶部独立页面链接', 'theme-textdomain' ),
		'desc' => __( '默认展示', 'theme-textdomain' ),
		'id' => $shortname."_toppage",
		'std' => 'display',
		'type' => 'select',
		'options' => $whether_array
	);
	
	$options[] = array(
		'name' => __( '是否展示评论表情', 'theme-textdomain' ),
		'desc' => __( '默认展示', 'theme-textdomain' ),
		'id' => $shortname."_smilies",
		'std' => 'display',
		'type' => 'select',
		'options' => $whether_array
	);
	
	$options[] = array(
		'name' => __( '是否显示侧边栏读者墙', 'theme-textdomain' ),
		'desc' => __( '默认展示', 'theme-textdomain' ),
		'id' => $shortname."_hotreviewer",
		'std' => 'display',
		'type' => 'select',
		'options' => $whether_array
	);
	
	$options[] = array(
		'name' => __( '是否显示侧边栏标签云集', 'theme-textdomain' ),
		'desc' => __( '默认隐藏', 'theme-textdomain' ),
		'id' => $shortname."_tags",
		'std' => 'hide',
		'type' => 'select',
		'options' => $whether_array
	);
	
	$options[] = array(
		'name' => __( '首页展示某分类友链', 'theme-textdomain' ),
		'desc' => __( '如果友链有分类，可以在首页展示某分类的友链，填写分类ID，全展示则不需要修改', 'theme-textdomain' ),
		'id' => $shortname."_linkid",
		'std' => '0',
		'class' => 'mini',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => __( '侧边栏热门日志天数范围', 'theme-textdomain' ),
		'desc' => __( '默认选择最近365天的文章，可以根据文章发布频次自行调整选择的天数范围', 'theme-textdomain' ),
		'id' => $shortname."_hotpostno",
		'std' => '365',
		'class' => 'mini',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => __( '特色功能设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
	
	$options[] = array(
		'name' => __( '缩略图类型', 'theme-textdomain' ),
		'desc' => __( '选择缩略图展示的优先级，> 符号前面的优先展示', 'theme-textdomain' ),
		'id' => $shortname."_thumbnail",
		'std' => 'one',
		'type' => 'select',
		'options' => array(
			'one' => __( '随机缩略图', 'theme-textdomain' ),
			'two' => __( '特色图片>自定义缩略图>随机缩略图', 'theme-textdomain' ),
			'three' => __( '特色图片>自定义缩略图>文章第一张图>随机缩略图', 'theme-textdomain' ),
		)
	);
	
	$options[] = array(
		'name' => __( '友情链接页面展示', 'theme-textdomain' ),
		'desc' => __( '完整链接可以展示图片、名称和描述（需先填写），基础链接则不展示描述', 'theme-textdomain' ),
		'id' => $shortname."_linkpage",
		'std' => 'one',
		'type' => 'select',
		'options' => array(
			'one' => __( '完整链接信息', 'theme-textdomain' ),
			'two' => __( '基础链接信息', 'theme-textdomain' ),
		)
	);
	
	$options[] = array(
		'name' => __( 'Gravatar头像替换源', 'theme-textdomain' ),
		'desc' => __( '解决Gravatar无法展示的问题，默认使用Cravatar', 'theme-textdomain' ),
		'id' => $shortname."_gravatar",
		'std' => '1',
		'type' => 'select',
		'options' => array(
			'0' => __( '官方源', 'theme-textdomain' ),
			'1' => __( 'Weavatar源', 'theme-textdomain' ),
			'2' => __( 'Cravatar源', 'theme-textdomain' ),
			'3' => __( 'Loli.net源', 'theme-textdomain' ),
			'4' => __( 'Sep.cc源', 'theme-textdomain' ),
		)
	);

	$options[] = array(
		'name' => __( '是否开启代码高亮功能(Prism.js)', 'theme-textdomain' ),
		'desc' => __( '默认关闭', 'theme-textdomain' ),
		'id' => $shortname."_prismjs",
		'std' => 'hides',
		'type' => 'select',
		'options' => $whether_arrays
	);

	$options[] = array(
		'name' => __( '是否开启走心评论功能', 'theme-textdomain' ),
		'desc' => __( '默认关闭', 'theme-textdomain' ),
		'id' => $shortname."_touching",
		'std' => 'hides',
		'type' => 'select',
		'options' => $whether_arrays
	);

	$options[] = array(
		'name' => __( '是否展示独立页面顶部随机图片', 'theme-textdomain' ),
		'desc' => __( '默认展示', 'theme-textdomain' ),
		'id' => $shortname."_tcbgimg",
		'std' => 'display',
		'type' => 'select',
		'options' => $whether_array
	);

	$options[] = array(
		'name' => __( '走心评论独立页面子标题', 'theme-textdomain' ),
		'desc' => __( '自定义子标题，需要展示随机背景图片才可见，不填展示默认文案「每一条评论，都是一个故事！」', 'theme-textdomain' ),
		'id' => $shortname."_tctagline",
		'class' => 'sub-level',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( '输入您的走心评论独立页面链接', 'theme-textdomain' ),
		'desc' => __( '评论中入选走心评论按钮的链接，可不填；若填写请填写完整链接地址，需包含http或者https', 'theme-textdomain' ),
		'id' => $shortname."_touchingurl",
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( '走心评论独立页面显示几列', 'theme-textdomain' ),
		'desc' => __( '此设置只针对pc端，移动端根据宽度自适应', 'theme-textdomain' ),
		'id' => $shortname."_touchingcol",
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
		'name' => __( '打赏设置', 'theme-textdomain' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( '是否展示文章页打赏', 'theme-textdomain' ),
		'desc' => __( '默认隐藏', 'theme-textdomain' ),
		'id' => $shortname."_reward",
		'std' => 'hide',
		'type' => 'select',
		'options' => $whether_array
	);

	$options[] = array(
		'name' => __( '支付宝收款二维码图片', 'theme-textdomain' ),
		'desc' => __( '支付宝收款二维码图片，大小建议：170px*170px', 'theme-textdomain' ),
		'id' => $shortname."_alipay",
		'type' => 'upload'
	);
	
	$options[] = array(
		'name' => __( '微信收款二维码图片', 'theme-textdomain' ),
		'desc' => __( '微信收款二维码图片，大小建议：170px*170px', 'theme-textdomain' ),
		'id' => $shortname."_wxpay",
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __( '更新日志', 'theme-textdomain' ),
		'type' => 'heading'
	);


	$options[] = array(
		'desc' => get_changelog_content(),
		'id' => $shortname . "_changelog",
		'type' => 'info'
	);

	return $options;
}