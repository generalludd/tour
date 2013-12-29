$(document).ready(function(){
	$(".add_phone").live("click",function(){
		my_id = this.id.split("_")[1];
		form_data = {
				person_id: my_id,
				ajax: 1
		};
		$.ajax({
			type: "get",
			url: base_url + "phone/create",
			data: form_data,
			success: function(data){
				show_popup("Add Phone", data, "auto");
			}
			
		});
		
	});
	
	$(".delete-phone").live("click", function(){
		my_id = this.id.split("_")[1];
		form_data= {
				id: my_id,
				ajax: "1"
		};
		$.ajax({
			type: "post",
			url: base_url + "phone/delete",
			data: form_data,
			success: function(data){
				if(data){
				$("#delete-phone_" + my_id).parents("div.phone-row").remove();
				}
			}
		});
	});
});