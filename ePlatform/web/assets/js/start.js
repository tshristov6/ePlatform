$(document).ready(function() {
	$("#signin").validate({
		submitHandler : function(form) {
			$('#start_btn').attr('disabled','disabled');
		    form.submit();
		},
		rules : {
			name : {
				required : true,
				minlength : 3,
				remote : {
					url : "check_name.php",
					type : "post",
					data : {
						username : function() {
							return $("#name").val();
						}
					}
				}
			},
			category : {
			    required : true
			},
			num_questions : {
				required : true
			}
		},
		messages : {
			name : {
				required : "Please enter your name",
				remote : "Name is already taken, Please choose some other name"
			},
			category:{
                required : "Please choose your category to start Quiz"
           },
           num_questions : {
           	   required : "Please choose number of questions to be showed on each page"
           }
		},
		errorPlacement : function(error, element) {
			$(element).closest('.form-group').find('.help-block').html(error.html());
		},
		highlight : function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		}
	});
});