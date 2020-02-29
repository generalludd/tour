$(document).ready(function(){
	
	$(document).on("click",".add-hotel",function(e){
		e.preventDefault();
		let form_data  = {
				ajax: "1",
				stay: $(this).data('stay')
		};
		$.ajax({
			type: "get",
			url: base_url + "hotel/create/" + $(this).data('tour_id'),
			data: form_data,
			success: function(data){
				show_popup("Adding a Hotel",data,"auto");
			}
		});
		
	});
	
	$(document).on("click",".edit-hotel",function(e){
		e.preventDefault();
		let form_data = {
			'ajax': 1
		};
		$.ajax({
			type: "get",
			url: $(this).attr('href'),
			data: form_data,
			success: function(data){
				show_popup("Editing a Hotel",data,"580px");
			}
		});
	});
	
	$(document).on("click",".delete-hotel", function(){
		console.log('here');
		let my_hotel = $(this).data("hotel_id");
		let my_tour = $(this).data("tour_id");
		let decision = confirm("This will completely delete this hotel from the database including all related contacts. Are you sure you want to continue? This cannot be undone!");
		if(decision){
			let final_decision = confirm("You have decided to permanently delete this hotel from the database. Be sure to update all other hotel records to fill the gap this leaves! This cannot be undone. Are you sure?");
			if(final_decision){
					let form_data = {
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
