<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('e3mobile');
		$this->load->model('user');
		$this->user->sessionLogin();
	}
	
	public function dashboard($key){
		$this->load->helper('url');
		if(!$this->user->isLogin())redirect('/');
		$course = $this->e3mobile->getCourseByKey($key);
    $courseId = $course['CourseId'];
    $announcement = $this->e3mobile->getAnnouncement($key);
    $homework = $this->e3mobile->getStuHomeworkList($courseId, 1);
    $doc1 = $this->e3mobile->getMaterialDocList($courseId, 1);
    $doc2 = $this->e3mobile->getMaterialDocList($courseId, 2);
    $doc = array_merge($doc1, $doc2);
		$this->load->template('course/dashboard', array('course'=>$course, 'announcement'=>$announcement, 'homework'=>$homework, 'document'=>$doc));
	}
  
  //FB Comment
  public function comment($id){
    $this->load->view('course/comment');
  }
}
