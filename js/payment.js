$(document).ready(function(){

	$(".edit-payments").on("click", function(){
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
	
	$(".add-payment").on("click", function(){
		my_id = this.id.split("_");
		my_tour = my_id[1];
		my_payer = my_id[2];
		$(this).fadeOut(1000);
		form_data = {
			tour_id: my_tour,
			payer_id: my_payer,
			type: "payment",
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
	
	$(".add-reimbursement").on("click",function(){
		my_id = this.id.split("_");
		my_tour = my_id[1];
		my_payer = my_id[2];
		$(this).fadeOut(1000);
		form_data = {
				tour_id: my_tour,
				payer_id: my_payer,
				type: "reimbursement",
				ajax: 1
		};
		$.ajax({
			type: "get",
			data: form_data,
			url: base_url  + "payment/create",
			success: function(data){
				$("#reimbursement-list tbody").append(data);
			}
		})
	})
	
	$(".insert-payment").on("click",function(){
		my_id = this.id.split("_");
		my_tour = my_id[1];
		my_payer = my_id[2];
		form_data = {
				tour_id: my_tour,
				payer_id: my_payer,
				amount: $("#amount").val(),
				type: "payment",
				receipt_date: $("#receipt_date").val(),
				ajax: 1
		};
		$.ajax({
			type: "post",
			data: form_data,
			url: base_url + "payment/insert/payment",
			success: function(data){
				$("#payments").html(data);
				set_payment_total();
			}
		});
	});
	
	$(".insert-reimbursement").on("click",function(){
		my_id = this.id.split("_");
		my_tour = my_id[1];
		my_payer = my_id[2];
		amt = $("#amount").val();
		if(amt > 0){
			amt = amt * -1;
		}
		form_data = {
				tour_id: my_tour,
				payer_id: my_payer,
				amount: amt,
				type: "reimbursement",
				receipt_date: $("#receipt_date").val(),
				ajax: 1
		};
		$.ajax({
			type: "post",
			data: form_data,
			url: base_url + "payment/insert/reimbursement",
			success: function(data){
				$("#reimbursements").html(data);
				set_payment_total();
			}
		});
	});
	
	$(".delete-payment").on("click",function(){
		ask = confirm("Are you sure you want to delete this payment? This cannot be undone!");
		if(ask){
				
				my_id = this.id.split("_")[1];

				form_data = {
						id: my_id
				};
				$.ajax({
						type: "post",
						url: base_url + "payment/delete/payment",
						data: form_data,
						success: function(data){
							$("#payment-row_" + my_id).remove();
							$("#payments").html(data);
							set_payment_total();
						}
			});
		}
	});
	$(".delete-reimbursement").on("click",function(){
		ask = confirm("Are you sure you want to delete this reimbursement? This cannot be undone!");
		if(ask){
				my_id = this.id.split("_")[1];
				form_data = {
						id: my_id
				};
				$.ajax({
						type: "post",
						url: base_url + "payment/delete/reimbursement",
						data: form_data,
						success: function(data){
							$("#payment-row_" + my_id).remove();
							$("#reimbursements").html(data);
							set_payment_total();
						}
			});
			}
	});
	
});

function set_payment_total(){
	total = $("#total-paid").val();
	$("#amt_paid").val(total);
	calculate_cost(1);
}
