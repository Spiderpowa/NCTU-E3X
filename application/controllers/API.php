<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
class Api extends REST_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('user');
		$this->load->model('e3mobile');
		$this->load->model('flag');
		$this->user->sessionLogin();
	}
  
  function message_get(){
    if(!$this->user->isLogin())return $this->response(array('error'=>'Please Login First'));
    $this->load->model('message');
    $user = $this->user->getUser();
    $msg = $this->message->getMessageByUId($user['id']);
    $this->flag->getFlags('e3xmessage', $msg);
		$this->response($msg);
  }
	
	function announce_get($action, $type = 1){
		$anns = NULL;
		switch($action){
			case 'id':
				$ids = explode(',', $this->get('id'));
				$anns = $this->e3mobile->getAnnouncement($ids, $type);
				break;
			case 'login':
				$anns = $this->e3mobile->getAnnouncementLogin();
				break;
		}
		if($anns == NULL){
			$this->response(array('error'=>'Unknown Action:'.$action));
		}else if($anns['error']){
			$this->response($anns);
		}else{
			$this->flag->getFlags('announcement', $anns);
			$this->response($anns);
		}
	}
  
  function document_get($action, $type = 1){
    $course = $this->e3mobile->getAllCourse();
		if($this->get('id')){
			$ids = explode(',', $this->get('id'));
		}else{
			$ids = range(0, count($course)-1);
		}
		$docs = NULL;
		switch($action){
			case 'id':
				$docs = $this->e3mobile->getMaterialDocList($ids, $type);
				break;
			case 'summary':
				$docs = $this->e3mobile->getMaterialDocSummary($ids);
				break;
		}
		if($docs == NULL){
			$this->response(array('error'=>'Unknown Action:'.$action));
		}else if($docs['error']){
			$this->response($docs);
		}else{
			$this->flag->getFlags('document', $docs);
			$this->response($docs);
		}
	}
	
	function homework_get($type){
		$course = $this->e3mobile->getAllCourse();
		if($this->get('id')){
			$ids = explode(',', $this->get('id'));
		}else{
			$ids = range(0, count($course)-1);
		}
		$hw = array();
		foreach($ids as $id){
			$tmp = array_merge($hw, $this->e3mobile->getStuHomeworkList($id, $type));
			$hw = $tmp;
		}
		$this->flag->getFlags('homework', $hw);
		$this->response($hw);
	}
	
	function flag_post($action = 'add'){
		if(!$this->user->isLogin()){
			$this->response(array('error'=>'Please Login', 'relogin'=>1));
		}
		$this->load->model('flag');
		$ids = explode(',', $this->post('id'));
		$data = array();
		foreach($ids as $id){
			if($action == 'add')
				$this->flag->setFlag($this->post('type'), $id, $this->post('flag'));
			else if($action == 'remove')
				$this->flag->removeFlag($this->post('type'), $id, $this->post('flag'));
			else
				continue;
			$data[] = array($this->post('type'), $id, $this->post('flag'));
		}
		$this->response(array('success'=>$data));
	}
  
  function attachment_get($type = 'document'){
    $this->response($this->e3mobile->getAttachFileList($this->get('resid'), $type, $this->get('id')));
  }
	

	function user_post($action){
		switch($action){
			case 'register':
				$this->response($this->user->register($this->post('id'), $this->post('serial')));
			break;
			case 'login':
				$this->response($this->user->login($this->post('id'), $this->post('password')));
			break;
			default:
				$this->response(null, 400);
			break;	
		}
	}
  
  function FB_get($action){
    $this->load->helper('url');
    switch($action){
      case 'comment':
        $content = file_get_contents('https://graph.facebook.com/comments/?ids='.site_url($this->get('path')));
        //$content = file_get_contents('https://graph.facebook.com/comments/?ids=https://facebook.com/');
        $this->response(json_decode($content));
      break;
      default:
        $this->response(null, 400);
			break;
    }
  }
}
