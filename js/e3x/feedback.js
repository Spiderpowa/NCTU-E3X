$('#feedbackForm').validate({
  rules:{
    name: {
      required:true
    },
    contact: {
      required:true
    },
    feedback: {
      required:true
    }
  },
  highlight: function(element) {
    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
  },
  success: function(element) {
    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
  }
});