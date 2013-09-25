var api_error_handle = function(data){
	if(data.error === undefined || data.error === null)return;// No Error
	if(data.relogin){
		location.href = encodeURI('/?login_error=閒置過久,請重新登入&noredirect=1');
	}
}