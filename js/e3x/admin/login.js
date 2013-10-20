$(function(){
	$('#loginForm').validate({
		rules:{
			username: {
				required:true
			},
			password: {
				required:true
			}
		},
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		}
	});
});
