<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function index()
	{
		$this->load->model('user');
		$this->load->helper('url');
		if(!$this->user->isLogin())redirect('/');
		$course = $this->user->getCourseList();
		$this->load->template('dashboard', array('course'=>$course));
	}
}
