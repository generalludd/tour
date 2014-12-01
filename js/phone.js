$(document).ready(function(){
	$(".add-phone").live("click",function(){
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
	
	$(".edit-phone").live("click",function(){
		my_id = this.id.split("_")[1];
		my_person = $("#id").val();

		form_data = {
				id: my_id,
				person_id: my_person,
				ajax: "1"
		};
		$.ajax({
			type: "get",
			url: base_url + "phone/edit",
			data: form_data,
			success: function(data){
				show_popup("Edit Phone", data, "auto");
			}
		});
	});
	
	$(".delete-phone").live("click", function(){
		question = confirm("Are you sure you want to delete this phone number? It cannot be undone!");
		if(question){
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
		}
	});
});