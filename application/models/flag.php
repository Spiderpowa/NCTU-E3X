<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flag extends CI_Model{
	private $_uid;
	function __construct(){
		parent::__construct();
		$this->load->model('user');
		$user = $this->user->getUser();
		$this->_uid = $user['id'];
	}
	
	function setFlag($type, $entry_id, $flag){
		$data = array(
			'uid'=>$this->_uid,
			'type'=>$type,
			'entry_id'=>$entry_id,
			'flag'=>$flag
		);
		$this->db->insert('flag', $data);
	}
	
	function removeFlag($type, $entry_id, $flag = NULL){
		$data = array(
			'uid'=>$this->_uid,
			'type'=>$type,
			'entry_id'=>$entry_id,
			'flag'=>$flag
		);
		if($flag === NULL){//remove all
			unset($data['flag']);
		}
		$this->db->delete('flag', $data);
	}
	
	function getFlag($type, $entry_id, $flag = NULL){
		$data = array(
			'uid'=>$this->_uid,
			'type'=>$type,
			'entry_id'=>$entry_id,
			'flag'=>$flag
		);
		if($flag === NULL){//remove all
			unset($data['flag']);
		}
		$this->db->select('flag')->from('flag')->where($data);
		$query = $this->db->get();
		$entry_flag = array();
		foreach($query->result() as $row){
			$entry_flag[] = $row->flag;
		}
		return $entry_flag;
	}
	
	function getFlags($type, &$entries, $flag = NULL){
		foreach($entries as &$entry){
			$entry_id_index = $this->_findEntryId($type, $entry);
			$entry_id = $entry[$entry_id_index];
			$entry['flag'] = $this->getFlag($type, $entry_id, $flag);
		}
	}
	
	private function _findEntryId($type, $entry){
		$data = array(
			'announcement'=>'BulletinId'
		);
		return $data[$type];
	}
}