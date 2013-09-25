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
        <ul class="nav nav-pills pull-left" id="announcement-filter">
          <li><a href="#" data-filter="hidden">顯示隱藏</a></li>
        </ul>
        <button class="btn btn-primary pull-right" type="button" id="read-all-announcement">全部標記已讀</button>
        <div class="hide">
          <div class="btn-group announcement-action pull-right">
            <button type="button" class="btn btn-default" data-flag="star" title="重要公告，不能標記成已讀或隱藏" data-placement="bottom"><span class="glyphicon glyphicon-star"></span> 重要</button>
            <button type="button" class="btn btn-default no-star" data-flag="read" title="已經看過了，放置於下方" data-placement="bottom"><span class="glyphicon glyphicon-check"></span> 已讀</button>
            <button type="button" class="btn btn-default no-star" data-flag="hidden" title="不是很重要的公告，隱藏起來" data-placement="bottom"><span class="glyphicon glyphicon-eye-close"></span> 隱藏</button>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="announcement-container" id="unread-announcement">
          <h4>重要公告</h4>
        </div>
        <div class="announcement-container" id="read-announcement">
          <h4>已讀公告</h4>
        </div>
        <div class="announcement-container" id="hidden-announcement">
          <h4>隱藏公告</h4>
        </div>
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
<?=load_js('e3x/dashboard.js');?>
