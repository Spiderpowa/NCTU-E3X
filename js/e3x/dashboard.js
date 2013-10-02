var course_list = new Array();
/* Announcement */
var announcement = new Array();
var getCourseList = function(){
	$('.course_list').each(function(i, e){
		course_list.push({
			id:$(e).data('id'),
			name:$(e).data('name')
		});
	});
}

var getLatestAnnounce = function(){
	var client = new $.RestClient('/API/');
	client.add('announce');
	var req = client.announce.read('login').always(function(data){
		api_error_handle(data);
		announcement = new Array();
		$('#announcement .loading').remove();
		$('#tab-announcement img').remove();
		$('#announcement .panel').remove();
		var action_bar = $('.hide .announcement-action');
		for(var i=0; i<data.length; ++i){
			var ann = data[i];
			announcement.push(ann);
			$.extend(ann, {
				type:'announcement'
			});
			genPanel(ann, i);
		}
		applyAnnounceFlag();
	});
}

var applyAnnounceFlag = function(){
	var unread = 0;
	var newDiv = $('<div>').addClass('label label-danger pull-right').text('New');
	var hideDiv = $('<div>').addClass('label label-info pull-right').text('Hidden');
	var starDiv = $('<div>').addClass('label label-warning pull-right').append('<span class="glyphicon glyphicon-star"></span>');
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
		var hidden = false, star = false;
		if(ann.flag.indexOf('hidden') != -1){//Hidden
			hidden = true;
			header.prepend(hideDiv.clone());
			container.addClass('panel-info');
			container.appendTo('#hidden-announcement');
		}
		if(ann.flag.indexOf('star') != -1){//Star
			star = true;
			header.prepend(starDiv.clone());
			container.data('star', 'true');
			body.find('button.no-star').attr('disabled', 'disabled');
		}else{
			container.removeData('star');
			body.find('button.no-star').attr('disabled', false);
		}
		
		if(ann.flag.indexOf('read') == -1){//Unread
			if(!hidden && !star){
				++unread;
				header.prepend(newDiv.clone());
			}
			if(!hidden){
				container.addClass('panel-primary');
				container.appendTo('#unread-announcement');
			}
		}else if(!hidden){
			container.addClass('panel-default');
			container.appendTo('#read-announcement');
			body.collapse();
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

var setAnnounceFlag = function(){
	var container = $($(this).parent().data('container'));
	var id = $(this).parent().data('id');
	var flag = $(this).data('flag');
	var ann = announcement[id];
	var ann_id = ann.BulletinId;
	var client =  new $.RestClient('/API/');
	client.add('flag');
	var action = '';
	var data = {type:'announcement', id:ann_id, flag:flag};
	if(flag == 'star' && container.data('star')){//unstar
		action = 'remove';
	}
	if (flag == 'star' && !container.data('star')){//star
		//remove all other flags
		ann.flag = new Array();
		(function(data){
			var flagdata = $.extend(true, {}, data);//Deep Copy
			flagdata.flag = null;
			client.flag.create('remove', flagdata).done(function(data){
				api_error_handle(data);
				flagdata.flag = 'star';
				client.flag.create(action, flagdata).done(api_error_handle);
			});
		})(data);
	}else{
		client.flag.create(action, data).done(api_error_handle);
	}
	if(action == 'remove'){
		container.removeData(flag);
		var index;
		while((index = ann.flag.indexOf(flag)) != -1){
			ann.flag.splice(index, 1);
		}
	}else{
		ann.flag.push(flag);
	}
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
	$('.hide .announcement-action button').click(setAnnounceFlag);
	//Hide All Announcement
	$('#read-all-announcement').click(function(){
		bootbox.confirm('全部標記成已讀?', function(result){
			if(result){
				var ids = new Array();
				$.each(announcement, function(i, e){
					if(e.flag.indexOf('star')!=-1)return;
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
	var active_filter = new Array();
	var filter = $('#announcement-filter li a');
	$.each(filter, function(i, e){
		if($(e).parent().hasClass('active'))
			active_filter.push($(e).data('filter'));
	});
	if(active_filter.indexOf('hidden') != -1){
		$('.announcement-container').hide();
		$('#hidden-announcement').show();
	}else{
		$('.announcement-container').show();
		$('#hidden-announcement').hide();
	}
}
/* Homework */
var homework = new Array();
var homeworkDone = 0;
var getHomeworkList = function(){
	homework = new Array();
	homeworkDone = 0;
	var client = new $.RestClient('/API/');
	client.add('homework');
	var renderHomework = function(){
		$('#homework .loading').remove();
		$('#tab-homework img').remove();
		$('#homework .panel').remove();
		for(var i=0; i<homework.length; ++i){
			var data = homework[i];
			var content = '<div class="homework-due-date">截止日期:<span>'+data.EndDate+'</span></div>'+
				'<div class="homework-submit-type">繳交方式:<span>'+data.SubmitType+'</span></div>';
			var hw = {
				type:'homework',
				id:data.id,
				Caption:data.DisplayName,
				BeginDate:data.BeginDate,
				Content:content
			};
			genPanel(hw, i);
		}
		applyHomeworkFlag();
		
	}
	var processHWList = function(data){
		api_error_handle(data);
		for(var i=0; i<data.length; ++i){
			homework.push(data[i]);
		}
		++homeworkDone;
		if(homeworkDone == 2){
			renderHomework();
		}
	}
	client.homework.read('1').always(processHWList);//todo hw
	client.homework.read('3').always(processHWList);//due hw
}

var applyHomeworkFlag = function(){
	var unread = 0;
	var newDiv = $('<div>').addClass('label label-danger pull-right').text('New');
	var dueDiv = $('<span>').append($('<div>').addClass('label label-danger').text('遲交')).append(' ');
	var starDiv = $('<div>').addClass('label label-warning pull-right').append('<span class="glyphicon glyphicon-star"></span>');
	$('#tab-homework a .badge').remove();
	$('#homework .panel .label').remove();
	for(var i=0; i<homework.length; ++i){
		var entry = homework[i];
		var container = $('#homework-entry-'+i);
		var header = container.find('.panel-heading');
		var body = container.find('.panel-body');
		//Remove previous
		container.removeClass(function(i, css){
			return (css.match (/\b(panel-|filter_)\S+/g) || []).join(' ');
		});
		//Process Flag
		var star = false;
		if(entry.flag.indexOf('star') != -1){//Star
			star = true;
			header.prepend(starDiv.clone());
			container.data('star', 'true');
			body.find('button.no-star').attr('disabled', 'disabled');
		}else{
			container.removeData('star');
			body.find('button.no-star').attr('disabled', false);
		}
		if(entry.type == 3){//Due
			header.find('h2').prepend(dueDiv.clone());
			container.addClass('panel-danger');
			if(entry.flag.indexOf('read') > -1){
				container.appendTo('#read-homework');
				body.collapse();
			}else{
				++unread;
				container.appendTo('#due-homework');
			}
		}else if(entry.flag.indexOf('read') == -1){//Unread
			if(!star){
				++unread;
				header.prepend(newDiv.clone());
			}
			container.addClass('panel-primary');
			container.appendTo('#unread-homework');
		}else{
			container.addClass('panel-default');
			container.appendTo('#read-homework');
			body.collapse();
		}
	}
	if(unread > 0){
		var unread_div = $('<div>').prependTo($('#tab-homework a'));
		unread_div.addClass('badge pull-right');
		unread_div.text(unread);
	}
}

var setHomeworkFlag = function(){
	var container = $($(this).parent().data('container'));
	var id = $(this).parent().data('id');
	var flag = $(this).data('flag');
	var hw = homework[id];
	var hw_id = hw.HomeworkId;
	var client =  new $.RestClient('/API/');
	client.add('flag');
	var action = '';
	var data = {type:'homework', id:hw_id, flag:flag};
	if(flag == 'star' && container.data('star')){//unstar
		action = 'remove';
	}
	if (flag == 'star' && !container.data('star')){//star
		//remove all other flags
		hw.flag = new Array();
		(function(data){
			var flagdata = $.extend(true, {}, data);//Deep Copy
			flagdata.flag = null;
			client.flag.create('remove', flagdata).done(function(data){
				api_error_handle(data);
				flagdata.flag = 'star';
				client.flag.create(action, flagdata).done(api_error_handle);
			});
		})(data);
	}else{
		client.flag.create(action, data).done(api_error_handle);
	}
	if(action == 'remove'){
		container.removeData(flag);
		var index;
		while((index = hw.flag.indexOf(flag)) != -1){
			hw.flag.splice(index, 1);
		}
	}else{
		hw.flag.push(flag);
	}
	applyHomeworkFlag();
}

var initHomeworkComponent = function(){
	//Action Bar
	$('.hide .homework-action button').click(setHomeworkFlag);
	//Hide All Announcement
	$('#read-all-homework').click(function(){
		bootbox.confirm('全部標記成已讀?', function(result){
			if(result){
				var ids = new Array();
				$.each(homework, function(i, e){
					if(e.flag.indexOf('star')!=-1)return;
					ids.push(e.HomeworkId);
					e.flag.push('read');
				});
				var client =  new $.RestClient('/API/');
				client.add('flag');
				client.flag.create({type:'homework', id:ids.toString(), flag:'read'}).done(function(data){
					api_error_handle(data);
				});
				applyHomeworkFlag();
			}
		});
	});
}

/* General */
var addLoading = function(){
	$('.tab-pane').append(loadingDiv);
}

var loadingDiv = $('<div>').addClass('loading').css('text-align', 'center').text('截取資料中...').append(
	$('<img>').attr('src', '/img/loading.gif')
);

var genPanel = function(data, i){
	// data{type, id, Caption, BeginDate Content}
	var action_bar = $('.hide .'+data.type+'-action');
	var container = $('<div id="'+data.type+'-entry-'+i+'">').appendTo($('#'+data.type));
	var header = $('<div>').appendTo(container);
	var body = $('<div id="'+data.type+'-entry-body-'+i+'">').appendTo(container);
	container.addClass('panel');
	header.addClass('panel-heading');
	body.addClass('panel-body');
	var courseName = course_list[data.id].name;
	header.html('<div class="course-name">'+courseName+'</div>' + '<h2>'+data.Caption+' <small>'+data.BeginDate+'</small></h2>');
	header.click(function(){
		var obj = $($(this).data('target'));
		obj.collapse('toggle');
	});
	header.data('target', '#'+data.type+'-entry-body-'+i);
	body.html(data.Content);
	body.addClass('collapse in');
	var bar = action_bar.clone(true);
	body.prepend(bar);
	bar.data('id', i);
	bar.data('container', '#'+data.type+'-entry-'+i);
	bar.find('button').tooltip();
}

addLoading();
getCourseList();
getLatestAnnounce();
initAnnounceComponent();
getHomeworkList();
initHomeworkComponent();