<div class="row" id="dashboard">
  <div class="col-md-8">
    <ul class="nav nav-tabs">
      <li id="tab-announcement" class="active"><a href="#announcement" data-toggle="tab">公告 <img src="/img/loading.gif"></a></li>
      <li id="tab-coursetable"><a href="#coursetable" data-toggle="tab">功課表 <img src="/img/loading.gif"></a></li>
      <li id="tab-document"><a href="#document" data-toggle="tab">教材 <img src="/img/loading.gif"></a></li>
      <li id="tab-homework"><a href="#homework" data-toggle="tab">作業 <img src="/img/loading.gif"></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="announcement">
        <ul class="nav nav-pills pull-left" id="announcement-filter">
          <li><a href="#" data-filter="hidden">顯示隱藏</a></li>
        </ul>
        <button class="btn btn-primary pull-right" type="button" id="read-all-announcement">全部封存</button>
        <div class="hide">
          <div class="btn-group announcement-action pull-right">
            <button type="button" class="btn btn-default star" data-flag="star" title="重要公告" data-placement="left"><span class="glyphicon glyphicon-star"></span> 重要</button>
            <button type="button" class="btn btn-default no-star" data-flag="read" title="封存放置於下方" data-placement="left"><span class="glyphicon glyphicon-save"></span> 封存</button>
            <button type="button" class="btn btn-default no-star" data-flag="hidden" title="不是很重要的公告，隱藏起來" data-placement="left"><span class="glyphicon glyphicon-eye-close"></span> 隱藏</button>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="announcement-container" id="unread-announcement">
          <h4>重要公告</h4>
        </div>
        <div class="announcement-container" id="read-announcement">
          <h4>封存公告</h4>
        </div>
        <div class="announcement-container" id="hidden-announcement">
          <h4>隱藏公告</h4>
        </div>
      </div>
      <div class="tab-pane" id="coursetable">
      </div>
      <div class="tab-pane" id="document">
        <ul class="nav nav-pills pull-left" id="document-filter">
          <li><a href="#" data-filter="hidden">顯示隱藏</a></li>
        </ul>
        <button class="btn btn-primary pull-right" type="button" id="read-all-document">全部封存</button>
        <div class="hide">
          <div class="btn-group document-action pull-right">
            <button type="button" class="btn btn-default star" data-flag="star" title="重要教材" data-placement="left"><span class="glyphicon glyphicon-star"></span> 重要</button>
            <button type="button" class="btn btn-default no-star" data-flag="read" title="封存放置於下方" data-placement="left"><span class="glyphicon glyphicon-save"></span> 封存</button>
            <button type="button" class="btn btn-default no-star" data-flag="hidden" title="不是很重要的教材，隱藏起來" data-placement="left"><span class="glyphicon glyphicon-eye-close"></span> 隱藏</button>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="document-container" id="unread-document">
          <h4>重要教材</h4>
        </div>
        <div class="document-container" id="read-document">
          <h4>封存教材</h4>
        </div>
        <div class="document-container" id="hidden-document">
          <h4>隱藏教材</h4>
        </div>
      </div>
      <div class="tab-pane" id="homework">
       <button class="btn btn-primary pull-right" type="button" id="read-all-homework">全部封存</button>
        <div class="hide">
          <div class="btn-group homework-action pull-right">
            <button type="button" class="btn btn-default star" data-flag="star" title="重要作業" data-placement="left"><span class="glyphicon glyphicon-star"></span> 重要</button>
            <button type="button" class="btn btn-default no-star" data-flag="read" title="封存放置於下方" data-placement="left"><span class="glyphicon glyphicon-save"></span> 封存</button>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="homeworkt-container" id="due-homework">
          <h4>逾期作業</h4>
        </div>
        <div class="homeworkt-container" id="unread-homework">
          <h4>重要作業</h4>
        </div>
        <div class="homework-container" id="read-homework">
          <h4>封存作業</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="list-group">
      <div class="list-group-item active">本學期課程</div>
      <? foreach($course as $key => $v){?>
        <a href="/course/dashboard/<?=$key;?>" id="course-<?=$key;?>" class="list-group-item course_list" data-name="<?=$v['CourseName'];?>" data-id="<?=$key?>"><?=$v['CourseName'];?></a>
      <? }?>
    </div>
  </div>
</div>
<?=load_js('e3x/dashboard.js');?>
