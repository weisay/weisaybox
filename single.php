<?php get_header(); ?>
<div class="roll">
<div id="dark-mode-toggle-button" onclick="applyCustomDarkModeSettings(toggleCustomDarkMode());" title="点击切换显示模式" class="roll_dark"></div>
<div title="回到顶部" class="roll_top"></div><div title="查看评论" class="roll_comm"></div><div title="转到底部" class="roll_down"></div></div>
<div id="content">
<div class="main">
<div id="map">
<div class="site">当前位置： <a title="返回首页" href="<?php bloginfo('url'); ?>/">首页</a> &gt; <?php the_category(', ') ?> &gt; 正文</div>
</div>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div id="post-<?php the_ID(); ?>" class="article article_c">
<div class="author_info">
<div class="post-info"><h2 class="entry-title"><?php the_title(); ?></h2>
<div class="article_info"><span class="author"><?php the_author() ?></span><span class="updated"><span class="date-fby"> 发布于 </span><?php the_time('Y-m-d') ?><span class="date-hi"><?php the_time(' H:i') ?></span></span><span class="category fn"><?php the_category(', ') ?></span><?php if(function_exists('the_views')) { echo '<span class="views">'; the_views(); echo '</span>'; } ?><span class="comments"><?php comments_popup_link ('抢沙发','1条评论','<span content="UserComments:%">%</span>条评论'); ?></span><?php edit_post_link('编辑', ' [ ', ' ] '); ?></div></div>
</div>
<div class="entry-content">
<div><?php the_content('Read more...'); ?></div><div class="clear"></div>
<?php wp_link_pages(array('before' => '<div class="fenye">分页：', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span>上一页</span>', 'nextpagelink' => "")); ?>
<?php wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span>', 'link_after'=>'</span>')); ?>
<?php wp_link_pages(array('before' => '', 'after' => '</div><div class="clear"></div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => "<span>下一页</span>")); ?>
<?php the_tags('<div class="article-tags"><span>', ' ', '</span></div><div class="clear"></div>'); ?>
</div>
</div>
<?php if (weisay_option('wei_reward') == 'display') : ?>
<div class="article article_c article-shang">
	<div class="shang">
		<span class="zanzhu"><a title="赞助本站" href="javascript:;" onfocus="this.blur()">赏</a></span>
	</div>
</div>
<div class="shang-bg"></div>
<div class="shang-content" style="display:none;">
	<button class="shang-close" title="关闭">×</button><div class="shang-title">打赏支持</div>
	<div class="shang-body">
	<div class="shang-zfb shang-qrcode"><img alt="支付宝打赏" src="<?php echo weisay_option('wei_alipay'); ?>" width="170" height="170"><span>支付宝打赏</span></div>
	<div class="shang-wx shang-qrcode"><img alt="微信打赏" src="<?php echo weisay_option('wei_wxpay'); ?>" width="170" height="170"><span>微信打赏</span></div>
	<div class="clear"></div>
	<p class="shang-tips">扫描二维码，打赏一下作者吧~</p>
	</div>
</div>
<?php endif; ?>
<div class="article article_c">
<ul class="pre_nex"><li>
<?php previous_post_link('【上一篇】%link') ?></li><li><?php next_post_link('【下一篇】%link') ?></li>
</ul>
</div>
<div class="article article_c">
<?php require get_template_directory() . '/includes/related.php'; ?>
<div class="clear"></div>
</div>
<div class="article article_c">
<?php comments_template(); ?>
</div>
	<?php endwhile; else: ?>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>