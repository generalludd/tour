document.addEventListener('DOMContentLoaded', function () {
	// Locate all the buttons with the class .add-roommate and add an event listener to each.
	const addRoommateButtons = document.querySelectorAll('.add-roommate');
	addRoommateButtons.forEach(function (button) {
		button.addEventListener('click', function (event) {
			event.preventDefault();
			const myRoom = button.dataset.room_id;
			const myUrl = button.getAttribute('href');
			fetch(myUrl, {
					method: 'GET',
				}
			)
				.then(response => {

					return response.json();
				})
				.then(data => {
					console.log(data);
					const room = document.getElementById('room_' + myRoom);
					// append a row to the room's table element
					const newRow = document.createElement('tr');
					const newCell = document.createElement('td');
					newCell.classList.add('new-row');
					const newSelect = document.createElement('select');
					newSelect.setAttribute('name', 'person_id');
					const options = data.roomless;
					for (const key in options) {
						const option = document.createElement('option');
						option.setAttribute('value', key);
						option.textContent = options[key];
						newSelect.appendChild(option);
					}
					newCell.appendChild(newSelect);
					// create a link to the data.url
					const newLink = document.createElement('a');
					newLink.setAttribute('href', data.url);
					newLink.textContent = 'Add Placeholder';
					newLink.classList.add('add-placeholder link add');
					newCell.appendChild(newLink);
					newRow.appendChild(newCell);
					room.querySelector('tbody').appendChild(newRow);


					// Process your data here
				})
				.catch(error => {
					console.error('Fetch error:', error);
				});
		});
	});
});
 $(document).ready(function(){
// 	$(document).on("click",".add-roommate",function(e){
// 		e.preventDefault();
// 		let my_room = $(this).data('room_id');
//
// 		let form_data = {
//
// 				ajax: 1
// 		};
// 		console.log(form_data);
//
// 		$.ajax({
// 			type: "get",
// 			url: $(this).attr('href'),
// 			data: form_data,
// 			success: function(data){
// 				$("#room_" + my_room + " table.list tbody").append("<tr><td>" + data + "</td></tr>");
// 			}
//
// 		});
//
// 	});
	
	$(document).on("click",".add-placeholder",function(e){
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
	
	$(document).on("blur",".insert-placeholder",function(e){
		e.preventDefault();
		let me = this;
		let my_room = $(me).parents(".room-row").attr("id").split("_")[1];
		let form_data = {
				room_id: my_room,
				tour_id: $(this).data('tour_id'),
				stay: $(this).data('stay'),
				person_id:$(this).data('person_id'),
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
	
	
	$(document).on("change",".roommates #person_id",function(){
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
	
	$(document).on("click",".delete-roommate",function(){
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
	
	$(document).on("click",".duplicate-previous-stay",function(){
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
				window.location.href = base_url + "roommate/view_for_tour/" + my_id[1] + "/" + my_id[2];
			}
		});
		
	});
	
	$(document).on("click",".delete-room",function(){
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
