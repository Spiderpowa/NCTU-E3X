<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class E3Mobile extends CI_Model{
	private $_API = 'http://e3.nctu.edu.tw/mService/service.asmx/';
	private $_LoginTicket = NULL;
	private $_AccountID = NULL;
	private $_Course = array();
	function __construct(){
		parent::__construct();
	}
	
	function setLoginTicket($loginTicket, $accountId){
		$this->_AccountID = $accountId;
		$this->_LoginTicket = $loginTicket;
	}
	
	function setCourseList($course){
		$this->_Course = $course;
	}
	
	function login($username, $password){
		$data = array(
			'account' => $username,
			'password' => $password
		);			
		$data = $this->_post('Login', $data);
		if(!isset($data->LoginTicket)){
			return false;
		}else{
			$this->_AccountID = (string)$data->AccountId;
			$this->_LoginTicket = (string)$data->LoginTicket;
			return array(
				'username'=>$username,
				'name'=>(string)$data->Name,
				'AccountID'=>$this->_AccountID,
				'LoginTicket'=>$this->_LoginTicket
			);
		}
	}
	
	function getCourseList(){
		if(($test = $this->testTicket())!==true)return $test;
		$data = $this->_genData(array(
			'role'=>'stu'
		), true);
		$return = $this->_post('GetCourseList', $data);
		$courses = array();
		foreach($return as $course){
			$courses[] = $this->_fetchXml($course, array('CourseId', 'CourseName', 'TeacherName'));
		}
		return $courses;
	}
	
	function getAnnouncement($ids, $type = 1){
		if(($test = $this->testTicket())!==true)return $test;
		$anns = array();
		foreach($ids as $id){
			$data = $this->_genData(array(
				'courseId'=> $this->_Course[$id]['CourseId'],
				'bulType' => $type
			));
			$return = $this->_post('GetStuAnnouncementList', $data);
			foreach($return as $entry){
				$ann = $this->_fetchXml($entry, array('BulletinId', 'Caption', 'Content', 'BeginDate', 'EndDate'));
				$ann['id'] = $id;
				$anns[] = $ann;
			}
		}
		return $anns;
	}
	
	function getAnnouncementLogin(){
		if(($test = $this->testTicket())!==true)return $test;
		$anns = array();
		$data = $this->_genData(array(
			'studentId' => $this->_AccountID
		));
		$return = $this->_post('GetAnnouncementList_Login', $data);
		foreach($return as $entry){
			$ann = $this->_fetchXml($entry, array('BulletinId', 'Caption', 'Content', 'BeginDate', 'EndDate'));
			$ann['id'] = $this->getCourse($entry->CourseId);
			$anns[] = $ann;
		}
		return $anns;
	}
	
	function testTicket(){
		$data = $this->_genData(array(), true);
		$return = $this->_sendRequest($this->_API . 'GetPersonalData', $data);
		if(is_array($return) && $return['error'])
			return $return;
		return true;
	}
	
	function isLogin(){
		return ($this->_LoginTicket !== NULL && $this->_AccountID !== NULL);
	}

	
	function getCourse($course_id){
		foreach($this->_Course as $key => $course){
			if($course_id == $course['CourseId'])
				return $key;
		}
		return false;
	}
	
	private function _genData($data = array(), $needAccountID = false){
		$data['loginTicket'] = $this->_LoginTicket;
		if($needAccountID)
			$data['accountId'] = $this->_AccountID;
		return $data;
		
	}
	

	private function _post($url, $data){
		$url = $this->_API . $url;
		$xml = $this->_sendRequest($url, $data);
		$data = $this->_parseXml($xml);
		return $data;
	}
	
	private function _sendRequest($url, $data){
		$options = array(
			'http'=>array(
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context = stream_context_create($options);
		$result = @file_get_contents($url, false, $context);
		if($result === false){
			$result = array('error'=>'Login Timeout', 'relogin'=>1);
		}
		return $result;			
	}
	
	private function _parseXml($xml){
		if(is_array($xml))return $xml;
		$data = new SimpleXMLElement($xml);
		return $data;
	}
	
	private function _fetchXml($simpleXml, $key){
		$return = array();
		foreach($key as $v){
			$return[$v] = (string)$simpleXml->{$v};
		}
		return $return;
	}
}