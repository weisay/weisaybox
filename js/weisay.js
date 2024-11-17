jQuery(document).ready(function(){
jQuery('.article h2 a').click(function(){
	jQuery(this).text('页面载入中……');
	window.location = jQuery(this).attr('href');
	});
});

// 滚屏
jQuery(document).ready(function(){
jQuery('.roll_top').click(function(){jQuery('html,body').animate({scrollTop: '0px'}, 800);});
jQuery('.roll_comm').click(function(){jQuery('html,body').animate({scrollTop:jQuery('#comments').offset().top}, 800);});
jQuery('.roll_down').click(function(){jQuery('html,body').animate({scrollTop:jQuery('#footer').offset().top}, 800);});
});

//顶部导航下拉菜单，包含延迟效果
(function($){
jQuery.fn.hoverDelay = function(selector, options) {
	var defaults = {
		hoverDuring: 150,
		outDuring: 100,
		hoverEvent: jQuery.noop,
		outEvent: jQuery.noop
	};
	var sets = jQuery.extend(defaults, options || {});
	return jQuery(document).on("mouseenter mouseleave", selector, function(event) {
	var that = this;
	if(event.type == "mouseenter"){
		clearTimeout(that.outTimer);
		that.hoverTimer = setTimeout(
		function(){sets.hoverEvent.apply(that)},sets.hoverDuring);
	}else {
		clearTimeout(that.hoverTimer);
		that.outTimer = setTimeout(
		function(){sets.outEvent.apply(that)},sets.outDuring);
	}
	});
}
})(jQuery);
jQuery(document).ready(function(){
jQuery("#nav li,.toppage li").each(function(){
if(jQuery(this).find("ul").length!=0){jQuery(this).find("a:first").addClass("hasmenu")};
});
jQuery(".topnav ul li,.toppage ul li").hoverDelay(".topnav ul li,.toppage ul li", {
	hoverEvent: function(){
	jQuery(this).children("ul").show();
},
	outEvent: function(){
	jQuery(this).children("ul").hide();
}
});
});

//侧边栏TAB效果
jQuery(document).ready(function(){
	jQuery("#tabnav li").click(function(){
		jQuery(this).addClass("selected").siblings().removeClass("selected");
		jQuery("#tab-content > ul").eq(jQuery("#tabnav li").index(this)).addClass("active").siblings().removeClass("active"); 
	});
});

//图片渐隐
jQuery(document).ready(function(){
	jQuery('.thumbnail img').hover(
		function() {jQuery(this).fadeTo("fast", 0.7);},
		function() {jQuery(this).fadeTo("fast", 1);
	});
});

//文章编辑hover
jQuery(document).ready(function(){
jQuery('.article').hover(
	function() {
		jQuery(this).find('.edit').stop(true,true).fadeIn();
	},
	function() {
		jQuery(this).find('.edit').stop(true,true).fadeOut();
	}
);
});

//新窗口打开
jQuery(document).ready(function(){
	jQuery("a[rel='external'],a[rel='external nofollow'],a[rel='external nofollow ugc'],a[rel='ugc external nofollow']").click(
	function(){window.open(this.href);return false})
});

//顶部微博等图标渐隐
jQuery(document).ready(function(){
			jQuery('.rssicon').wrapInner('<span class="hover"></span>').css('textIndent','0').each(function () {
				jQuery('span.hover').css('opacity', 0).hover(function () {
					jQuery(this).stop().fadeTo(350, 1);
				}, function () {
					jQuery(this).stop().fadeTo(350, 0);
				});
			});
});

//打赏弹层
jQuery(document).ready(function(){
	jQuery('.zanzhu').click(function(){
		jQuery('.shang-bg').fadeIn(200);
		jQuery('.shang-content').fadeIn(400);
	});
	jQuery('.shang-bg, .shang-close').click(function(){
		jQuery('.shang-bg, .shang-content').fadeOut(400);
	});
});

//图片懒加载
jQuery(document).ready(function(){
	jQuery(".article img, .articles img").not("#respond_box img").lazyload({
		placeholder:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg==",
		effect:"fadeIn"
	});
});

//搜索框等伸缩
jQuery(document).ready(function(){
	if(jQuery.fn.avia_expand_element)
	{
	jQuery(".read-more-icon").avia_expand_element({subelements:'strong'});
	jQuery(".search_site").avia_expand_element({expandToWidth:323, speed:800});
	}
});
jQuery.extend( jQuery.easing,
{
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	}
});
(function($)
{
	jQuery.fn.avia_expand_element = function(options) 
	{
		var defaults =
			{
				expandToWidth: 'auto',
				subelements: false,
				easing: 'easeInOutCirc',
				speed: 600
			};
		var options = jQuery.extend(defaults, options);
		return this.each(function(i)
		{
			var el = jQuery(this),
				elWidthDefault = parseInt(el.width()),
				elNewWidth,
				sub;

			if(options.expandToWidth != 'auto')	
			{
				elNewWidth = parseInt(options.expandToWidth);
			}
			else
			{
				elNewWidth = parseInt(el.css({width:'auto'}).width());
				el.css({width:elWidthDefault});
			}
			if(options.subelements)
			{
				sub = el.find(options.subelements);
				sub.width(elNewWidth);
			}
			el.addClass('addapted');
			el.hover(function()
				{
					el.stop().animate({width: elNewWidth}, options.speed, options.easing);
				},
				
				function()
				{
					el.stop().animate({width: elWidthDefault}, options.speed, options.easing);
				}
			);
			
		});
	};
})(jQuery);

(function($)
{
	jQuery.fn.kriesi_empty_input = function(options)
	{
		return this.each(function()
		{
			var currentField = jQuery(this);
			currentField.methods =
			{
				startingValue:  currentField.val(),
				resetValue: function()
				{
					var currentValue = currentField.val();
					if(currentField.methods.startingValue == currentValue) currentField.val('');
				},
				restoreValue: function()
				{
					var currentValue = currentField.val();
					if(currentValue == '') currentField.val(currentField.methods.startingValue);
				}
			};
			currentField.bind('focus',currentField.methods.resetValue);
			currentField.bind('blur',currentField.methods.restoreValue);
		});
	};
})(jQuery);	

jQuery(document).ready(function(){
	// improves comment forms 下面这两行是控制搜索框内文字消失显示的
	if(jQuery.fn.kriesi_empty_input)
	jQuery('input:text').kriesi_empty_input();
	//expand elements
	if(jQuery.fn.avia_expand_element)
	{
		jQuery(".read-more-icon").avia_expand_element({subelements:'strong'});
		jQuery(".search_site").avia_expand_element({expandToWidth:323, speed:800});
	}
});