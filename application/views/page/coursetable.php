<? if($offline){?><div id="coursetable"><h1 class="hidden-print">離線課表</h1><? }?>
<div class="row">
  <div class="col-xs-2">&nbsp;</div>
  <?
  $day_cht = array('', '一', '二', '三', '四', '五', '六', '日');
  $class_time = array('', 'M', 'N', 'A', 'B', 'C', 'D', 'X', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L');
  $class_time_info = array('', '6:00', '7:00', '8:00', '9:00', '10:10', '11:10', '12:20', '13:20', '14:20', '15:30', '16:30', '17:30', '18:30', '19:30', '20:30', '21:30');
  for($day = 0; $day < 8; ++$day){ ?>
  <div class="col-xs-1">
    <? for($class = 0; $class < count($class_time); ++$class){?>
      <? if(!$class){?><div class="header course_data"><?=$day_cht[$day];?>&nbsp;</div><? }else{?>
        <? if(!$day){?><div class="header course_data"><?=$class_time[$class];?>&nbsp;<div><?=$class_time_info[$class];?></div></div><? }else{?>
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