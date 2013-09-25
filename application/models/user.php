<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function register($username, $serial){
		/*
		if(strlen($username) < 3 || strlen($password) < 5){
			return array('error'=>'Username or Password too short');
		}
		if($this->db->insert('user', array('username'=>$username, 'password'=>$this->_hash_password($password)))){
			return array('id'=>$this->db->insert_id());
		}
		return array('error'=>'Username Exists');
		*/
		$this->load->model('serial', '', true);
		$result = $this->serial->check($serial);
		if($result === NULL)
			return array('error'=>'啟動碼不存在');
		elseif($result === false)
			return array('error'=>'啟動碼已被使用');
		if($this->db->insert('user', array('username'=>$username))){
			//$this->serial->active($serial, $username);
			return array('username'=>$username);
		}else
			return array('error'=>'你的帳號已經開通了喔');
	}
	
	function login($username, $password){
		if( ($id = $this->isRegister($username)) === FALSE){
			return array('error'=>'此帳號尚未開通, 請至首頁開通後才可使用');
		}
		$this->load->model('e3mobile');
		$return = $this->e3mobile->login($username, $password);
		$return['id'] = $id;
		if($return === FALSE){
			return array('error'=>'登入失敗');
		}else{
			//Get Courst List
			$this->session->set_userdata('user', $return);
			$this->session->set_userdata('course', $this->e3mobile->getCourseList());
			return array('username'=>$username);
		}
	}
	
	function isRegister($username){
		$this->db->select('id')->from('user')->where('username', $username);
		$query = $this->db->get();
		if(!$query->num_rows())return false;
		return $query->row()->id;
	}
	
	function sessionLogin(){
		$this->load->model('e3mobile');
		if($this->isLogin()){
			$user = $this->getUser();
			$this->e3mobile->setLoginTicket($user['LoginTicket'], $user['AccountID']);
			$this->e3mobile->setCourseList($this->getCourseList());
		}
	}
	
	function getCourseList(){
		if(!$this->isLogin())return array();
		return $this->session->userdata('course');
	}

	function getUser(){
		return $this->session->userdata('user');
	}
	
	function isLogin(){
		return $this->session->userdata('user')!==false;
	}
	
	function logout(){
		$this->session->unset_userdata('user');
	}
	
	private function _test_password($password, $hash_password){
		$salt = strstr($hash_password, '$', true);
		return $this->_hash_password($password, $salt) == $hash_password;
	}
	
	private function _hash_password($password, $salt = ''){
		if($salt == '')$salt = $this->_gen_salt();
		return $salt.'$'.md5($salt.$password);
	}
	
	private function _gen_salt($len = 5){
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $len);
	}
}