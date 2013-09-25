<?
function load_css($file){
	return '<link href="/css/'.$file.'?v='.filemtime(FCPATH.'/css/'.$file).'" type="text/css" rel="stylesheet" />
';
}

function load_js($file){
	return '<script src="/js/'.$file.'?v='.filemtime(FCPATH.'/js/'.$file).'"></script>
';
}