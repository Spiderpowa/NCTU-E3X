/* CourseTable */
var CourseTable = function(){
  var _obj;
  var _prevSection = {
    M:'XXX',//Doesn't exist
    N:'M',
    A:'N',
    B:'A',
    C:'B',
    D:'C',
    X:'D',
    E:'X',
    F:'E',
    G:'F',
    H:'G',
    I:'H',
    J:'I',
    K:'J',
    L:'K'
  }
  var _data;
  var _self = this;
  this.setData = function(data){
    _data = data;
  }
  this.render = function(target){
    _obj = $(target);
    var data = _data;
    var other_course = _obj.find('#other-course');
    other_course.hide();
    other_course.append('<h4>其他課程：</h4>');
    for(var i=0; i<data.length; ++i){
      var course = data[i].data;
      if(!course.length){
        other_course.append('<div>' + data[i].name + '</div>'); 
        other_course.show();
      }
      merge_course(course);
      for(var j=0; j<course.length; ++j){
        var time = course[j];
        var div = _obj.find('#course_'+time.WeekDay+time.Section);
        if(time.merge){
          div.css('border-top', 'none');
          div.text('');
        }else{
          div.addClass('course_color_main');
          var courseName = $('<div>').appendTo(div);
          var courseRoom = $('<div>').appendTo(div);
          courseName.text(time.CourseName);
          courseRoom.text(time.RoomNo);
        }
        div.addClass('course_color'+(i%4));
      }
    }
  }
  
  this.save = function(){
    $.cookie('coursetable', JSON.stringify(_self.getData()), {expires:365});
  }
  
  this.load = function(){
    var dataStr = $.cookie('coursetable');
    if(!dataStr){
      bootbox.alert('沒有離線課表');
      return;
    }
    _data = $.parseJSON(dataStr);
  }
  
  this.getData = function(){
    return _data;
  }
  
  var merge_course = function(course){
    var has_course = new Array();
    for(var i=0; i<8; ++i)has_course.push({});
    //Fill all course section
    for(var i=0; i<course.length; ++i){
      var time = course[i];
      has_course[time.WeekDay][time.Section] = true;
    }
    //Start Merge
    for(var i=0; i<course.length; ++i){
      var time = course[i];
      if(has_course[time.WeekDay][_prevSection[time.Section]] === true){
        time.merge = true;
      }else{
        time.merge = false;
      }
    }
  };
}