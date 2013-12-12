$(document).ready(function(){
	$(".order-create").live("click",function(){
		my_id = this.id.split("_")[1];
		form_data = {
				color_id: my_id
		};
		$.ajax({
			type: "get",
			data: form_data,
			url: base_url + "order/create",
			success: function(data){
				show_popup("New Order",data, "auto");
				//$(".all-orders").append(data);
				//$(".order-create").fadeOut();
			}
		});
		
	});
	
	$("input[name='flat_size'],input[name='flat_cost'],input[name='plant_cost']").live("change",function(){
		flat_size = $("input[name='flat_size']").val();
		flat_cost = $("input[name='flat_cost']").val();
		plant_cost = $("input[name='plant_cost']").val();
		if(flat_cost && !plant_cost){
			$("input[name='plant_cost']").val( parseFloat(flat_cost) / parseFloat(flat_size) );
		}else if(!flat_cost && plant_cost){
			$("input[name='flat_cost']").val(parseFloat(flat_size) * parseFloat(plant_cost));
		}
		console.log(flat_cost);
	});
});