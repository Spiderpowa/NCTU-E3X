/* Login & Registeration Model */
var loginAjax = null;
var registerAjax = null;
var modalAlert = function(id, type, msg){
	$(id + ' .alert').hide();
	$(id + ' .alert-'+type).text(msg).show();
}
var msg = function(message, title){
	var msgModal = $('#msgModal');
	if(message === undefined){//close modal
		return msgModal.modal('hide');
	}
	if(title === undefined)title='';
	msgModal.find('.modal-title').text(title);
	msgModal.find('.modal-body').html(message);
	return msgModal.modal('show');
}
$('#loginForm').validate({
	rules:{
		id: {required:true},
		password: {required:true}
	},
	highlight: function(element) {
		$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	},
	success: function(element) {
		$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
	},
	submitHandler:function(form){
		modalAlert('#loginModal', 'info', '登入中...請稍後');
		var client = new $.RestClient('/API/');
		client.add('user');
		var req = client.user.create('login', {id:form.id.value, password:form.password.value}).done(function(data){
			if(data.error){
				modalAlert('#loginModal', 'danger', data.error);
			}else{
				modalAlert('#loginModal', 'success', '登入成功!');
				location.href = '/dashboard';
			}
		});
	}
	
});
$('#registerForm').validate({
	rules:{
		id: {required:true},
		serial: {required:true}
	},
	highlight: function(element) {
		$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	},
	success: function(element) {
		$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
	},
	submitHandler:function(form){
		modalAlert('#registerModal', 'info', '請稍後...你的帳號馬上就要準備好了!');	
		var formArray = $(form).serializeArray();
		var data = {};
		$.each(formArray, function(i, e){
			data[e.name] = e.value;
		});
		data.username = data.id;
		registerAjax = $.ajax({
			url:'/API/user/register',
			method:'post',
			dataType:'json',
			data:data
		}).done(function(data){
			if(data.error){
				$('#registerModal').modal('show');
				$('#registerModal .modal-footer .btn').attr('disabled', false);
				modalAlert('#registerModal', 'danger', data.error);
			}else{
				$('#registerModal').modal('hide');
				$('#loginModal').modal('show');
				modalAlert('#loginModal', 'success', '帳號準備好了!馬上體驗E3X吧!');
				$('#loginId').val(data.username);
			}
		});
		return false;
	}
});
$('form div.modal').on('show.bs.modal', function(){
	if(loginAjax != null) loginAjax.abort();
	if(registerAjax != null) registerAjax.abort();
	loginAjax = registerAjax = null;
	$('form div.modal .modal-body .alert').hide();
	$('form div.modal .modal-footer .btn').attr('disabled', false);
});
var parse_parameter = function(){
	var msg = getParameterByName('login_error');
	if(msg.length){
		$('#loginModal').modal('show');
		modalAlert('#loginModal', 'danger', msg);
	}
};

parse_parameter();
/* Facebook Comment Show */
var FBShow = function (){
  var client;
  var self = this;
  var msg;
  var loopInt = null;
  var msgIdx;
  this.init = function (){
    msg = new Array();
    client = new $.RestClient('/API/');
    client.add('FB');
    var req = client.FB.read('comment', {path:'/'}).done(function(data){
      $.each(data, function(i, v){
        parseData(v);
      });
      self.startShowLoop();
    });
  }
  var parseData = function(data){
    for(var i=0; i<data.comments.data.length; ++i){
      msg.push(data.comments.data[i]);
    }
  }
  var preloadImage = function(){
    var imgObj = new Image();
    imgObj.src = 'https://graph.facebook.com/'+msg[msgIdx].from.id+'/picture?type=large&width=200&height=200';
  }
  var showNextMessage = function(){
    var message = msg[msgIdx++];
    msgIdx %= msg.length;
    (function(message){
      var img = $('#fb-comment-message #fb-comment-message-head img');
      var quote = $('#fb-comment-message #fb-comment-message-text blockquote p');
      var from = $('#fb-comment-message #fb-comment-message-text blockquote small');
      var blockquote = $('#fb-comment-message #fb-comment-message-text blockquote');
      img.fadeOut(200, function(){
        img.attr('src', 'https://graph.facebook.com/'+message.from.id+'/picture?type=large&width=200&height=200');
        img.fadeIn();
      });
      blockquote.fadeOut(200, function(){
        quote.text(message.message);
        from.text(message.from.name);
        blockquote.fadeIn();
      });
    })(message);
    preloadImage();
  }
  this.startShowLoop = function(){
    if(loopInt == null){
      msgIdx = 0;
      preloadImage();
      setTimeout(showNextMessage, 1000);
      loopInt = setInterval(showNextMessage, 5000);
    }else{
      stopShowLoop();
      startShowLoop();
    }
  }
  
  this.stopShowLoop = function(){
    if(loopInt == null)return;
    clearInterval(loopInt);
    loopInt = null;
  }
}

var fbshow = new FBShow();
$(function(){
  fbshow.init();
});

prefetch('/js/e3x/dashboard.js');