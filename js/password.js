$('#popup_container').ready(function(){
	
	$('.password_edit').live('click',function(){
		var my_id="";
		if(this.id) {
			 my_id=this.id.split('_')[1];
		}else if($('#id')) {
			 my_id=$('#id').val();
		}
		var myUrl = base_url + "user/edit_password";
		var form_data = {
				id: my_id,
				ajax: 1
		};
		$.ajax({
			type: "POST",
			url: myUrl,
			data: form_data,
			success: function(data){
			show_popup("Change Password", data, "auto");
		}
		});
	}); // end password_edit
	
	
	$('#new_password').live('keyup',function(){
		match_passwords();
	});
	
	$('#check_password').live('keyup',function(){
		match_passwords();
	});
	
	$('.change_password').live('click',function(){
		var my_id = $("#id").val();
		var my_current_password=$('#current_password').val();
		var my_new_password=$('#new_password').val();
		var my_check_password = $("#check_password").val();
		var valid_password=$("#valid_password").val();
		var form_data = {
				id: my_id,
				current_password: my_current_password,
				new_password: my_new_password,
				check_password: my_check_password,
				ajax: 1
		};
		var myUrl = base_url + "user/change_password";
		if(valid_password=="true" && my_current_password!="") {
			$.ajax({
				type: "POST",
				url: myUrl,
				data: form_data,
				success: function(data){
				$("#password_form").html("<div class='notice'>" + data + "</div>");
				
			}
			});
		}else {
			var message="You have the following error(s):";
			if(valid_password!="true") {;
				message = message + "\rYour passwords do not match!";
				$("#check_password").val("");
				$("#new_password").val("").focus();

				
			}
			if(my_current_password=="") {
				message = message+ "\rYou have not entered your current password!";
				
			}
			
			alert(message);
		}// end if valid_password;
		return false;
			
	});
	
	$('.log_out').live('click', function(){
		document.location = "index.php?target=logout";
	}// end function
	);// end log_out
	
});

function match_passwords() {
	var new_password=$('#new_password').val();
	var check_password=$('#check_password').val();
	if(check_password!="" && new_password!="") {
		if(new_password==check_password) {
			$('#valid_password').val("true");
			$('#password_note').fadeIn().html("Passwords Match");
			$('#change-password').fadeIn();
		}else {
			$('#valid_password').val("false");
			$('#password_note').fadeIn().html("Passwords Do Not Match");
			$('#change-password').fadeOut();

		}
	}
}