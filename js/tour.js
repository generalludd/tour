$(document).ready(function(){

$(".edit-tour").live("click", function(){
	my_id = this.id.split("_")[1];
	form_data = {
			id : my_id
	};
	$.ajax({
		type: "get",
		url: base_url + "tour/edit",
		data: form_data,
		success: function(data){
			show_popup("Edit Tour", data, "auto");
		}
	
	});
	
});

$(".create-tour").live("click", function(){
		$.ajax({
			type:"get",
			url: base_url + "tour/create",
			success: function(data){
				show_popup("Create Tour", data, "auto");
			}
		});
	});

});