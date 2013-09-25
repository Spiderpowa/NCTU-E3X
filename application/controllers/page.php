<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {
	
	public function tos()
	{
		$this->load->template('page/tos');
	}
	
	public function disclaimer()
	{
		$this->load->template('page/disclaimer');
	}
}
