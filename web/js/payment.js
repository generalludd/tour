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
	
	$(document).on("click", ".add-payment",function(e){
		e.preventDefault();
		let my_href = $(this).attr('href');
		$(this).fadeOut(1000);
		let form_data = {
			ajax: 1
		};

		$.ajax({
			type: "get",
			data: form_data,
			url: my_href,
			success: function(data){
				$("#payment-list tbody").append(data);
				
			}
		});
	});
	
	$(document).on("click",".add-reimbursement", function(e){
		e.preventDefault();
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
	
	$(document).on("click",".insert-payment",function(e){
		e.preventDefault();
		insert_amount(this);
	});
	
	$(document).on("click",".insert-reimbursement",function(e){
		e.preventDefault();
		insert_amount(this);
	});
	
	$(document).on("click",".delete-payment",function(){
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
	$(document).on("click",".delete-reimbursement",function(){
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


function insert_amount(me){
	let my_tour = $(me).data('tour_id');
	let my_payer = $(me).data('payer_id');
	let my_type = $(me).data('type');
	let my_receipt_date = $("#receipt_date").val();
	let my_href = $(me).attr('href');
	let my_target = '#' + my_type + 's';
	let amt = $("#amount").val();
	if(my_type == 'reimbursement'){
		if(amt > 0){
			amt = amt * -1;
		}
	}
	let form_data = {
		tour_id: my_tour,
		payer_id: my_payer,
		amount: amt,
		type: my_type,
		receipt_date: my_receipt_date,
		ajax: 1
	};
	$.ajax({
		type: "post",
		data: form_data,
		url: my_href,
		success: function(data){
			$(my_target).html(data);
			set_payment_total();
		}
	});
}
