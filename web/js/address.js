$(document).ready(function(){



	
	$(document).on("click",".select-housemate",function(e){
		e.preventDefault();
		let my_url = $(this).attr('href');
		let person_id = $(this).data('person_id');
		let address_id = $(this).data('address_id');
		let form_data = {
				id: person_id,
				field: "address_id",
				value: address_id,
				ajax: 1,
			target: 'address/view',
		};
		$.ajax({
			type: "post",
			url:  my_url,
			data: form_data,
			success: function(data){
				$("#address").html(data);
			}
		});
	});
	
});
