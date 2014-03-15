$(document).ready(function(){

	$(".edit-payments").live("click", function(){
		my_id = this.id.split("_");
		my_tour = my_id[1];
		my_payer = my_id[2];
		form_data = {
				tour_id: my_tour,
				payer_id: my_payer, 
				ajax: 1
		};
		$.ajax({
			type: "get",
			url: base_url + "payment/view_list",
			data: form_data,
			success: function(data){
				show_popup("List of Payments",data, "auto");
			}
		});
	});
	
	$(".add-payment").live("click", function(){
		my_id = this.id.split("_");
		my_tour = my_id[1];
		my_payer = my_id[2];
		form_data = {
			tour_id: my_tour,
			payer_id: my_payer,
			ajax: 1
		};

		$.ajax({
			type: "get",
			data: form_data,
			url: base_url + "payment/create",
			success: function(data){
				$("#payment-list tbody").append(data);
			}
		});
	});
	
	$(".insert-payment").live("click",function(){
		my_id = this.id.split("_");
		my_tour = my_id[1];
		my_payer = my_id[2];
		form_data = {
				tour_id: my_tour,
				payer_id: my_payer,
				amount: $("#amount").val(),
				receipt_date: $("#receipt_date").val(),
				ajax: 1
		};
		$.ajax({
			type: "post",
			data: form_data,
			url: base_url + "payment/insert",
			success: function(data){
				$("#payment-list-box").html(data);
				set_payment_total();
			}
		});
	});
	
	$(".delete-payment").live("click",function(){
		ask = confirm("Are you sure you want to delete this payment? This cannot be undone!");
		if(ask){
			again = confirm("Are you absolutely sure?");
			if(again){
				
				my_id = this.id.split("_")[1];

				form_data = {
						id: my_id
				};
				$.ajax({
						type: "post",
						url: base_url + "payment/delete",
						data: form_data,
						success: function(data){
							$("#payment-row_" + my_id).remove();
							$("#payment-list-box").html(data);
							set_payment_total();
						}
			});
			}
		}
	});
	
});

function set_payment_total(){
	total = $("#total-paid").val();
	$("#amt_paid").val(total);
	calculate_cost(1);
}
