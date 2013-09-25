<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader {
	var $msg;
	function __construct(){
		parent::__construct();
		$this->msg = array('error'=>array(), 'info'=>array(), 'success'=>array());
	}
	public function template($template_name, $vars = array(), $return = FALSE)
	{
		$vars['message'] = $this->msg;
		$vars['session_user'] = get_instance()->session->userdata('user');
		$vars['session_course'] = get_instance()->session->userdata('course');
		get_instance()->load->helper('path');
		$content  = $this->view('templates/header', $vars, $return);
		$content .= $this->view($template_name, $vars, $return);
		$content .= $this->view('templates/footer', $vars, $return);
		
		if($return === true)
		{
			return $content;
		}
	}
	public function error_msg($msg){
		$this->msg['error'][] = $msg;
	}
	public function info_msg($msg){
		$this->msg['info'][] = $msg;
	}
	public function success_msg($msg){
		$this->msg['success'][] = $msg;
	}
}