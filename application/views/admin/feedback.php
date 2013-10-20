<h4>意見反應<? if($this->input->get('hidden')){?>(隱藏)<? }?></h4>
<a href="<?=$this->input->get('hidden')?'?':'?hidden=1';?>">切換顯示</a>
<table class="table table-striped">
<tr><th>時間</th><th>姓名</th><th>連絡方式</th><th>意見</th></tr>
<? foreach($feedback as $e){?>
<tr>
  <td><a href="/admin/feedback/hide/<?=$e->id;?>/<?=$this->input->get('hidden')?'0':'1';?>"><i class="glyphicon glyphicon-eye-<?=$this->input->get('hidden')?'open':'close';?>"></i></a><?=$e->time;?></td>
  <td><?=$e->name;?></td>
  <td><?=$e->contact;?></td>
  <td><?=$e->feedback;?></td></tr>
<? }?>
</table>
