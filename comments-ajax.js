/**
 * WordPress jQuery-Ajax-Comments v1.3 by Willin Kan.
 * URI: http://kan.willin.org/?p=1271
 */
var i = 0, got = -1, len = document.getElementsByTagName('script').length;
while ( i <= len && got == -1){
	var js_url = document.getElementsByTagName('script')[i].src,
			got = js_url.indexOf('comments-ajax.js'); i++ ;
}
var edit_mode = '1', // 再編輯模式 ( '1'=開; '0'=不開 )
		ajax_php_url = js_url.replace('comments-ajax.js','comments-vajax.php'),
		wp_url = js_url.substr(0, js_url.indexOf('wp-content')),
		pic_sb = wp_url + 'wp-admin/images/wpspin_light.gif', // 提交 icon
		pic_no = wp_url + 'wp-admin/images/no.png',      // 錯誤 icon
		pic_ys = wp_url + 'wp-admin/images/yes.png',     // 成功 icon
		txt1 = '<div id="loading" class="comment-tips"><img src="' + pic_sb + '" style="vertical-align:middle;" alt=""/> 正在提交, 请稍候...</div>',
		txt2 = '<div id="error" class="comment-tips">#</div>',
		txt3 = '"> <div id="edita"><img src="' + pic_ys + '" style="vertical-align:middle;" alt=""/> 提交成功',
		edt1 = ',刷新页面之前你可以 <a rel="nofollow" class="comment-reply-link comment-reply-link-edit" href="#edit" onclick=\'return addComment.moveForm("',
		edt2 = ')\'>再次编辑</a></div> ',
		cancel_edit = '取消编辑',
		edit, num = 1, comm_array=[]; comm_array.push('');

jQuery(document).ready(function($) {
		$comments = $('#comments-title'); // 評論數的 ID
		$cancel = $('#cancel-comment-reply-link'); cancel_text = $cancel.text();
		$submit = $('#commentform #submit'); $submit.attr('disabled', false);
		$('#comment').after( txt1 + txt2 ); $('#loading').hide(); $('#error').hide();
		$body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');

/** submit */
$('#commentform').submit(function() {
		$('#loading').slideDown();
		$submit.attr('disabled', true).fadeTo('slow', 0.5);
		if ( edit ) $('#comment').after('<input type="text" name="edit_id" id="edit_id" value="' + edit + '" style="display:none;" />');

/** Ajax */
	$.ajax( {
		url: ajax_php_url,
		data: $(this).serialize(),
		type: $(this).attr('method'),

		error: function(request) {
			$('#loading').slideUp();
			$('#error').slideDown().html('<img src="' + pic_no + '" style="vertical-align:middle;" alt=""/> ' + request.responseText);
			setTimeout(function() {$submit.attr('disabled', false).fadeTo('slow', 1); $('#error').slideUp();}, 3000);
			},

		success: function(data) {
			$('#loading').hide();
			comm_array.push($('#comment').val());
			$('textarea').each(function() {this.value = ''});
			var t = addComment, cancel = t.I('cancel-comment-reply-link'), temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId), post = t.I('comment_post_ID').value, parent = t.I('comment_parent').value;

// comments
		if ( ! edit && $comments.length ) {
			n = parseInt($comments.text().match(/\d+/));
			$comments.text($comments.text().replace( n, n + 1 ));
		}

// show comment
		new_htm = '" id="new_comm_' + num + '"></';
		new_htm = ( parent == '0' ) ? ('\n<ol style="clear:both;" class="comment-list' + new_htm + 'ol>') : ('\n<ul class="children' + new_htm + 'ul>');

		ok_htm = '\n<span id="success_' + num + txt3;
		if ( edit_mode == '1' ) {
			div_ = (document.body.innerHTML.indexOf('div-comment-') == -1) ? '' : ((document.body.innerHTML.indexOf('li-comment-') == -1) ? 'div-' : '');
			ok_htm = ok_htm.concat(edt1, div_, 'comment-', parent, '", "', parent, '", "respond", "', post, '", ', num, edt2);
		}
		ok_htm += '</span><span></span>\n';

		$('#respond').before(new_htm);
		$('#new_comm_' + num).hide().append(data);
		$('#new_comm_' + num + ' li').append(ok_htm);
		$('#new_comm_' + num).fadeIn(4000);

		$body.animate( { scrollTop: $('#new_comm_' + num).offset().top - 200}, 900);
		countdown(); num++ ; edit = ''; $('*').remove('#edit_id');
		cancel.style.display = 'none';
		cancel.onclick = null;
		t.I('comment_parent').value = '0';
		if ( temp && respond ) {
			temp.parentNode.insertBefore(respond, temp);
			temp.parentNode.removeChild(temp)
		}
		}
	}); // end Ajax
	return false;
}); // end submit

/** comment-reply.dev.js */
addComment = {
	moveForm : function(commId, parentId, respondId, postId, num, replyTo) {
		var t = this, div, comm = t.I(commId), respond = t.I(respondId), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent'), post = t.I('comment_post_ID');
		if ( edit ) exit_prev_edit();
		num ? (
			t.I('comment').value = comm_array[num],
			edit = t.I('new_comm_' + num).innerHTML.match(/(comment-)(\d+)/)[2],
			$new_sucs = $('#success_' + num ), $new_sucs.hide(),
			$new_comm = $('#new_comm_' + num ), $new_comm.hide(),
			$cancel.text(cancel_edit)
		) : $cancel.text(cancel_text);

		t.respondId = respondId;
		postId = postId || false;

		if ( !t.I('wp-temp-form-div') ) {
			div = document.createElement('div');
			div.id = 'wp-temp-form-div';
			div.style.display = 'none';
			respond.parentNode.insertBefore(div, respond)
		}

		!comm ? (
			temp = t.I('wp-temp-form-div'),
			t.I('comment_parent').value = '0',
			temp.parentNode.insertBefore(respond, temp),
			temp.parentNode.removeChild(temp)
		) : comm.parentNode.insertBefore(respond, comm.nextSibling);

		$body.animate( { scrollTop: $('#respond').offset().top - 180 }, 400);

		replyTo = replyTo || false;

		if (replyTo) {
			if (!$("#respond #reply-title").data("original-title"))
				$("#respond #reply-title").data("original-title", $("#respond #reply-title").html());
			$("#respond #reply-title").html(replyTo);
		}

		if ( post && postId ) post.value = postId;
		parent.value = parentId;
		cancel.style.display = '';

		cancel.onclick = function() {
			if ( edit ) exit_prev_edit();
			var t = addComment, temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId);

			t.I('comment_parent').value = '0';
			if ( temp && respond ) {
				temp.parentNode.insertBefore(respond, temp);
				temp.parentNode.removeChild(temp);
			}
			this.style.display = 'none';
			this.onclick = null;

			if ($("#respond #reply-title").data("original-title"))
				$("#respond #reply-title").html($("#respond #reply-title").data("original-title"));

			return false;
		};

		try { t.I('comment').focus(); }
		catch(e) {}

		return false;
	},

	I : function(e) {
		return document.getElementById(e);
	}
}; // end addComment

function exit_prev_edit() {
		$new_comm.show(); $new_sucs.show();
		$('textarea').each(function() {this.value = ''});
		edit = '';
}

var wait = 12, submit_val = $submit.val();
function countdown() {
	if ( wait > 0 ) {
		$submit.val(wait); wait--; setTimeout(countdown, 1000);
	} else {
		$submit.val(submit_val).attr('disabled', false).fadeTo('slow', 1);
		wait = 12;
	}
}
});// end jQ

// Ajax走心评论点击按钮切换图标
function post_karma(comment_id, action_url, elem) {
	elem.innerHTML = "<span><svg t='1691142752046' class='icon' viewBox='0 0 1024 1024' version='1.1' xmlns='http://www.w3.org/2000/svg' p-id='2098' width='16' height='16'><path d='M375.5105 766.646c-17.5455 0-32.7285-7.812-36.288-25.011l-49.1715-235.3995-32.382 55.9125c-6.6465 11.277-18.8055 19.3095-31.878 19.3095H46.7135a37.044 37.044 0 0 1 0-74.088h157.9095l70.4025-118.9755a37.044 37.044 0 1 1 68.1345 11.3085l29.7675 143.8605L440.81 166.949a36.54 36.54 0 0 1 36.477-29.8935h0.1575c17.9865 0 33.327 12.474 36.351 30.177l72.45 422.7615 23.31-59.0625c5.544-14.175 19.215-23.5935 34.4295-23.5935h328.671a37.044 37.044 0 0 1 0 74.088h-303.5025l-62.685 158.445A37.0125 37.0125 0 0 1 535.5305 732.5L476.405 387.071 411.956 740.69c-3.15 17.451-18.2385 25.956-35.973 25.956h-0.4725z' fill='#d81e06' p-id='2099'></path></svg></svg></span>";
	var origin_karma = elem.getAttribute("data-karma");
	var new_karma = Number(!parseInt(origin_karma));
	var formData = new FormData();
	formData.append('comment_id', comment_id);
	formData.append('comment_karma', new_karma);
	$.ajax({
		type: 'POST',
		url: action_url,
		data: new URLSearchParams(formData).toString(),
		dataType: 'json',
		timeout: 10000,
		beforeSend: function (xhr) {
		}
	}).done(function (data) {
		if (data.code == 200) {

			elem.setAttribute("data-karma", new_karma);
		} else {
			alert('设置失败');
		}
	}).fail(function (jqXHR, textStatus, errorThrown) {
		alert('设置失败(原因：\'' + textStatus + '\')，请稍后再试');
	}).always(function (jqXHR, textStatus) {
		if (elem.getAttribute("data-karma") == '0') {
			elem.innerHTML = "<span title='加入走心'><svg t='1691142362631' class='icon' viewBox='0 0 1024 1024' version='1.1' xmlns='http://www.w3.org/2000/svg' p-id='3461' ><path d='M709.56577067 110.4732032c-96.8271424 0-166.18710933 87.25008853-196.0785664 133.39655893-29.9242016-46.1464704-99.2525152-133.39655893-196.07747414-133.39655893-138.9415136 0-251.94071467 125.20683413-251.94071466 279.09134827 0 71.95780053 48.8076128 175.11579733 108.0556768 229.1037952 81.95836693 105.30066347 312.36872 294.85954133 340.81281173 294.85954133 28.94728533 0 254.41302293-185.87497493 337.85259093-293.59773653 60.28719787-54.93435093 109.33167147-158.2342464 109.33167147-230.3656C961.52176747 235.6789472 848.50401067 110.4732032 709.56577067 110.4732032M902.11434027 389.56455147c0 57.54855787-41.73561173 143.42877973-91.125008 187.5253632-1.35349333 1.2301504-2.58255147 2.58364373-3.81161067 4.06593706-73.42262933 95.66248427-221.2448032 214.31688427-292.6830368 266.2877408C461.38864 808.5743296 301.43851307 687.4618112 219.3229664 580.77818347c-1.1024416-1.44954773-2.39371733-2.80522347-3.74721067-4.06593707-49.2027456-44.03436693-90.71568533-129.69410027-90.71568533-187.14769493 0-121.14308053 86.3670432-219.71666667 192.5496608-219.71666667 68.4452672 0 134.3407296 74.08409387 169.27394667 147.5383776 4.6291648 9.7331424 14.8982464 15.7954816 26.80461866 15.7954816s22.17436267-6.0634304 26.83518187-15.7954816c34.90156373-73.45428373 100.76427947-147.5383776 169.24338453-147.5383776C815.7451136 169.8478848 902.11434027 268.42147093 902.11434027 389.56455147' fill='#d81e06' p-id='3462'></path></svg></span>";
		} else {
			elem.innerHTML = "<span title='取消走心'><svg t='1691141971354' class='icon' viewBox='0 0 1024 1024' version='1.1' xmlns='http://www.w3.org/2000/svg' p-id='3103' ><path d='M709.56577067 110.4732032c-96.8271424 0-166.18710933 87.25008853-196.0785664 133.39655893-29.9242016-46.1464704-99.2525152-133.39655893-196.07747414-133.39655893-138.9415136 0-251.94071467 125.20683413-251.94071466 279.09134827 0 71.95780053 48.8076128 175.11579733 108.0556768 229.1037952 81.95836693 105.30066347 312.36872 294.85954133 340.81281173 294.85954133 28.94728533 0 254.41302293-185.87497493 337.85259093-293.59773653 60.28719787-54.93435093 109.33167147-158.2342464 109.33167147-230.3656C961.52176747 235.6789472 848.50401067 110.4732032 709.56577067 110.4732032' fill='#d81e06' p-id='3104'></path></svg></span>";
		}
	});
	return false;
}