/**
 * 
 */
$(document).ready(function(){
$(".edit-payer").live("click",function(){
	my_id = this.id.split("_");
	form_data ={
	payer_id: my_id[1],
	tour_id: my_id[2],
	ajax: 1
	};
	$.ajax({
		type:"get",
		data: form_data,
		url: base_url + "payer/edit",
		success: function(data){
			show_popup("Edit Tour for Payer",data, "auto");
		}
	});
	
	
});


/*
 * change the hidden value of the payment type based on changes to a dropdown
 * in the payer editor. 
 */
$(".change_payment_type").live("blur",function(){
	my_id = $("#tour_id").val();
	my_type = $(this).val();
	
	form_data = {
			tour_id: my_id,
			field: my_type,
			ajax: 1
	};
	$.ajax({
		type: "get",
		url: base_url + "tour/get_value",
		data: form_data,
		success: function(data){
			$("#tour_price").val(data);
			$("#tour_price_display").html(data);
			total_cost = calculate_cost(1);
		}
	});
	
});

$(".change_room_size").live("change",function(){
	my_id = $("#tour_id").val();
	amt_paid = $("#amt_paid").val().valueOf();
	my_type = $(this).val();
	my_format = "int";
	
	form_data = {
			tour_id: my_id,
			field: my_type,
			ajax: 1
	};
	$.ajax({
		type: "get",
		url: base_url + "tour/get_value",
		data: form_data,
		success: function(data){
			$("#room_rate").val(data);
			$("#room_rate_display").html(data);
			total_cost = calculate_cost(1);
		}
	});
	
});



$("input.edit-payer-amounts").live("blur",function(){
calculate_cost(1);	
});



$('#tourist-dropdown').live('keyup', function(event) {
	var person_search = this.value;
	if (person_search.length > 2 && person_search != "find person") {
		search_words = person_search.split(' ');
		my_name = search_words.join('%') + "%";
		 my_payer = "";
		  my_tour = "";
		if($("#tour_id") && $("#payer_id")){
			my_tour = $("#tour_id").val();
		my_payer = $("#payer_id").val();
		}
		var form_data = {
			ajax: 1,
			name: my_name,
			tour_id : my_tour,
			payer_id: my_payer
		};
		$.ajax({
			url: base_url + "tourist/find_by_name",
			type: 'GET',
			data: form_data,
			success: function(data){
				//remove the search_list because we don't want to have a ton of them. 
if(data.length > 0){
				$("#search_list").css({"z-index": 10000}).html(data).position({
					my: "left top",
					at: "left bottom",
					of: $("#tourist-dropdown"), 
					collision: "fit"
				}).show();
			}
		}
		});
	}else{
		$("#search_list").hide();
    	$("#search_list").css({"left": 0, "top": 0});


	}
});// end person_search.keyup




$('#tourist-dropdown').live('blur', function(event) {
	$("#search_list").fadeOut();
});



/*
 * "target" in this scriptlet identifies the format of the output from tourist/insert
 * in this case payer returns a list of tourists for a given payer. 
 */

$(".select_for_tour").live("click", function(){
	my_id = this.id.split("_");
	my_person = my_id[1];
	my_payer = my_id[2];
	my_tour = my_id[3];
	$("#search_list").hide();

	form_data = {
			person_id: my_person,
			payer_id: my_payer,
			tour_id: my_tour,
			target: "payer",
			ajax: 1
	};
	$.ajax({
		type:"post",
		url: base_url + "tourist/insert",
		data: form_data,
		success: function(data){
			$("#payer-tourist-list").html(data);
			tourist_count = $("#payer-tourist-list tr").length;
			$("#tourist_count").val(tourist_count);
			calculate_cost(1);
		}
	
	});
});

/*
 * target in this scriptlet identifies the format of the output from tourist/insert
 * in this case "tour" will return a confirmation with an option to 
 */

$(".select-payer").live("click", function(){
	my_id = this.id.split("_");
	my_person = my_id[1];
	my_payer = my_id[2];
	my_tour = my_id[3];
	form_data = {
			person_id: my_person,
			payer_id: my_payer,
			tour_id: my_tour,
			target: "tour",
			ajax: 1
	};
	$.ajax({
		type: "post",
		url: base_url + "tourist/insert",
		data: form_data,
		success: function(data){
			$("#tourist-selector").html("<p><a href='tourist/show_all/" + my_tour + "' class='button'>Success: View Tourist List</a></p>");
		}
	});
});



$(".delete-tourist").live("click",function(){
	question = confirm("Are you sure you want to remove this person from this list? This cannot be undone");
	if(question){
		my_id = this.id.split("_");
		my_person = my_id[1];
		my_tour = my_id[2];
		my_payer = $("#payer_id").val();
		form_data = {
				person_id: my_person,
				tour_id: my_tour,
				payer_id: my_payer,
				ajax: 1
		};
		$.ajax({
			type: "post",
			url: base_url + "tourist/delete",
			data: form_data,
			success: function(data){
				$("#payer-tourist-list").html(data);
				tourist_count = $("#payer-tourist-list tr").length;
				$("#tourist_count").val(tourist_count);
				calculate_cost(1);
			}
		});
		}
});

$(".select-tourist-type").live("click",function(){
	my_id = this.id.split("_")[1];
	form_data = {
			id: my_id,
			ajax: 1
	};
	$.ajax({
		type: "get",
		url: base_url + "tourist/select_tourist_type",
		data: form_data,
		success: function(data){
			show_popup("Select Tourist Type", data, "auto");
		}
	});
});

$(".select-tour").live("click",function(){
	my_id = this.id.split("_")[1];
	form_data = {
			id: my_id,
			ajax: "1"
	};
	$.ajax({
		type: "get",
		url: base_url + "tour/show_current",
		data: form_data,
		success: function(data){
			show_popup("Select a Tour",data,"auto");
		}
	});
});



$(".select-as-tourist").live("click",function(){
	my_tour = this.id.split("_")[1];
	my_person = $("#person_id").val();
	form_data = {
			tour_id: my_tour,
			person_id: my_person,
			ajax: "1"
	};
	$.ajax({
		type: "get",
		url: base_url + "payer/select_payer",
		data: form_data,
		success: function(data){
			$("#tourist-selector").html(data);
		}
	});
});

$(".select-as-payer").live("click",function(){
	my_tour = this.id.split("_")[1];
	my_person = $("#person_id").val();
	form_data = {
			ajax:"1",
			tour_id: my_tour,
			payer_id: my_person
	};
	$.ajax({
		type: "post",
		url: base_url + "payer/insert",
		data: form_data,
		success: function(data){
			$("#tourist-selector").html(data);
		}
	});
});

});//end document load

$(".create-new-tourist").live("click", function(){
	form_data = {
			ajax: '1'
	};
	$.ajax({
		type: "get",
		url: base_url + "tourist/create",
		data: form_data,
		success: function(data){
			$("#add-new-tourist").html(data);
			$(".create-new-tourist").fadeOut();
		}
	});
});

$(".insert-new-tourist").live("click",function(){
	my_tour = $("#tour_id").val();
	my_payer = $("#payer_id").val();
	form_data = {
			tour_id: my_tour,
			payer_id: my_payer,
			first_name: $("#first_name").val(),
			last_name: $("#last_name").val(),
			shirt_size: $("#shirt_size").val(),
			ajax: '1'
	};
	$.ajax({
		type: "post",
		data: form_data,
		url: base_url + "tourist/insert_new",
		success: function(data){
			$(".create-new-tourist").fadeOut();
			$("#add-new-tourist").children().remove();
			$("#payer-tourist-list").html(data);
			tourist_count = $("#payer-tourist-list tr").length;
			$("#tourist_count").val(tourist_count);
			calculate_cost(1);
		}
		
	});
	
});

function calculate_cost(include_amt){
	tourist_count = $("#tourist_count").val().valueOf();
	amt_paid = 0;
	if(include_amt){
		amt_paid = $("#amt_paid").val().valueOf();

	}
	if( amt_paid.length == 0 ){
		amt_paid = 0;
	}
	discount = $("#discount").val().valueOf();
	if(discount.length == 0){
		discount = 0;
	}
	room_rate = $("#room_rate").val().valueOf();
	if(room_rate.length == 0){
		room_rate = 0;
	}
	
	tour_price = $("#tour_price").val().valueOf();
	$("#total_cost").html( parseInt(tourist_count) * (parseInt(tour_price) + parseInt(room_rate) - parseInt(discount)));
	if(include_amt){
		$("#amt_due").html(
				(parseInt(tourist_count) * (parseInt(tour_price) + parseInt(room_rate) - parseInt(discount))-amt_paid)
		);
	}

}
