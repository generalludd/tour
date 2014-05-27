$(document).ready(function(){
	$(".add-roommate").live("click",function(){
		my_room = $("#room").val();
		if(!my_room){
		my_room = this.id.split("_")[1];
		}
		my_tour = $("#tour_id").val();
		my_stay = $("#stay").val();
		form_data = {
				stay: my_stay,
				tour_id: my_tour,
				room: my_room,
				ajax: 1
		};
		
		
		$.ajax({
			type: "get",
			url: base_url + "roommate/get_roomless_menu",
			data: form_data,
			success: function(data){
				$("#room_" + my_room + " table.list tbody").append("<tr><td>" + data + "</td></tr>");
			}
			
		});
		
	});
	
	$(".add-room").live("click",function(){
		my_id = this.id.split("_");
		my_tour = my_id[1];
		my_stay = my_id[2];
		form_data = {
				stay: my_stay,
				tour_id: my_tour,
				ajax: 1
		};
		$.ajax({
			type: "get",
			url: base_url + "room/create",
			data: form_data,
			success: function(data){
				$("#roommate-list-block").append(data);
			}
		});
	});
	
	$(".roommates #person_id").live("change",function(){
		my_tour = $("#tour_id").val();
		my_stay = $("#stay").val();
		my_room = $(this).parents("div.room-row").attr("id").split("_")[1];
		my_person = $(this).val();
		form_data = {
			tour_id: my_tour,
			stay: my_stay,
			room: my_room,
			person_id: my_person,
			ajax: '1'
		};
		$.ajax({
			type: "post",
			url: base_url + "roommate/insert_row",
			data: form_data,
			success: function(data){
				$(".room-row#room_" + my_room).html(data);
			}
		});
	});
	
	$(".delete-roommate").live("click",function(){
		question = confirm("Are you sure you want to delete this roommate? This cannot be undone!"); 
	if(question){
		my_id = this.id.split("_");
		my_room = my_id[1];
		my_person = my_id[2];
		my_tour = $("#tour_id").val();
		my_stay = $("#stay").val();
		form_data = {
				room: my_room,
				tour_id: my_tour,
				person_id: my_person,
				stay: my_stay,
				ajax: "1"
		};
		$.ajax({
			type: "post",
			url: base_url + "roommate/delete",
			data: form_data,
			success: function(data){
				$(".room-row#room_" + my_room).html(data);
			}
		});
		
	}
	});
	
	
	
});//end document.ready

function sort_ids(elements)
{
	    elements.sort(function(a,b){
	        return parseInt(a.id) > parseInt(b.id);
	    });
	   

}