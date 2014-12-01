$(document).ready(function() {

	$(".edit-receipt-message").live("click", function() {
		my_id = this.id.split("_")[1];

		form_data = {
			id : my_id,
			ajax : 1
		};
		$.ajax({
			type : "get",
			url : base_url + "receipt/edit",
			data : form_data,
			success : function(data) {
				show_popup("Edit Receipt", data, "auto");
			},
			error : function(data) {
				show_popup("error", data, "600");
			}
		});
	});

	$("#resend").live("click",function(){
		if($("#resend").attr("checked")){
			$(".alert").html("This message will be resent.").fadeIn();
		}else{
			$(".alert").html("").fadeOut();

		}
	});

});
