<?php if ( is_user_logged_in() ) : ?>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded",()=>{new Mmenu("#menu",{setSelected:{hover:true,parent:true,},counters:{add:true,},navbar:{title:'导航'},navbars:[{"position":"top","content":['<div class="mm-navbar__searchfield" id="mm-0"><form class="mm-searchfield" action="<?php bloginfo('url'); ?>/" method="get" ><div class="mm-searchfield__input"><input class="" type="text" autocomplete="off" name="s" placeholder="Search" aria-label="Search"></div></form></div>']},{position:"bottom",content:['<a href="<?php bloginfo('url') ?>/wp-admin/">仪表盘</a>',],},],})});
</script>
<nav id="menu">
<?php wp_nav_menu( array( 'theme_location' => 'leftmenu' ) ); ?>
</nav>
<?php else: ?>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded",()=>{new Mmenu("#menu",{setSelected:{hover:true,parent:true,},counters:{add:true,},navbar:{title:'导航'},navbars:[{"position":"top","content":['<div class="mm-navbar__searchfield" id="mm-0"><form class="mm-searchfield" action="<?php bloginfo('url'); ?>/" method="get" ><div class="mm-searchfield__input"><input class="" type="text" autocomplete="off" name="s" placeholder="Search" aria-label="Search"></div></form></div>']},],})});
</script>
<nav id="menu">
<?php wp_nav_menu( array( 'theme_location' => 'leftmenu' ) ); ?>
</nav>
<?php endif; ?>