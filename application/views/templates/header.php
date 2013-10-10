<!DOCTYPE html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta property="og:image" content="https://graph.facebook.com/1403815759848058/picture?width=200&height=200" />
<meta property="fb:app_id" content="572310832804666"/>
<meta property="fb:admins" content="1449616088"/>
<title>E3X<? if(isset($title)){echo ' - '.html_escape($title);}?></title>
<? if(isset($description)){?>
<meta name="description" content="<?=html_escape($description);?>" />
<? }?>
<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/ui-lightness/jquery-ui.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css" rel="stylesheet">
<?=load_css('style.css');?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/js/jquery.rest.min.js?v=0.0.7"></script>
<script src="/js/jquery.cookie.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="/js/holder.js"></script>
<script src="/js/bootbox.min.js?v=4.0.0"></script>
<?=load_js('js.js');?>
<?=load_js('e3x/error_handle.js');?>
<script>
var bootstrapButton = $.fn.button.noConflict();
$.fn.bootstrapBtn = bootstrapButton;
bootbox.setDefaults({locale:'zh_TW'});
</script>
</head>
<body>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '572310832804666',                        // App ID from the app dashboard
      channelUrl : 'page/channel', // Channel file for x-domain comms
      status     : true,                                 // Check Facebook Login status
      xfbml      : true                                  // Look for social plugins on the page
    });

    // Additional initialization code such as adding Event Listeners goes here
  };

  // Load the SDK asynchronously
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/zh_TW/all.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<div id="header" class="hidden-print">
  <nav class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">E3X <span class="label label-info">Alpha</span></a>
      </div>
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
          <li class="divider-vertical"></li>
          <li><a href="/">首頁</a></li>
          <li><a href="https://www.facebook.com/NCTU.E3X" target="_blank">FB粉絲團</a></li>
          <li><a href="/page/coursetable/">離線課表</a></li>
          <? if($session_user){?>
          <li><a href="#">Hi <?=$session_user['name'];?></a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">課程列表 <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <? foreach($session_course as $key=>$course){?>
              <li><a href="/course/dashboard/<?=$key;?>"><?=$course['CourseName'];?></a></li>
              <? }?>
            </ul>
          </li>
          <li><a href="/login/logout" id="logout">登出</a></li>
          <? }?>
        </ul>
        <ul class="nav navbar-nav pull-right">
          <li><a href="/feedback">意見回饋</a></li>
        </ul>
      </div>
    </div>
  </nav>
</div>
<div id="body" class="container">
<div id="message">
<!--
<div class="alert fade in"><a class="close" data-dismiss="alert" href="#">&times;</a><h4>Info</h4>很抱歉 網站為修中 請稍後再試</div>
-->
<!--Message-->
<? foreach($message['error'] as $str){?>
<div class="alert alert-danger fade in"><a class="close" data-dismiss="alert" href="#">&times;</a><h4>Error</h4><?=html_escape($str);?></div>
<? }?>
<? foreach($message['info'] as $str){?>
<div class="alert alert-info fade in"><a class="close" data-dismiss="alert" href="#">&times;</a><h4>Info</h4><?=html_escape($str);?></div>
<? }?>
<? foreach($message['success'] as $str){?>
<div class="alert alert-success fade in"><a class="close" data-dismiss="alert" href="#">&times;</a><h4>Success</h4><?=html_escape($str);?></div>
<? }?>
</div>
