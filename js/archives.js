jQuery(document).ready(function() {
	function setsplicon(c, d) {
		if (c.html()=='+' || d=='+') {
			c.html('-');
			c.removeClass('car-plus');
			c.addClass('car-minus');
		} else if( !d || d=='-'){
			c.html('+');
			c.removeClass('car-minus');
			c.addClass('car-plus');
		}
	}
jQuery('div.car-collapse').find('span.car-yearmonth').click(function(e /* 1. 增加事件参数e */) {
	jQuery(this).next('ul').slideToggle('fast');
	setsplicon(jQuery(this).find('.car-toggle-icon'));
	e.stopImmediatePropagation();	// 2. 停止向上冒泡并且阻止元素绑定的同类型事件
});
jQuery('div.car-collapse').find('.car-toggler').click(function(e /* 1. 增加事件参数e */) {
	if ( '展开所有月份' == jQuery(this).text() ) {
		jQuery(this).parent('.car-container').find('.car-monthlisting').show();
		jQuery(this).text('折叠所有月份');
		setsplicon(jQuery('.car-collapse').find('.car-toggle-icon'), '+');
	}
	else {
		jQuery(this).parent('.car-container').find('.car-monthlisting').hide();
		jQuery(this).text('展开所有月份');
		setsplicon(jQuery('.car-collapse').find('.car-toggle-icon'), '-');
	}
	e.stopImmediatePropagation();	// 2. 停止向上冒泡并且阻止元素绑定的同类型事件
	return false;
});
jQuery("#archive-selector").change(function(){
var selval = parseInt(jQuery("#archive-selector").find("option:selected").val(), 10);
	if (selval == 0) {
	jQuery(".car-list li[class^='car-pubyear-']").show();
	} else {
	jQuery.each(jQuery(".car-list li[class^='car-pubyear-']"), function(i, obj){
		var orgval = parseInt(obj.className.replace("car-pubyear-", ""), 10);
				if (selval == orgval)
					obj.style.display='';
				else
			obj.style.display='none';
	});
	}
});

jQuery("#archive-selector").append("<option value='0'> 全部 </option>");
jQuery.each(jQuery(".car-list li[class^='car-pubyear-']"), function(i, obj){

	var year1 = obj.className.replace("car-pubyear-", "");
	if (jQuery("#archive-selector option[value=" + year1 + "]").length < 1)
		jQuery("#archive-selector").append("<option value='" + year1 + "'> " + year1 + "年 </option>");
});

});