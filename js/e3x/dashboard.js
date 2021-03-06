var course_list = new Array();
var getCourseList = function(){
  $('.course_list').each(function(i, e){
    course_list.push({
      id:$(e).data('id'),
      name:$(e).data('name')
    });
  });
  course_list.push({
    id:course_list.length,
    name:'E3X系統公告'
  });
}
var sort_important = function(a, b){
  var astar = a.flag.indexOf('star') != -1;
  var bstar = b.flag.indexOf('star') != -1;
  if(astar && !bstar)return -1;
  else if(bstar && !astar)return 1;
  return ((a.BeginDate < b.BeginDate) ? 1 : ((a.BeginDate > b.BeginDate) ? -1: 0 ));
}
/* E3X Message */
var e3xmessage = new Array();
var getE3XMessageList = function(){
  e3xmessage = new Array();
  var client = new $.RestClient('/API/');
  client.add('message');
  client.message.read().always(function(data){
    $('#e3xmessage .loading').remove();
    $('#tab-e3xmessage img').remove();
    $('#e3xmessage .panel').remove();
    data.sort(sort_important);
    for(var i=0; i<data.length; ++i){
      var e3xmsg = data[i];
      e3xmessage.push(data[i]);
      var msg = {
        type:'e3xmessage',
        id:course_list.length-1,
        aid:e3xmsg.id,
        Caption:e3xmsg.title,
        BeginDate:e3xmsg.time,
        Content:e3xmsg.content
      };
      genPanel(msg, i);
    }
    applyE3XMessageFlag();
  });
}

var applyE3XMessageFlag = function(){
  var unread = 0;
  var newDiv = $('<div>').addClass('label label-danger pull-right').text('New');
  var starDiv = $('<div>').addClass('label label-warning pull-right').append('<span class="glyphicon glyphicon-star"></span>');
  $('#tab-e3xmessage a .badge').remove();
  $('#e3xmessage .panel .label').remove();
  for(var i=0; i<e3xmessage.length; ++i){
    var entry = e3xmessage[i];
    var container = $('#e3xmessage-entry-'+i);
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
    if(entry.flag.indexOf('read') == -1){//Unread
      if(!star){
        ++unread;
        header.prepend(newDiv.clone());
      }
      container.addClass('panel-primary');
      container.appendTo('#unread-e3xmessage');
    }else{
      container.addClass('panel-default');
      container.appendTo('#read-e3xmessage');
      body.collapse();
    }
  }
  if(unread > 0){
    var unread_div = $('<div>').prependTo($('#tab-e3xmessage a'));
    unread_div.addClass('badge pull-right');
    unread_div.text(unread);
  }
}

var setE3XMessageFlag = function(){
  var container = $($(this).parent().data('container'));
  var id = $(this).parent().data('id');
  var flag = $(this).data('flag');
  var e3xmsg = e3xmessage[id];
  var e3xmsg_id = e3xmsg.id;
  var client =  new $.RestClient('/API/');
  client.add('flag');
  var action = '';
  var data = {type:'e3xmessage', id:e3xmsg_id, flag:flag};
  if(flag == 'star' && container.data('star')){//unstar
    action = 'remove';
  }
  if (flag == 'star' && !container.data('star')){//star
    //remove all other flags
    e3xmsg.flag = new Array();
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
    while((index = e3xmsg.flag.indexOf(flag)) != -1){
      e3xmsg.flag.splice(index, 1);
    }
  }else{
    e3xmsg.flag.push(flag);
  }
  applyE3XMessageFlag();
}

var initE3XMessageComponent = function(){
  //Action Bar
  $('.hide .e3xmessage-action button').click(setE3XMessageFlag);
  //Hide All Announcement
  $('#read-all-e3xmessage').click(function(){
    bootbox.confirm('封存全部訊息?', function(result){
      if(result){
        var ids = new Array();
        $.each(e3xmessage, function(i, e){
          if(e.flag.indexOf('star')!=-1)return;
          ids.push(e.id);
          e.flag.push('read');
        });
        var client =  new $.RestClient('/API/');
        client.add('flag');
        client.flag.create({type:'e3xmessage', id:ids.toString(), flag:'read'}).done(function(data){
          api_error_handle(data);
        });
        applyE3XMessageFlag();
      }
    });
  });
}

/* Announcement */
var announcement = new Array();

var getLatestAnnounce = function(){
  var client = new $.RestClient('/API/');
  client.add('announce');
  var req = client.announce.read('login').always(function(data){
    api_error_handle(data);
    data.sort(sort_important);
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
    var a = $(this);
    var tmp = a.text();
    a.text(a.data('toggle-text'));
    a.data('toggle-text', tmp);
    a.parent().toggleClass('active');
    applyAnnounceFilter();
  });
  //Action Bar
  $('.hide .announcement-action button').click(setAnnounceFlag);
  //Hide All Announcement
  $('#read-all-announcement').click(function(){
    bootbox.confirm('封存全部公告?', function(result){
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
/* Document */
var docs = new Array();

var getDocumentList = function(){
  var client = new $.RestClient('/API/');
  client.add('document');
  var req = client.document.read('id').always(function(data){
    api_error_handle(data);
    data.sort(sort_important);
    docs = new Array();
    $('#document .loading').remove();
    $('#tab-document img').remove();
    $('#document .panel').remove();
    var action_bar = $('.hide .document-action');
    for(var i=0; i<data.length; ++i){
      var doc = data[i];
      docs.push(doc);
      var downloadLink = createAttachmentLink(doc.DocumentId, 'document', doc.id);
      var content = $('<div>');
      if(doc.Summary)content.append('<h4>摘要</h4>'+doc.Summary);
      content.append(downloadLink);
      $.extend(doc, {
        type:'document',
        Caption:doc.DisplayName,
        Content:content,
        BeginDate:''//We dont need this
      });
      genPanel(doc, i);
    }
    applyDocumentFlag();
  });
}

var applyDocumentFlag = function(){
  var unread = 0;
  var newDiv = $('<div>').addClass('label label-danger pull-right').text('New');
  var hideDiv = $('<div>').addClass('label label-info pull-right').text('Hidden');
  var starDiv = $('<div>').addClass('label label-warning pull-right').append('<span class="glyphicon glyphicon-star"></span>');
  $('#tab-document a .badge').remove();
  $('#document .panel .label').remove();
  for(var i=0; i<docs.length; ++i){
    var doc = docs[i];
    var container = $('#document-entry-'+i);
    var header = container.find('.panel-heading');
    var body = container.find('.panel-body');
    //Remove previous
    container.removeClass(function(i, css){
      return (css.match (/\b(panel-|filter_)\S+/g) || []).join(' ');
    });
    //Process Flag
    var hidden = false, star = false;
    if(doc.flag.indexOf('hidden') != -1){//Hidden
      hidden = true;
      header.prepend(hideDiv.clone());
      container.addClass('panel-info');
      container.appendTo('#hidden-document');
    }
    if(doc.flag.indexOf('star') != -1){//Star
      star = true;
      header.prepend(starDiv.clone());
      container.data('star', 'true');
      body.find('button.no-star').attr('disabled', 'disabled');
    }else{
      container.removeData('star');
      body.find('button.no-star').attr('disabled', false);
    }
    
    if(doc.flag.indexOf('read') == -1){//Unread
      if(!hidden && !star){
        ++unread;
        header.prepend(newDiv.clone());
      }
      if(!hidden){
        container.addClass('panel-primary');
        container.appendTo('#unread-document');
      }
    }else if(!hidden){
      container.addClass('panel-default');
      container.appendTo('#read-document');
      body.collapse();
    }
    for(var j=0; j<doc.flag.length; ++j){
      container.addClass('filter_'+doc.flag[j]);
    }
  }
  if(unread > 0){
    var unread_div = $('<div>').prependTo($('#tab-document a'));
    unread_div.addClass('badge pull-right');
    unread_div.text(unread);
  }
}

var setDocumentFlag = function(){
  var container = $($(this).parent().data('container'));
  var id = $(this).parent().data('id');
  var flag = $(this).data('flag');
  var doc = docs[id];
  var doc_id = doc.DocumentId;
  var client =  new $.RestClient('/API/');
  client.add('flag');
  var action = '';
  var data = {type:'document', id:doc_id, flag:flag};
  if(flag == 'star' && container.data('star')){//unstar
    action = 'remove';
  }
  if (flag == 'star' && !container.data('star')){//star
    //remove all other flags
    doc.flag = new Array();
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
    while((index = doc.flag.indexOf(flag)) != -1){
      doc.flag.splice(index, 1);
    }
  }else{
    doc.flag.push(flag);
  }
  applyDocumentFlag();
  applyDocumentFilter();
}

var initDocumentComponent = function(){
  //Filter
  var filter = $('#document-filter li a');
  filter.click(function(){
    var a = $(this);
    var tmp = a.text();
    a.text(a.data('toggle-text'));
    a.data('toggle-text', tmp);
    a.parent().toggleClass('active');
    applyDocumentFilter();
  });
  //Action Bar
  $('.hide .document-action button').click(setDocumentFlag);
  //Hide All document
  $('#read-all-document').click(function(){
    bootbox.confirm('封存全部教材?', function(result){
      if(result){
        var ids = new Array();
        $.each(docs, function(i, e){
          if(e.flag.indexOf('star')!=-1)return;
          ids.push(e.DocumentId);
          e.flag.push('read');
        });
        var client =  new $.RestClient('/API/');
        client.add('flag');
        client.flag.create({type:'document', id:ids.toString(), flag:'read'}).done(function(data){
          api_error_handle(data);
        });
        applyDocumentFlag();
        applyDocumentFilter();
      }
    });
  });
}

var applyDocumentFilter = function(){
  var active_filter = new Array();
  var filter = $('#document-filter li a');
  $.each(filter, function(i, e){
    if($(e).parent().hasClass('active'))
      active_filter.push($(e).data('filter'));
  });
  if(active_filter.indexOf('hidden') != -1){
    $('.document-container').hide();
    $('#hidden-document').show();
  }else{
    $('.document-container').show();
    $('#hidden-document').hide();
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
      var downloadLink = createAttachmentLink(data.HomeworkId, 'homework', data.id);
      var content = $('<div class="homework-content"><div class="homework-due-date">截止日期:<span>'+data.EndDate+'</span></div>'+
        '<div class="homework-submit-type">繳交方式:<span>'+data.SubmitType+'</span></div></div>');
      content.append(downloadLink);
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
    bootbox.confirm('封存全部作業?', function(result){
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
  header.append('<div class="course-name">'+courseName+'</div>' + '<h2>'+data.Caption+' <small>'+data.BeginDate+'</small></h2>');
  header.click(function(){
    var obj = $($(this).data('target'));
    obj.collapse('toggle');
  });
  header.data('target', '#'+data.type+'-entry-body-'+i);
  body.append(data.Content);
  body.addClass('collapse in');
  var bar = action_bar.clone(true);
  body.prepend(bar);
  bar.data('id', i);
  bar.data('container', '#'+data.type+'-entry-'+i);
  bar.find('button').tooltip();
}
/* CourseTable */
var courseTable = new CourseTable();
var getCourseTable = function(){
  var client = new $.RestClient('/API/');
  client.add('course');
  client.course.read('time').done(function(data){
    courseTable.setData(data);
    courseTable.render('#coursetable');
    $('#coursetable .loading').remove();
    $('#tab-coursetable img').remove();
  });
}
$('#save-offline-coursetable').click(function(){
  bootbox.confirm('這將會使你的課表不用登入也可以查看，請確定不要在公用電腦使用本功能',function(result){
    if(!result)return;
    courseTable.save();
  });
  return false;
});
/* Remember Tab */
$('.nav-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e){
  $.cookie('last_view_tab', $(e.target).attr('href'), {expires:365});
});
if($.cookie('last_view_tab')){
  $('.nav-tabs a[href="'+$.cookie('last_view_tab')+'"]').tab('show');
}
/* Analyze */
$('.nav-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e){
  ga('send', 'event', 'click', 'dashboard-tab', $(e.target).attr('href'));
});
$.each(['announcement-action', 'document-action', 'homework-action'], function(i, e){
  $('.'+e+' button').on('click', function(){
    ga('send', 'event', 'click', e, $(this).data('flag'));
  });
});
$('[id^=read-all-]').on('click', function(){
  ga('send', 'event', 'click', $(this).attr('id').split('-')[2]+'-action', 'read-all');
});
addLoading();
getCourseList();
getLatestAnnounce();
initAnnounceComponent();
getDocumentList();
initDocumentComponent();
getHomeworkList();
initHomeworkComponent();
getE3XMessageList();
initE3XMessageComponent();
getCourseTable();