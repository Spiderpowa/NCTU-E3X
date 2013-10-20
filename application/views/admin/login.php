<div class="row">
  <div class="col-md-6">
    <h3>管理員登入</h3>
    <form action="/admin/dologin" method="post" class="form-horizontal" id="loginForm">
      <div class="form-group">
        <label class="col-md-2 control-label" for="username">帳號</label>
        <div class="col-md-10">
          <input class="form-control" name="username" type="text" id="username" placeholder="帳號" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-2 control-label" for="loginPassword">密碼</label>
        <div class="col-md-10">
          <input class="form-control" name="password" type="password" id="loginPassword" placeholder="密碼" />
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-10 col-md-offset-2">
          <button type="submit" class="btn btn-default">登入</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?=load_js('jquery.validate.min.js');?>
<?=load_js('e3x/admin/login.js');?>