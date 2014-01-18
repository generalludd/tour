$(document).ready(function(){
	$('#person_search').live('keyup', function(event) {
		var person_search = this.value;
		if (person_search.length > 6 && person_search != "find people") {
			search_words = person_search.split(' ');
			my_name = search_words.join('%');
			var form_data = {
				ajax: 1,
				name: my_name
			};
			$.ajax({
				url: base_url + "person/find_by_name",
				type: 'GET',
				data: form_data,
				success: function(data){
					//remove the search_list because we don't want to have a ton of them. 

					$("#search_list").css({"z-index": 1000}).html(data).position({
						my: "left top",
						at: "left bottom",
						of: $("#person_search"), 
						collision: "fit"
					}).show();
			}
			});
		}else{
			$("#search_list").hide();
        	$("#search_list").css({"left": 0, "top": 0});


		}
	});// end stuSearch.keyup
	

	$('#person_search').live('focus', function(event) {
		$('#person_search').val('').css( {
			color : 'black'
		});
	});
	
	
	$('#person_search').live('blur', function(event) {
		
		$("#search_list").fadeOut();
		$('#person_search').css({color:'#666'}).val('find people');
		//$("#search_list").remove();
		
		
	});
	
	$(".show-person-filter").live("click", function(){
		$.ajax({
			type: "get",
			url: base_url + "person/show_filter",
			data: "",
			success: function(data){
				show_popup("Filter Search", data, "auto");
			}
		});
	});
	
	$(".delete-person").live("click", function(){
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
	
	$(".disable-person").live("click",function(){
		my_id = this.id.split("_")[1];
		decision = confirm("This person has been on several tours. Their record will only be removed from the list of people in the database. Continue?");
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
	
	$(".restore-person").live("click",function(){
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
	
	$(".create-person").live("click",function(){
		form_data = {
				ajax: '1'
		};
		$.ajax({
			type:"get",
			url: base_url + "person/create",
			data: form_data,
			success: function(data){
				show_popup("Creating a New Person", data, "auto");
			}
		});
	});
	
	$(".edit-person").live("click",function(){
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
	
	
	$(".add-housemate").live("click", function(){
		my_person = this.id.split("_")[1];
		my_address = this.id.split("_")[2];
		form_data = {
				address_id: my_address,
				ajax: "1"
		};
		$.ajax({
			type:"post",
			url: base_url + "person/add_housemate",
			data: form_data,
			success: function(data){
				show_popup("Adding a Housemate", data, "auto");
			}
		});
	});


});

