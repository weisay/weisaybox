<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,maximum-scale=2.0,shrink-to-fit=no" />
<?php require get_template_directory() . '/includes/seo.php'; ?>
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php $theme = wp_get_theme(); $themeversion = $theme -> get('Version'); ?>
<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/js/jquery.min.js?ver=3.7.1'); ?>"></script>
<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/js/mmenu.js?ver=' . $themeversion); ?>"></script>
<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/js/dark.min.js?ver=' . $themeversion); ?>"></script>
<?php if ( is_singular() ){ ?>
<?php if (weisay_option('wei_prismjs') == 'open') : ?>
<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/js/prism.js?ver=' . $themeversion); ?>"></script>
<?php endif; ?>
<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/comments-ajax.js?ver=' . $themeversion); ?>"></script>
<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/js/realgravatar.js?ver=' . $themeversion); ?>"></script>
<?php } ?>
<?php wp_head(); ?>
<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/js/lazyload.js?ver=' . $themeversion); ?>"></script>
<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/js/weisay.js?ver=' . $themeversion); ?>"></script>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php echo weisay_option('wei_headcustom'); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
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
	<h1><a href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo('name'); ?></a></h1>
	<div class="blogtitle"><?php bloginfo('description'); ?></div></div> 
</div>
<div class="clear"></div>
<div class="headermenu">
	<a class="hamburger" onfocus="this.blur()" href="#menu" rel="nofollow" aria-label="菜单"><span></span></a>
	<?php bloginfo('name'); ?>
</div>
<div class="clear"></div>
</div>
</div>
<div class="mainmenus">
<?php require get_template_directory() . '/menu.php'; ?>
<div class="mainmenu">
<div id="nav" class="topnav"><?php wp_nav_menu( array( 'theme_location' => 'headermenu' ) ); ?></div>
<?php if (weisay_option('wei_search') != 'hide') : ?>
<div class="search">
<div class="search_site">
<form id="searchform" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
<input type="submit" value="" id="searchsubmit" class="button" />
<input type="text" required="" id="s" name="s" value="" placeholder="搜索"/>
</form>
</div></div>
<?php endif; ?>
<div class="clear"></div>
</div>
</div>