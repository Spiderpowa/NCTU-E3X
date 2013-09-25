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
