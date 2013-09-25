var course_list = new Array();
var announcement = new Array();
var getCourseList = function(){
	$('.course_list').each(function(i, e){
		course_list.push($(e).data('id'));
	});
}

var getLatestAnnounce = function(){
	var client = new $.RestClient('/API/');
	client.add('announce');
	var req = client.announce.read('login').done(function(data){
		api_error_handle(data);
		announcement = new Array();
		$('#announcement .loading').remove();
		$('#tab-announcement img').remove();
		$('#announcement .panel').remove();
		var action_bar = $('.hide .announcement-action');
		for(var i=0; i<data.length; ++i){
			var container = $('<div id="announcement-entry-'+i+'">').appendTo($('#announcement'));
			var header = $('<div>').appendTo(container);
			var body = $('<div>').appendTo(container);
			container.addClass('panel');
			header.addClass('panel-heading');
			body.addClass('panel-body');
			var ann = data[i];
			announcement.push(ann);
			header.html('<h2>'+ann.Caption+' <small>'+ann.BeginDate+'</small></h2>');
			body.html(ann.Content);
			var bar = action_bar.clone(true);
			body.prepend(bar);
			bar.data('id', i);
			//bar.find('button').click(setFlag);
		}
		applyAnnounceFlag();
		$('.filter_hidden').hide();//Directly hide hidden announcement
	});
}

var applyAnnounceFlag = function(){
	var unread = 0;
	var newDiv = $('<div>').addClass('label label-danger pull-right').text('New');
	var hideDiv = $('<div>').addClass('label label-info pull-right').text('Hidden');
	$('#tab-announcement a .badge').remove();
	$('#announcement .panel .label').remove();
	for(var i=0; i<announcement.length; ++i){
		var ann = announcement[i];
		var container = $('#announcement-entry-'+i);
		var header = container.find('.panel-heading');
		var body = container.find('.panel-body');
		//Remove previous
		container.removeClass(function(i, css){
			return (css.match (/\b(panel-|filter_)\S+/g) || []).join(' ');
		});
		//Process Flag
		if(ann.flag.indexOf('read') == -1){
			++unread;
			header.prepend(newDiv.clone());
			container.addClass('panel-primary');
		}else{
			container.addClass('panel-default');
		}
		if(ann.flag.indexOf('hidden') != -1){
			header.prepend(hideDiv.clone());
			container.addClass('panel-info');
		}
		for(var j=0; j<ann.flag.length; ++j){
			container.addClass('filter_'+ann.flag[j]);
		}
	}
	if(unread > 0){
		var unread_div = $('<div>').prependTo($('#tab-announcement a'));
		unread_div.addClass('badge pull-right');
		unread_div.text(unread);
	}
}

var setFlag = function(){
	var id = $(this).parent().data('id');
	var flag = $(this).data('flag');
	var ann = announcement[id];
	var ann_id = ann.BulletinId;
	var client =  new $.RestClient('/API/');
	client.add('flag');
	client.flag.create({type:'announcement', id:ann_id, flag:flag}).done(function(data){
		api_error_handle(data);
	});
	ann.flag.push(flag);
	applyAnnounceFlag();
	applyAnnounceFilter();
}

var initAnnounceComponent = function(){
	//Filter
	var filter = $('#announcement-filter li a');
	filter.click(function(){
		$(this).parent().toggleClass('active');
		applyAnnounceFilter();
	});
	//Action Bar
	$('.hide .announcement-action button').click(setFlag);
	//Hide All Announcement
	$('#read-all-announcement').click(function(){
		var r;
		bootbox.confirm('全部標記成已讀?', function(result){
			if(result){
				var ids = new Array();
				$.each(announcement, function(i, e){
					ids.push(e.BulletinId);
					e.flag.push('read');
				});
				var client =  new $.RestClient('/API/');
				client.add('flag');
				client.flag.create({type:'announcement', id:ids.toString(), flag:'read'}).done(function(data){
					api_error_handle(data);
				});
				applyAnnounceFlag();
				applyAnnounceFilter();
			}
		});
	});
}

var applyAnnounceFilter = function(){
	$('#announcement .panel').removeClass('announce_hide');
	$('#announcement .panel').addClass('announce_show');
	var filter = $('#announcement-filter li a');
	$.each(filter, function(i, e){
		var isActive = $(e).parent().hasClass('active');
		var filterType = $(e).data('filter');
		var entries = $('.filter_'+filterType);
		//*
		if(!isActive){
			entries.addClass('announce_hide');
			entries.removeClass('announce_show');
		}
		//*/
		/*
		if(isActive)entries.show('blind', {direction: 'up' }, 'fast');
		else entries.hide('blind', {direction: 'up' }, 'fast');
		*/
	});
	
	$('#announcement .announce_hide').hide('blind', {direction: 'up' }, 'slow');
	$('#announcement .announce_show').show('blind', {direction: 'up' }, 'slow');
	
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
initAnnounceComponent();
/*
var client = new $.RestClient('/API/');
client.add('user');
var req = client.user.create('login', {id:form.id.value, password:form.password.value}).done(function(data){
if(data.error){
	*/