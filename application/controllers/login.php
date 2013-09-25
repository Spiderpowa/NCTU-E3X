<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('user');
	}
	
	public function logout(){
		$this->user->logout();
		$this->load->success_msg('已登出');
		$this->load->helper('url');
		redirect('/');
	}
}
