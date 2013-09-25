<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->model('user');
		if($this->user->isLogin()){
			$this->load->helper('url');
			redirect('/dashboard');
		}
		$this->load->template('welcome_message', array('description' => '全新設計E3系統，直覺、快速、好用'));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */