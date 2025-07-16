<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,maximum-scale=2.0,shrink-to-fit=no" />
<?php include('includes/seo.php'); ?>
<link rel="profile" href="http://gmpg.org/xfn/11">
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js?ver=3.7.1"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/mmenu.js?ver=<?php $theme=wp_get_theme(); echo $theme->get('Version'); ?>"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/dark.min.js?ver=<?php $theme=wp_get_theme(); echo $theme->get('Version'); ?>"></script>
<?php if ( is_singular() ){ ?>
<?php if (weisay_option('wei_prismjs') == 'displays') : ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/prism.js?ver=<?php $theme=wp_get_theme(); echo $theme->get('Version'); ?>"></script>
<?php endif; ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/comments-ajax.js?ver=<?php $theme=wp_get_theme(); echo $theme->get('Version'); ?>"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/realgravatar.js?ver=<?php $theme=wp_get_theme(); echo $theme->get('Version'); ?>"></script>
<?php } ?>
<?php wp_head(); ?>
<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/lazyload.js?ver=<?php $theme=wp_get_theme(); echo $theme->get('Version'); ?>"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/weisay.js?ver=<?php $theme=wp_get_theme(); echo $theme->get('Version'); ?>"></script>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
</head>
<body>
<div id="head">
<div id="header">
<div class="subpage">
<?php if (weisay_option('wei_toppage') != 'hide') : ?>
	<div class="toppage"><?php wp_nav_menu(array('theme_location' => 'topmenu')); ?></div>
<?php endif; ?>
<div id="rss"><ul>
<li class="rssfeed"><a href="<?php bloginfo('rss2_url'); ?>" target="_blank" class="rssicon" title="欢迎订阅<?php bloginfo('name'); ?>"></a></li>
</ul></div>
<div class="clear"></div>
</div>
<div class="webtitle">
	<div class="blogname">
	<h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
	<div class="blogtitle"><?php bloginfo('description'); ?></div></div> 
</div>
<div class="clear"></div>
<div class="headermenu">
	<a class="hamburger" onfocus="this.blur()" href="#menu"><span></span></a>
	<?php bloginfo('name'); ?>
</div>
<div class="clear"></div>
</div>
</div>
<div class="mainmenus">
<?php include('menu.php'); ?>
<div class="mainmenu">
<div id="nav" class="topnav"><?php wp_nav_menu( array( 'theme_location' => 'headermenu' ) ); ?></div>
<?php if (weisay_option('wei_search') != 'hide') : ?>
<div class="search">
<div class="search_site">
<form id="searchform" method="get" action="<?php bloginfo('url'); ?>/">
<input type="submit" value="" id="searchsubmit" class="button" />
<input type="text" required="" id="s" name="s" value="" placeholder="搜索"/>
</form>
</div></div>
<?php endif; ?>
<div class="clear"></div>
</div>
</div>