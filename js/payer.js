/**
 * 
 */
$(document).ready(function(){
$(".edit_payer").live("click",function(){
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
$(".change_payment_type").live("change",function(){
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



$("input.edit_payer_amounts").live("blur",function(){
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
			url: base_url + "person/find_by_name",
			type: 'GET',
			data: form_data,
			success: function(data){
				//remove the search_list because we don't want to have a ton of them. 

				$("#search_list").css({"z-index": 10000}).html(data).position({
					my: "left top",
					at: "left bottom",
					of: $("#tourist-dropdown"), 
					collision: "fit"
				}).show();
		}
		});
	}else{
		$("#search_list").hide();
    	$("#search_list").css({"left": 0, "top": 0});


	}
});// end person_search.keyup



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
