$(document).ready(function(){
	$("body").on("click",".edit-contact",function(){
		my_id = this.id.split("_")[1];
		form_data = {
				id: my_id,
				ajax: 1
		};
		$.ajax({
			type:"get",
			url: base_url + "contact/edit",
			data: form_data,
			success: function(data){
				show_popup("Edit Contact", data, "auto");
			}
		});
	});
	
	$("body").on("click",".add-contact",function(e){
		e.preventDefault();
		let my_id = this.id.split("_")[1];
		let my_url = $(this).attr("href");
		let form_data = {
				hotel_id: my_id,
				ajax: 1
		};
		$.ajax({
			type: "get",
			url: my_url,
			data: form_data,
			success: function(data){
				show_popup("Add a New Contact",data, "auto");
			}
		});
	});
	
	$("body").on("click",".delete-contact", function(){
		my_id = this.id.split("_");
		my_contact = my_id[1];
		my_hotel = my_id[2];
		decision = confirm("Are you sure you want to delete this contact? This cannot be undone!");
		form_data = {
				id: my_contact,
				hotel_id: my_hotel,
				ajax: 1
		};
		if(decision){
			$.ajax({
				type: "post",
				url: base_url + "contact/delete",
				data: form_data,
				success: function(data){
					window.location.href = base_url + "hotel/view/" + my_hotel;
				}
			});
		}
	});
	
});
