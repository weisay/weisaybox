<div class="clear"></div>
<div id="footer">
<p>Copyright © <?php echo weisay_option('wei_websiteyear'); ?> <?php bloginfo('name'); ?>. <span class="power">Powered by <a href="https://www.wordpress.org/" rel="external">WordPress</a>. Theme by <a href="https://www.weisay.com/" rel="external">Weisay</a>. <?php if (weisay_option('wei_beian') == 'display') { ?><a href="https://beian.miit.gov.cn/" rel="external nofollow"><?php echo weisay_option('wei_beianhao'); ?></a><?php { echo '.'; } ?><?php } else { } ?> <?php if (weisay_option('wei_gwab') == 'display') { ?><a href="https://www.beian.gov.cn/portal/registerSystemInfo" rel="external nofollow"><?php echo weisay_option('wei_gwabh'); ?></a><?php { echo '.'; } ?><?php } else { } ?></span></p>
</div>
<?php wp_footer(); ?>
<?php if(current_user_can('level_10')) : ?><style type="text/css">@media screen and (max-width:991px){#wpadminbar{display:none;}html{margin-top:0px !important;}}</style><?php endif;?>
</body>
</html>