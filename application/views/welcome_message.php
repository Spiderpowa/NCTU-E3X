<script src="/js/jquery.validate.min.js"></script>
<div class="jumbotron full-width" id="welcome">
  <div class="full-width-bg">
    <div class="full-width-content">
      <h1>NCTU E3X</h1>
      <p class="title">全新設計E3系統，直覺、快速、好用</p>
      <p class="buttons">
        <a class="btn btn-primary btn-lg" href="#loginModal" data-toggle="modal" role="button">馬上登入 &raquo;</a>
        <a class="btn btn-success btn-lg" href="#registerModal" data-toggle="modal" role="button">開通帳號 &raquo;</a>
      </p>
    </div>
  </div>
</div>

<div id="welcome-comment" class="panel-group">
  <div class="panel">
    <div id="fb-comment-show" class="panel-collapse collapse in">
      <div class="panel-body">
        <div class="pull-right"><a class="accordion-toggle" data-toggle="collapse" data-parent="#welcome-comment" href="#fb-leave-comment">留下回應</a></div> 
        <div class="row" id="fb-comment-message">
          <div class="col-md-4" id="fb-comment-message-head">
            <img class="img-circle" />
          </div>
          <div class="col-md-6" id="fb-comment-message-text">
            <blockquote>
              <p>正在讀取Facebook回應...</p>
              <small class="pull-right"></small>
            </blockquote>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="panel">
    <div id="fb-leave-comment" class="panel-collapse collapse">
      <div class="panel-body">  
        <div class="pull-right"><a class="accordion-toggle" data-toggle="collapse" data-parent="#welcome-comment" href="#fb-comment-show">看大家的回應</a></div>
        <div class="fb-comments" data-href="<?=site_url();?>" data-colorscheme="light" data-num-posts="0" data-width="650"></div>
      </div>
    </div>
  </div>
</div>

    

<!-- Login Modal -->
<form id="loginForm" method="post" role="form">
  <div id="loginModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">x</button>
          <h3 id="loginModalLabel">登入</h3>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="alert alert-success"></div>
            <div class="alert alert-info"></div>
            <div class="alert alert-danger"></div>
            <label for="loginId">學號</label>
            <input class="form-control" type="text" name="id" id="loginId" placeholder="請輸入學號" />
          </div>
          <div class="form-group">
            <label for="loginPassword">密碼</label>
            <input class="form-control" type="password" name="password" id="loginPassword" placeholder="請輸入e3密碼" />
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" data-dismiss="modal" type="button">取消</button>
          <button class="btn btn-primary">登入</button>
        </div>
      </div>
    </div>
  </div>
</form>

<!-- Register Modal -->
<form id="registerForm" method="post" role="form">
  <div id="registerModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">x</button>
          <h3 id="registerModalLabel">開通帳號</h3>
        </div>
        <div class="modal-body">
          <div class="alert alert-success"></div>
          <div class="alert alert-info"></div>
          <div class="alert alert-danger"></div>
          <div class="form-group">
            <label for="registerId">學號</label>
            <input class="form-control" type="text" name="id" id="registerId" placeholder="請輸入學號" />
          </div>
          <div class="form-group">
            <label for="registerSerial">啟動碼</label>
            <input class="form-control" type="text" name="serial" id="registerSerial" placeholder="沒有啟動?馬上加入粉絲團!" />
          </div>
        </div>
        <div class="modal-footer">
          <div class="alert alert-info">當你按下開通後，即為同意 <strong><a href="/page/tos" target="_blank">服務條款</a></strong> 以及 <strong><a href="/page/disclaimer" target="_blank">免責聲明</a></strong></div>
          <button class="btn btn-default" data-dismiss="modal" type="button">取消</button>
          <button class="btn btn-primary" type="submit">開通</button>
        </div>
      </div>
    </div>
  </div>
</form>

<div id="msgModal" data-keyboard="false" data-backdrop="static" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="msgModalLabel" class="modal-title"></h3>
      </div>
      <div class="modal-body">
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<a href="https://github.com/Spiderpowa/NCTU-E3X"><img style="position: absolute; top: 0; right: 0; border: 0; z-index:9999" src="https://s3.amazonaws.com/github/ribbons/forkme_right_orange_ff7600.png" alt="Fork me on GitHub"></a>
<?=load_js('e3x/index.js');?>
