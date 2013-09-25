var course_list = new Array();
var getCourseList = function(){
	$('.course_list').each(function(i, e){
		course_list.push($(e).data('id'));
	});
}
var getLatestAnnounce = function(){
	var client = new $.RestClient('/API/')
	client.add('announce');
	var req = client.announce.read('login').done(function(data){
		$('#announcement .loading').remove();
		$('#tab-announcement img').remove();
		var unread = 0;
		var newDiv = $('<div>').addClass('label label-danger pull-right').text('New');
		for(var i=0; i<data.length; ++i){
			var container = $('<div>').appendTo($('#announcement'));
			var header = $('<div>').appendTo(container);
			var body = $('<div>').appendTo(container);
			container.addClass('panel panel-primary');
			header.addClass('panel-heading');
			body.addClass('panel-body');
			var ann = data[i];
			header.html('<h2>'+ann.Caption+' <small>'+ann.BeginDate+'</small></h2>');
			body.html(ann.Content);
			//Process Flag
			if(ann.flag.indexOf('read') == -1){
				++unread;
				header.prepend(newDiv.clone());
			}
		}
		var unread_div = $('<div>').prependTo($('#tab-announcement a'));
		unread_div.addClass('badge pull-right');
		unread_div.text(unread);
	});
}

var addLoading = function(){
	$('.tab-pane').append(loadingDiv);
}

var loadingDiv = $('<div>').addClass('loading').css('text-align', 'center').text('截取資料中...').append(
	$('<img>').attr('src', '/img/loading.gif')
);

addLoading();
getCourseList();
getLatestAnnounce();
/*
var client = new $.RestClient('/API/');
client.add('user');
var req = client.user.create('login', {id:form.id.value, password:form.password.value}).done(function(data){
if(data.error){
	*/