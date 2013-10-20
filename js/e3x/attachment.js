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
          var link = $('<a>');
          link.text(data[i].DisplayFileName);
          link.attr('href', data[i].RealityFileName);
          link.addClass('document-attachment');
          content.append(link);
        }
        bootbox.alert(content.html());
      }
    });
    return false;
  });
  return downloadLink;
}