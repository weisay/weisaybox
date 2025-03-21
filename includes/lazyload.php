<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/lazyload.js"></script>
<script type="text/javascript">
	jQuery(function() {          
    	jQuery(".article img, .articles img").not("#respond_box img").lazyload({
        	placeholder:"<?php bloginfo('template_url'); ?>/images/image-pending.gif",
            effect:"fadeIn"
          });
    	});
</script>