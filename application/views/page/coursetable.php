<? if($offline){?><div id="coursetable"><h1>離線課表</h1><? }?>
<div class="row">
  <div class="col-xs-2">&nbsp;</div>
  <?
  $day_cht = array('', '一', '二', '三', '四', '五', '六', '日');
  $class_time = array('', 'M', 'N', 'A', 'B', 'C', 'D', 'X', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L');
  for($day = 0; $day < 8; ++$day){ ?>
  <div class="col-xs-1">
    <? for($class = 0; $class < count($class_time); ++$class){?>
      <? if(!$class){?><div class="header course_data""><?=$day_cht[$day];?>&nbsp;</div><? }else{?>
        <? if(!$day){?><div class="header course_data""><?=$class_time[$class];?>&nbsp;</div><? }else{?>
          <div id="course_<?=$day;?><?=$class_time[$class];?>" class="course_data">&nbsp;</div>
        <? }?>
      <? }?>
    <? }?>
  </div>
  <? }?>
</div>
<div id="other-course">
</div>
<? if($offline){?></div><? }?>
<?=load_js('e3x/coursetable.js');?>
<? if($offline){?>
<?=load_js('e3x/offlinecoursetable.js');?>
<? }?>