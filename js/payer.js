/**
 * 
 */

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
				(
						parseInt(tourist_count) * (parseInt(tour_price) + parseInt(room_rate) - parseInt(discount)
						)-amt_paid
						));
	}

}