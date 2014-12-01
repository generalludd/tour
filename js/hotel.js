$(document).ready(function(){
	
	$(".add-hotel").on("click",function(){
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
	
	$(".edit-hotel").on("click",function(){
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
				show_popup("Editing a Hotel",data,"580px");
			}
		});
	});
	
	$(".delete-hotel").on("click", function(){
		my_id = this.id.split("_");
		my_hotel = my_id[1];
		my_tour = my_id[2];
		decision = confirm("This will completely delete this hotel from the database including all related contacts. Are you sure you want to continue? This cannot be undone!");
		if(decision){
			final_decision = confirm("You have decided to permanently delete this hotel from the database. Be sure to update all other hotel records to fill the gap this leaves! This cannot be undone. Are you sure?");
			if(final_decision){
					form_data = {
							id: my_hotel,
							ajax: 1
					};
					$.ajax({
						type: "post",
						url: base_url + "hotel/delete",
						data: form_data,
						success: function(data){
							window.location.href = base_url + "hotel/view_all/" + my_tour;
						}
					});
			}
		}
	});
	
	
});//end document.ready