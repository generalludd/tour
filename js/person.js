$(document).ready(function(){

	
	$(".show-person-filter").on("click", function(){
		$.ajax({
			type: "get",
			url: base_url + "person/show_filter",
			data: "",
			success: function(data){
				show_popup("Filter Search", data, "auto");
			}
		});
	});
	
	$(".delete-person").on("click", function(){
		my_id = this.id.split("_")[1];
		decision = confirm("This person and their phone information (and address if they have no housemates) will be completely deleted from the database. Are you sure you want to continue? This cannot be undone!");
		if(decision){
			final_decision = confirm("You have decided to permanently delete this person from the database. This cannot be undone. Are you sure?");
			if(final_decision){
					form_data = {
							id: my_id,
							ajax: 1
					};
					$.ajax({
						type: "post",
						url: base_url + "person/delete",
						data: form_data,
						success: function(data){
							window.location.href = base_url;
						}
					});
			}
		}
	});
	
	$(".disable-person").on("click",function(){
		my_id = this.id.split("_")[1];
		decision = confirm("This person has been on several tours. Their record will only be hidden from the searchable list of people in the database. Continue?");
		if(decision){
			form_data = {
					id: my_id,
					ajax: 1
			};
			$.ajax({
				type: "post",
				url: base_url + "person/delete",
				data: form_data,
				success: function(data){
					window.location.href = base_url + "person/view/" + my_id;
				}
			
			});
		}
	});
	
	$(".restore-person").on("click",function(){
		my_id = this.id.split("_")[1];
		form_data = {
			id: my_id,
			ajax: 1
		};
	
		$.ajax({
			type: "post",
			url: base_url + "person/restore",
			data: form_data,
			success: function(data){
				window.location.href = base_url + "person/view/" + my_id;
			}
		});
	});
	
	$(".create-person").on("click",function(e){
		e.preventDefault();
		let my_url = $(this).attr("href");
		let form_data = {
				ajax: '1'
		};
		$.ajax({
			type:"get",
			url: my_url,
			data: form_data,
			success: function(data){
				show_popup("Creating a New Person", data, "auto");
			}
		});
	});
	
	$(".edit-person").on("click",function(){
		my_id = this.id.split("_")[1];
		form_data = {
				ajax: 1
		};
		$.ajax({
			type: "get",
			url: base_url + "person/edit/" + my_id,
			data: form_data,
			success: function(data){
				show_popup("Edit Person", data, "auto");
			}
		});
	});
	
	
	$(document).on("click",".add-housemate", function(e){
		e.preventDefault();
		let form_data = {
				ajax: "1"
		};
		$.ajax({
			type:"get",
			url: $(this).attr('href'),
			data: form_data,
			success: function(data){
				show_popup("Adding a Housemate", data, "auto");
			}
		});
	});
	
	$("#is_veteran").on("change", function(){
		if($("#is_veteran").attr("checked")){
			my_value = 1;
		}else{
			my_value = 0;
		}
		my_id = $("#id").val();
		my_field = "is_veteran",
		form_data = {
				field: my_field,
				value: my_value,
				format: "checkbox",
				id: my_id,
				ajax: 1
		};
		$.ajax({
			type: "post",
			url: base_url + "person/update_value",
			data: form_data,
			success: function(data){
				$("#is_veteran-ajax-response").html("Saved").show().fadeOut(1000);
			}

		});
	});


});

