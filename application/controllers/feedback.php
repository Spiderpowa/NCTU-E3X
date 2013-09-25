<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback extends CI_Controller {
	public function index()
	{
		$this->load->library('recaptcha');
		$this->load->template('feedback', array('recaptcha'=>$this->recaptcha->recaptcha_get_html()));
	}
	
	public function submit()
	{
			$this->load->library('recaptcha');
			if($this->input->post('name') && $this->input->post('contact') && $this->input->post('feedback')){
				$this->recaptcha->recaptcha_check_answer(
					$_SERVER['REMOTE_ADDR'],
					$this->input->post('recaptcha_challenge_field'),
					$this->input->post('recaptcha_response_field'));
				if(!$this->recaptcha->getIsValid()){
					$this->load->error_msg('驗證碼錯誤');
				}else{
					$data = array(
						'name'=>$this->input->post('name'),
						'contact'=>$this->input->post('contact'),
						'feedback'=>$this->input->post('feedback')
					);
					$this->db->insert('feedback', $data);
					$this->load->success_msg('感謝您的填寫');
				}
			}else{
				$this->load->error_msg('全部攔位都要填寫');
			}		
			return $this->index();
	}
}
