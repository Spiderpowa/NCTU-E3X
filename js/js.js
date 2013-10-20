function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

var prefetch = function(file){
  var l = document.createElement('link');
  l.rel = 'prefetch';
  l.href = file;
  document.getElementsByTagName('head')[0].appendChild(l);
}