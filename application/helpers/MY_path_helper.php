<?
function load_css($file){
  $GLOBALS['__load_css_file__'][] = $file;
	return '';
}

function load_js($file){
  $GLOBALS['__load_js_file__'][] = $file;
  return '';
}

function render_css(){
  $return = '';
  foreach($GLOBALS['__load_css_file__'] as $file){
    $return .= '<link href="/css/'.$file.'?v='.filemtime(FCPATH.'/css/'.$file).'" type="text/css" rel="stylesheet" />
';
  }
  return $return;
}

function render_js(){
  $return = '';
  foreach($GLOBALS['__load_js_file__'] as $file){
    $return .= '<script src="/js/'.$file.'?v='.filemtime(FCPATH.'/js/'.$file).'"></script>
';
  }
  return $return;
}