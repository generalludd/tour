$(document).ready(function(){
	
	$(".add-hotel").live("click",function(){
		my_tour = this.id.split("_")[1];
		form_data  = {
				ajax: "1",
				tour_id: my_tour
		};
		$.ajax({
			type: "get",
			url: base_url + "hotel/create",
			data: form_data,
			success: function(data){
				show_popup("Adding a Hotel",data,"auto");
			}
		});
		
	});
	
	$(".edit-hotel").live("click",function(){
		my_hotel = this.id.split("_")[1];
		form_data = {
			ajax: "1",
			id: my_hotel
		};
		$.ajax({
			type: "get",
			url: base_url + "hotel/edit",
			data: form_data,
			success: function(data){
				show_popup("Editing a Hotel",data,"auto");
			}
		});
	});
	
	
});//end document.ready