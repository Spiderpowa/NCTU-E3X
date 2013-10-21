var getAttachment = function(resid, type, courseid, callback){
  var client = new $.RestClient('/API/');
	client.add('attachment');
  var data = {
    resid: resid,
    type: type,
    id: courseid
  };
  (function(cb){
	  var req = client.attachment.read(data).always(function(data){
      api_error_handle(data);
      cb(data);
    });
  }(callback));
};

var createAttachmentLink = function(resId, type, courseId){
  var downloadLink = $('<a>');
  downloadLink.attr('href', '#');
  downloadLink.data('resId', resId);
  downloadLink.data('type', type);
  downloadLink.data('courseId', courseId);
  downloadLink.text('下載附件');
  downloadLink.click(function(){
    getAttachment($(this).data('resId'), $(this).data('type'), $(this).data('courseId'), function(data){
      if(0 && data.length == 1)
        location.href = data[0].RealityFileName;
      else{
        var content = $('<div>');
        content.append('<h2>附件列表</h2>');
        if(!data.length){
          content.append('無附件');
        }
        for(var i=0; i<data.length; ++i){
          var div = $('<div>').appendTo(content);
          div.addClass('document-attachment');
          var dnlink = $('<a>').appendTo(div);
          dnlink.addClass('btn btn-info');
          dnlink.attr('href', 'http://e3.nctu.edu.tw/NCTU_EASY_E3P/LMS2/common_get_content_media_attach_file.ashx?AttachMediaId='+data[i].AttachMediaId+'&CourseId='+data[i].courseId);
          dnlink.text('下載');
          var link = $('<a>').appendTo(div);
          link.text(data[i].DisplayFileName);
          link.attr('href', data[i].RealityFileName);
        }
        bootbox.alert(content.html());
      }
    });
    return false;
  });
  return downloadLink;
}