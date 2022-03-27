$(document).ready(function(){
	$(".add-address").on("click",function(){
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
	
	$(".edit-address").on("click",function(e){
		e.preventDefault();

		let form_data = {
				address_id: $(this).data('address_id'),
				person_id: $(this).data('person_id'),
				ajax: "1"
		};
	
		$.ajax({
			type: "get",
			url: $(this).attr('href'),
			data: form_data,
			success: function(data){
				show_popup("Edit Address", data, "auto");
			}
		});
	});
	
	$(".change-housemate").on("click",function(){
		my_id = this.id.split("_")[1];
		form_data = {
				person_id: my_id,
				ajax: 1
		};
		$.ajax({
			type: "get",
			url: base_url + "address/find_housemate",
			data: form_data,
			success: function(data){
				show_popup("Select a New Housemate", data, "auto");
			}
		});
	});

	
	$(".select-housemate").on("click",function(){
		my_person = $("#person_id").val();
		my_address = this.id.split("_")[1];
		form_data = {
				id: my_person,
				field: "address_id",
				value: my_address,
				ajax: 1
		};
		$.ajax({
			type: "post",
			url: base_url + "person/update_value",
			data: form_data,
			success: function(){
				window.location = base_url + "person/view/" + my_person;
			}
		});
	});
	
});
