$(document).ready(function(){
	$(".add-address").live("click",function(){
		my_id = this.id.split("_")[1];
		form_data = {
				id: my_id,
				ajax: 1
		};
		$.ajax({
			type: "get",
			url: base_url + "address/create",
			data: form_data,
			success: function(data){
				show_popup("Add Address", data, "auto");
			}
			
		});
		
	});
	
	$(".edit-address").live("click",function(){
		my_address = this.id.split("_")[1];
		my_person = this.id.split("_")[2];
		form_data = {
				address_id: my_address,
				person_id: my_person,
				ajax: "1"
		};
	
		$.ajax({
			type: "get",
			url: base_url + "address/edit",
			data: form_data,
			success: function(data){
				show_popup("Edit Address", data, "auto");
			}
		});
	});
	
});