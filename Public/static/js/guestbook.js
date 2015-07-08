/**
 * CDVPHP框架：快速入门留言本例子
 * 
 * Filename: guestbook.js
 * Author: fanjiapeng Ctime: 2014-03-15
 */
 
 var guestbook = {
	 
	'OBJ_ID' : ['admin_login'],
	
	 
	 // 空值返回 true  非空值返回 false
	'is_empty': function(id_name) {
		
		if(!id_name) return true;
		if(!$.trim($("#" + id_name).val())) return true;
		
		return false;
	},
	
	'toggle_border': function(id_name, toggle) {
		if(!id_name) return false;
		
		$('#' + id_name).css('border-color', toggle ? '#b94a48' : '#ccc').focus();
	},
	
	'send': function(url, method, data) {
		$.ajax({
			url: url,
			type: method,
			timeout: 5000,
			data : data ? data : '',
			dataType: 'json',
			success: function(data) {
				if(data['errno'] == 0) {
					window.location.href = data['errmsg'];
				} else {
					guestbook.notice(1, data['errmsg']);
					$("#imgcode_do").click();
					$('#code').val('');
				}
			},
			error : function() {
				alert("网页已经过期，请重新提交。");	
			}
		});
		
	},
	 
	'admin_login': function() {

		if(this.is_empty('username')) {
			this.toggle_border('username', 1);
			return false;
		}
		
		if(this.is_empty('password')) {
			this.toggle_border('password', 1);
			return false;
		}
		
		if(this.is_empty('code')) {
			this.toggle_border('code', 1);
			return false;
		}

		this.send($('#form-horizontal').attr('action'), 'POST', 
			"username="+$.trim($('#username').val())+" \
			&password="+$.md5($.trim($('#password').val()))+" \
			&remember="+($('input[name=remember]:checked').val()||0)+"");

		return false;
	},
	
	// 所有页面DOM元素事件初始化
	'init': function() {
		
		$('input').bind('keyup', function() {
			if($.trim($(this).val()).length > 0) $(this).css('border-color', '#ccc');					  
		});
		
		for (var key in guestbook.OBJ_ID) {
			
			var obj = $('#' + this.OBJ_ID[key]);
			
			if(obj.length < 1) {
				continue;
			}

			obj.bind('click', function(){			 
				return guestbook[guestbook.OBJ_ID[key]]();			 
			})	
		}
	},
	
	// 提交信息提示
	'notice': function(bool, data) {
		if(bool > 0) {
			$(".alert").show().addClass('alert-error').removeClass('alert-success').html('<strong>系统提示：</strong> '+data+'！');
		} else if(bool == 0) {
			$(".alert").show().addClass('alert-success').removeClass('alert-error').html('<strong>恭喜你：</strong> '+data+'！');
		}
	}
	
}

$(function(){
	guestbook.init();	   
})
