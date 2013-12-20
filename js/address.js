$(document).ready(function(){
	$(".add_address").live("click",function(){
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
				show_popup("Add address", data, "auto");
			}
			
		});
		
	});
});