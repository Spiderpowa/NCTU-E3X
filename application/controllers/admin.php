<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('user', '', true);
		$this->load->helper('url');
	}
	
	public function index()
	{
		$this->_checkAdmin();
		$this->load->template('admin/welcome');
	}
	
	public function login()
	{
		$this->load->template('admin/login');
	}
	
	public function dologin()
	{
		$return = $this->user->localLogin($this->input->post('username'), $this->input->post('password'));
		if($return === true){
			redirect('/admin/');
			return $this->index();
		}else{
			$this->load->error_msg($return['error']);
			return $this->login();
		}
	}
  
  public function changepassword()
  {
    $this->_checkAdmin();
    if(strlen($this->input->post('newpassword')) < 5){
      $this->load->error_msg('Password Too Short');
      return $this->index();
    }else if($this->input->post('newpassword') != $this->input->post('confirmpassword')){
      $this->load->error_msg('Password Not Match');
      return $this->index();
    }
    $user = $this->user->getUser();
    if($this->user->changePassword($user['id'], $this->input->post('newpassword'), 1)){
      $this->load->success_msg('Password Changed');
    }else{
      $this->load->error_msg('Password Change Fail');
    }
    return $this->index();
  }
  
  public function serial($action = NULL)
  {
    $this->_checkAdmin();
    if($action == NULL){
      $this->load->template('admin/serial');
    }else if($action = 'create'){
      $this->load->model('serial');
      $serial = array();
      for($i = 0; $i < $this->input->post('number'); ++$i){
        $serial[$i] = $this->serial->generate($this->input->post('prefix'), $this->input->post('segment'), $this->input->post('length'));
        if($serial[$i] === false)--$i;
      }
      $this->load->template('admin/serial_create', array('serial'=>$serial));
    }
  }
  
	public function feedback($action = NULL, $id = 0, $value = 0)
	{
    $this->_checkAdmin();
		if($action == NULL){			
			$this->db->select('*')->from('feedback')->order_by('time', 'desc');
			$this->db->where('hide', $this->input->get('hidden')?'1':'0');
			$query = $this->db->get();			
			$this->load->template('admin/feedback', array('feedback'=>$query->result()));
		}else if($action == 'hide'){
			$this->db->where('id', $id)->limit(1);
			$this->db->update('feedback', array('hide'=>$value));
			redirect('/admin/feedback/?hidden='.(!$value));
		}
	}
	
	private function _isAdmin($user){
		return $user['admin'] == 1;
	}
	
	private function _checkAdmin(){
		if(!$this->user->isLogin()){
			redirect('/admin/login');
		}
		if(!$this->_isAdmin($this->user->getUser())){
			redirect('/admin/login');
		}
	}
	
	
}
