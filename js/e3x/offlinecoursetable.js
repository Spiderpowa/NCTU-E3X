var initOfflineCourseTable = function(){
  var courseTable = new CourseTable();
  courseTable.load();
  courseTable.render('#coursetable');
}

initOfflineCourseTable();