$(document).ready(function(){
	$(".add-address").live("click",function(){
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
	
	$(".edit-address").live("click",function(){
		my_address = this.id.split("_")[1];
		my_person = this.id.split("_")[2];
		form_data = {
				address_id: my_address,
				person_id: my_person,
				ajax: "1"
		};
	
		$.ajax({
			type: "get",
			url: base_url + "address/edit",
			data: form_data,
			success: function(data){
				show_popup("Edit Address", data, "auto");
			}
		});
	});
	
	$(".move-address").live("click",function(){
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
	
	$('#find_housemate').live('keyup', function(event) {
		var find_housemate = $("#find_housemate").val();
		if (find_housemate.length > 2 && find_housemate != "find housemates") {
			search_words = find_housemate.split(' ');
			my_name = search_words.join('%');
			my_person = $("#person_id").val();
			var form_data = {
				ajax: 1,
				person_id: my_person,
				name: my_name
			};
			$.ajax({
				url: base_url + "person/find_for_address",
				type: 'GET',
				data: form_data,
				success: function(data){
					//remove the search_list because we don't want to have a ton of them. 

					$("#housemate-list").css({"z-index": 1000}).html(data).position({
						my: "left top",
						at: "left bottom",
						of: $("#find_housemate"), 
						collision: "fit"
					}).show();
			}
			});
		}else{
			$("#housemate-list").hide();
        	$("#housemate-list").css({"left": 0, "top": 0});


		}
	});// end stuSearch.keyup
	

	$('#find_housemate').live('focus', function(event) {
		$('#find_housemate').val('').css( {
			color : 'black'
		});
	});
	
	
	$('#find_housemate').live('blur', function(event) {
		
		$("#search_list").fadeOut();
		$('#find_housemate').css({color:'#666'}).val('find housemates');
		//$("#search_list").remove();
	});
	
	$(".select-housemate").live("click",function(){
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