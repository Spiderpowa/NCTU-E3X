<h2>意見回應</h2>
<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <h3>寫信給我們</h3>
    <form action="/feedback/submit" method="post" class="form-horizontal" id="feedbackForm">
      <div class="form-group">
        <label class="col-md-2 control-label" for="inputName">姓名</label>
        <div class="col-md-10">
          <input class="form-control" name="name" type="text" id="inputName" placeholder="該如何稱呼您?" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-2 control-label" for="inputContact">連絡方式</label>
        <div class="col-md-10">
          <input class="form-control" name="contact" type="text" id="inputContact" placeholder="email, FB 或 手機等" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-2 control-label" for="inputFeedback">意見反應</label>
        <div class="col-md-10">
          <textarea class="form-control" name="feedback" rows="15" id="inputFeedback" placeholder="請留下您寶貴的意見"></textarea>
        </div>
      </div>
  
      <div class="form-group">
        <label class="col-md-2 control-label">驗證碼</label>
        <div class="col-md-10">
          <?=$recaptcha;?>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-10 col-md-offset-2">
          <button type="submit" class="btn btn-default">送出</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?=load_js('jquery.validate.min.js');?>
<?=load_js('e3x/feedback.js');?>