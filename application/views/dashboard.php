<div class="row" id="dashboard">
  <div class="col-md-8">
    <ul class="nav nav-tabs">
      <li id="tab-announcement" class="active"><a href="#announcement" data-toggle="tab">公告 <img src="/img/loading.gif"></a></li>
      <li id="tab-coursetable"><a href="#coursetable" data-toggle="tab">功課表 <img src="/img/loading.gif"></a></li>
      <li id="tab-document"><a href="#document" data-toggle="tab">教材 <img src="/img/loading.gif"></a></li>
      <li id="tab-assignment"><a href="#assignment" data-toggle="tab">作業 <img src="/img/loading.gif"></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="announcement">
      </div>
      <div class="tab-pane" id="coursetable">
      </div>
      <div class="tab-pane" id="document">
      </div>
      <div class="tab-pane" id="assignment">
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <ul class="list-group">
      <li class="list-group-item active">本學期課程</li>
      <? foreach($course as $key => $v){?>
        <a href="#" class="list-group-item course_list" data-id="<?=$key?>"><?=$v['CourseName'];?></a>
      <? }?>
    </ul>
  </div>
</div>
<script src="/js/e3x/dashboard.js"></script>