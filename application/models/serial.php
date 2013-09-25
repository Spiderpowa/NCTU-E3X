<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Serial extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function check($serial){
		$this->db->select('enable')->from('serial')->where('serial', $serial);
		$query = $this->db->get();
		if(!$query->num_rows())
			return NULL;
		$row = $query->row();
		if($row->enable == 0)
			return false;
		return true;
	}
	
	function active($serial, $username = ''){
		$this->db->where('serial', $serial);
		$this->db->update('serial', array('enable'=>0, 'usedby'=>$username));
	}
	
	function generate($prefix = 'A0T0'){
		$serial_array = array($this->_gen_serial(),$this->_gen_serial(),$this->_gen_serial());
		if(strlen($prefix))array_unshift($prefix);
		$serial = implode('-', $serial_array);
		return $this->create($serial);
	}
	
	function create($serial){
		return $this->db->insert('serial', array('enable'=>1, 'serial'=>$serial));
	}
	
	private function _gen_serial($len = 5){
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $len);
	}
}