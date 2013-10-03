<div class="row" id="course_dashboard">
  <div class="col-md-4">
    <div class="list-group">
      <div class="list-group-item active"><?=$course['CourseName'];?></div>
      <a href="/course/announce/<?=$course['CourseKey'];?>" class="list-group-item course_list">公告</a>
      <a href="/course/document/<?=$course['CourseKey'];?>" class="list-group-item course_list">教材</a>
      <a href="/course/homework/<?=$course['CourseKey'];?>" class="list-group-item course_list">作業</a>
    </div>
  </div>
  <div class="col-md-8">
    <div id="announcement">
      <h2>公告</h2>
      <ul>
        <? foreach($announcement as $entry){?>
        <li><?=$entry['Caption'];?></li>
        <? }?>
      </ul>
    </div>
    
    <div id="homework">
      <h2>作業</h2>
      <ul>
        <? foreach($homework as $entry){?>
        <li><?=$entry['DisplayName'];?></li>
        <? }?>
      </ul>
    </div>
    
    <div id="document">
      <h2>教材</h2>
      <ul>
        <? foreach($document as $entry){?>
        <li><?=$entry['DisplayName'];?></li>
        <? }?>
      </ul>
    </div>
    <div class="fb-comments" data-href="<?=site_url('/course/comment/'.$course['CourseId']);?>" data-colorscheme="light" data-width="470"></div>
  </div>
</div>
<?=load_js('e3x/dashboard.js');?>
