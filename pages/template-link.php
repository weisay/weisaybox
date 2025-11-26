<?php
/*
Template Name: 友情链接
*/
?>
<?php get_header(); ?>
<div class="roll">
<div id="dark-mode-toggle-button" onclick="applyCustomDarkModeSettings(toggleCustomDarkMode());" title="点击切换显示模式" class="roll_dark"></div>
<div title="回到顶部" class="roll_top"></div><div title="查看评论" class="roll_comm"></div><div title="转到底部" class="roll_down"></div></div>
<div id="content">
<div class="main">
<div id="map">
<div class="site">当前位置： <a title="返回首页" href="<?php bloginfo('url'); ?>/">首页</a> &gt; <?php the_title(); ?></div>
</div>

<?php
$content = trim(get_the_content());
if (!empty($content)) : ?>
<div class="article article_c">
<div class="link-content">
<?php the_content('Read more...'); ?>
</div>
</div>
<?php endif; ?>

<div class="article article_c">
<?php if (weisay_option('wei_linkpage') == 'two') : ?>
<div class="links-box links-base">
<?php wp_list_bookmarks (
	array (
		'categorize' => '1',	//链接分类：1显示，0不显示
		'category_orderby' => 'id',	//链接分类排序，可用：name、id、slug、count
		'show_name' => '1',	//链接名称：1显示，0不显示
		'show_images' => '1',	//链接图片：1显示，0不显示
		'show_description' => '0',	//链接描述：1显示，0不显示
		'category_before' => '',
		'category_after' => '' ,
		'title_li' => __('Bookmarks'),
		'title_before' => '<h2 class="links-title">',
		'title_after' => '</h2>',
		'before' => '<li class="links-item">',
		'after' => '</li>',
		'between' => '',
		'link_before' => '<span>',
		'link_after' => '</span>',
		'orderby' => 'link_id',	//友情链接排序，可用：link_id、rand、url、name、target、description、owner、rating、updated、rss、length 等
) ); ?>
</div>
<?php else: ?>
<div class="links-box">
<?php wp_list_bookmarks (
	array (
		'categorize' => '1',	//链接分类：1显示，0不显示
		'category_orderby' => 'id',	//链接分类排序，可用：name、id、slug、count
		'show_name' => '1',	//链接名称：1显示，0不显示
		'show_images' => '1',	//链接图片：1显示，0不显示
		'show_description' => '1',	//链接描述：1显示，0不显示
		'category_before' => '',
		'category_after' => '' ,
		'title_li' => __('Bookmarks'),
		'title_before' => '<h2 class="links-title">',
		'title_after' => '</h2>',
		'before' => '<li class="links-item">',
		'after' => '</li>',
		'between' => '',
		'link_before' => '<span>',
		'link_after' => '</span>',
		'orderby' => 'link_id',	//友情链接排序，可用：link_id、rand、url、name、target、description、owner、rating、updated、rss、length 等
) ); ?>
</div>
<?php endif; ?> 
<div class="clear"></div>
</div>
<div class="article article_c">
<?php comments_template(); ?>
</div>
</div>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>