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
		}else{
			$this->flag->getFlags('announcement', $anns);
			$this->response($anns);
		}
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
}
