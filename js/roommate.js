$(document).ready(function(){
	$("html").on("click",".add-roommate",function(){
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
	
	$("html").on("click",".add-placeholder",function(e){
		e.preventDefault();
		me = this;
		my_url = $(me).attr("href");
		form_data = {
			ajax:1	
		};
		$.ajax({
			type:'get',
			data:form_data,
			url:my_url,
			success: function(data){
				$(me).parents("td").html(data);
			}
		});
	});
	
	$("html").on("blur",".insert-placeholder",function(e){
		e.preventDefault();
		me = this;
		my_id = me.id.split("_");
		my_room = $(me).parents(".room-row").attr("id").split("_")[1];
		
		form_data = {
				room_id: my_room,
				tour_id: my_id[1],
				stay: my_id[2],
				person_id: my_id[3],
				placeholder: $(me).val(),
				ajax: 1
		};
		$.ajax({
			type:"post",
			url: base_url + "roommate/insert_placeholder",
			data: form_data,
			success: function(data){
				$(me).parent('td').html(data);
			}
		});
	});
	
	$("html").on("click",".add-room", function(){
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
				location.href = "#end-of-list";
			}
		});
	});
	
	$("html").on("change",".roommates #person_id",function(){
		my_tour = $("#tour_id").val();
		my_stay = $("#stay").val();
		my_room = $(this).parents("div.room-row").attr("id").split("_")[1];
		my_person = $(this).val();
		form_data = {
			tour_id: my_tour,
			stay: my_stay,
			room_id: my_room,
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
	
	$("html").on("click",".delete-roommate",function(){
		question = confirm("Are you sure you want to delete this roommate? This cannot be undone!"); 
	if(question){
		my_id = this.id.split("_");
		my_room = my_id[1];
		my_person = my_id[2];
		my_tour = $("#tour_id").val();
		my_stay = $("#stay").val();
		form_data = {
				room_id: my_room,
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
				$("#roommate-block_" + my_room).html(data);
			}
		});
		
	}
	});
	
	$("html").on("click",".duplicate-previous-stay",function(){
		my_id = this.id.split("_");
		form_data = {
				tour_id: my_id[1],
				stay : my_id[2]
		};
		$.ajax({
			type:"post",
			url: base_url + "roommate/duplicate",
			data: form_data,
			success: function(data){
				window.location.href = base_url + "roommate/view_for_tour/?tour_id=" + my_id[1] + "&stay=" + my_id[2];
			}
		});
		
	});
	
	$("html").on("click",".delete-room",function(){
		question = confirm("are you sure you want to delete this room? This cannot be undone!");
		if(question){
			my_id = this.id.split("_")[1];
			form_data = {
					id:my_id
			};
			
			$.ajax({
				type: "post",
				data: form_data,
				url: base_url + "room/delete",
				success: function(data){
					$("#room_" + my_id).remove();
					
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