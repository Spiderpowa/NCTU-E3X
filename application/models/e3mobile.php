<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class E3Mobile extends CI_Model{
	private $_API = 'http://e3.nctu.edu.tw/mService/service.asmx/';
  private $_MetaType = array(
    'document' => 10
  );
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
      $data =  $this->_fetchXml($course, array('CourseId', 'CourseName', 'TeacherName'));
      $data['CourseKey'] = count($courses);
			$courses[] =$data;
		}
		return $courses;
	}
	
	function getAnnouncement($keys, $type = 1){
		if(($test = $this->testTicket())!==true)return $test;
    if(!is_array($keys))$keys = array($keys);
		$anns = array();
		foreach($keys as $key){
			$data = $this->_genData(array(
				'courseId'=> $this->_Course[$key]['CourseId'],
				'bulType' => $type
			));
			$return = $this->_post('GetStuAnnouncementList', $data);
			foreach($return as $entry){
				$ann = $this->_fetchXml($entry, array('BulletinId', 'Caption', 'Content', 'BeginDate', 'EndDate'));
				$ann['id'] = $key;
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
			$ann['id'] = $this->getCourseKey($entry->CourseId);
			$anns[] = $ann;
		}
		return $anns;
	}
  
  function getMaterialDocList($keys, $type){
		if(($test = $this->testTicket())!==true)return $test;
    if(!is_array($keys))$keys = array($keys);
		$docs = array();
		foreach($keys as $key){
			$data = $this->_genData(array(
				'courseId'=> $this->_Course[$key]['CourseId'],
				'docType' => $type
			));
			$return = $this->_post('GetMaterialDocList', $data);
			foreach($return as $entry){
				$doc = $this->_fetchXml($entry, array('DocumentId', 'DisplayName', 'BeginDate', 'EndDate', 'Summary'));
				$doc['id'] = $key;
				$docs[] = $doc;
			}
		}
		return $docs;
  }
  
  function getMaterialDocSummary($docId){
		if(($test = $this->testTicket())!==true)return $test;
    if(!is_array($keys))$keys = array($keys);
		$data = $this->_genData(array(
			'docId' => $docId
		));
		$return = $this->_post('GetMaterialDocSummary', $data);
		$doc = $this->_fetchXml($return, array('DocumentId', 'DisplayName', 'BeginDate', 'EndDate'));
		$doc['id'] = $this->getCourseKey($entry->CourseId);
		return $doc;
  }
	
	function getStuHomeworkList($key, $type){
		if(($test = $this->testTicket())!==true)return $test;
		$ass = array();
		$data = $this->_genData(array(
			'courseId' => $this->_Course[$key]['CourseId'],
			'listType' => $type
		), true);
		$return = $this->_post('GetStuHomeworkList', $data);
		foreach($return as $entry){
			$hw = $this->_fetchXml($entry, array('HomeworkId', 'DisplayName', 'BeginDate', 'EndDate', 'SubmitType'));
			$hw['id'] = $key;
			$hw['type'] = (int)$type;
			$ass[] = $hw;
		}
		return $ass;
	}
  
  function getAttachFileList($resId, $type, $key){
    if(($test = $this->testTicket())!==true)return $test;
    $metaType = $this->_MetaType[$type];
		$files = array();
    $data = $this->_genData(array(
      'courseId'=> $this->_Course[$key]['CourseId'],
      'metaType' => $this->_MetaType[$type],
      'resId' => $resId
    ));
  	$return = $this->_post('GetAttachFileList', $data);
		foreach($return as $entry){
			$file = $this->_fetchXml($entry, array('DisplayFileName', 'RealityFileName', 'MimeType', 'FileSize', 'CreateTime'));
			$file['id'] = $key;
			$files[] = $file;
		}
		return $files;
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

	
	function getCourseKey($course_id){
		foreach($this->_Course as $key => $course){
			if($course_id == $course['CourseId'])
				return $key;
		}
		return false;
	}
	
	function getCourseByKey($course){
		return $this->_Course[$course];
	}
	
	function getAllCourse(){
		return $this->_Course;
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