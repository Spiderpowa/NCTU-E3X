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