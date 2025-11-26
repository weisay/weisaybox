<?php get_header(); ?>
<div class="roll">
<div id="dark-mode-toggle-button" onclick="applyCustomDarkModeSettings(toggleCustomDarkMode());" title="点击切换显示模式" class="roll_dark"></div>
<div title="回到顶部" class="roll_top"></div><div title="转到底部" class="roll_down"></div></div>
<div id="content">
<div class="main">
<div id="map">
<div class="site">当前位置： <a title="返回首页" href="<?php bloginfo('url'); ?>/">首页</a> &gt; 404 &gt; 页面已飞走，试试搜索吧</div>
</div>
<div class="article article_c article_e sorry">
<img src="<?php echo esc_url(get_template_directory_uri() . '/images/404.png'); ?>" alt="404">
</div>
<div class="article article_c article_d">
	<h3 class="center subtitle">你迷路了?试试搜索吧</h3>
	<div class="searchBar">
		<form id="searchform" method="get" action="<?php bloginfo('url'); ?>/">
		<div class="boxInput">
			<input type="text" required="" name="s" id="s" value="" class="inputTxt" placeholder="搜索">
		</div>
		<button type="submit" class="schBtn">搜索</button>
	</form>
	</div>
</div>
</div>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>