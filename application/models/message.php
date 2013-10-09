<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Model{
	private $select_message = 'id, title, content, time';
	function __construct(){
		parent::__construct();
	}
	
	public function getMessage($id){
    $this->db->select($this->select_message)->from('message')->where('id', $id);
    $query = $this->db->get();
    return $query->row();
  }
  
  public function getMessageByUId($uid){
    $this->db->select($this->select_message)->from('message')->where('uid', $uid);
    $query = $this->db->get();
    $msgs = array();
    foreach($query->result_array() as $row){
      $msgs[] = $row;
    }
    return $msgs;
  }
}