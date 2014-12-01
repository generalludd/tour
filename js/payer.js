/**
 * 
 */
$(document).ready(function(){
$(".edit-payer").live("click",function(){
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

$(".save-payer-edits").live("click", function(){
	document.forms[0].submit();
});

$(".cancel-payer-edit").live("click", function(){
	$("#payer-editor-block").css("background-color","#FFCCCC");
	tour_id = $("#tour_id").val();
	ok = "<a class='button delete' href='" + base_url + "tourist/view_all/" + tour_id + "' >Yes</a>";
	text = "<p>Do you want to cancel? This will discard ONLY changes<br/> you made to the \"Ticket Details\" column on this page.</p>";
	button_box = "<div class='button-box mini'><ul class='button-list'><li>" + ok + "</li></ul></div>";
	message = text + button_box;
	
	show_popup("Discard Changes to Ticket Details?", message, "auto");
	$("#payer-editor-block").css("background-color","#fff");

});




/*
 * change the hidden value of the payment type based on changes to a dropdown
 * in the payer editor. 
 */
$(".change_payment_type").live("mouseup",function(){
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



$("input.edit-payer-amounts").live("blur",function(){
calculate_cost(1);	
});



$('#tourist-dropdown').live('keyup', function(event) {
	var person_search = this.value;
	if (person_search.length > 6 && person_search != "find person") {
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
			url: base_url + "tourist/find_by_name",
			type: 'GET',
			data: form_data,
			success: function(data){
				//remove the search_list because we don't want to have a ton of them. 
if(data.length > 0){
				$("#search_list").css({"z-index": 10000}).html(data).position({
					my: "left top",
					at: "left bottom",
					of: $("#tourist-dropdown"), 
					collision: "fit"
				}).show();
			}
		}
		});
	}else{
		$("#search_list").hide();
    	$("#search_list").css({"left": 0, "top": 0});


	}
});// end person_search.keyup




$('#tourist-dropdown').live('blur', function(event) {
	$("#search_list").fadeOut();
});



/*
 * "target" in this scriptlet identifies the format of the output from tourist/insert
 * in this case payer returns a list of tourists for a given payer. 
 */

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
			target: "payer",
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

/*
 * target in this scriptlet identifies the format of the output from tourist/insert
 * in this case "tour" will return a confirmation with an option to 
 */

$(".select-payer").live("click", function(){
	my_id = this.id.split("_");
	my_person = my_id[1];
	my_payer = my_id[2];
	my_tour = my_id[3];
	form_data = {
			person_id: my_person,
			payer_id: my_payer,
			tour_id: my_tour,
			target: "tour",
			ajax: 1
	};
	$.ajax({
		type: "post",
		url: base_url + "tourist/insert",
		data: form_data,
		success: function(data){
			window.location.href = base_url + "tourist/view_all/" + my_tour;
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

$(".select-tourist-type").live("click",function(){
	my_id = this.id.split("_")[1];
	form_data = {
			id: my_id,
			ajax: 1
	};
	$.ajax({
		type: "get",
		url: base_url + "tourist/select_tourist_type",
		data: form_data,
		success: function(data){
			show_popup("Select Tourist Type", data, "auto");
		}
	});
});

$(".select-tour").live("click",function(){
	my_id = this.id.split("_")[1];
	form_data = {
			id: my_id,
			ajax: "1"
	};
	$.ajax({
		type: "get",
		url: base_url + "tour/show_current",
		data: form_data,
		success: function(data){
			show_popup("Select a Tour",data,"auto");
		}
	});
});



$(".select-as-tourist").live("click",function(){
	my_tour = this.id.split("_")[1];
	my_person = $("#person_id").val();
	form_data = {
			tour_id: my_tour,
			person_id: my_person,
			ajax: "1"
	};
	$.ajax({
		type: "get",
		url: base_url + "payer/select_payer",
		data: form_data,
		success: function(data){
			$("#tourist-selector").html(data);
		}
	});
});

$(".select-as-payer").live("click",function(){
	my_tour = this.id.split("_")[1];
	my_person = $("#person_id").val();
	form_data = {
			ajax:"1",
			tour_id: my_tour,
			payer_id: my_person
	};
	$.ajax({
		type: "post",
		url: base_url + "payer/insert",
		data: form_data,
		success: function(data){
			document.location.href = base_url + "payer/edit/?payer_id=" + my_person + "&tour_id=" + my_tour;
//			$("#tourist-selector").html(data);
		}
	});
});

$(".delete-payer").live("click",function(){
	request = confirm("Only delete a payer if they have been added to the tour by mistake. Check 'Cancel' if they have dropped out.");
	if(request){
		plea = confirm ("Are you really sure? This will remove this payer, all their accompanying tourists, and any roommmate records for the tour they may have. Continue?");
	if(plea){
		my_id = this.id.split("_");
		my_payer = my_id[1];
		my_tour = my_id[2];
		form_data = {
				tour_id: my_tour,
				payer_id: my_payer,
				ajax: 1
		};
		$.ajax({
			type: "post",
			url: base_url + "payer/delete",
			data: form_data,
			success: function(){
			}
		});
		window.location.href = base_url + "tourist/view_all/" + my_tour;

	}
	
	}
});

$(".create-new-tourist").live("click", function(){
	tourist_name = $("#tourist-dropdown").val();
	first_name = tourist_name.split(" ")[0];
	last_name = tourist_name.split(" ")[1];
	form_data = {
			ajax: '1'
	};
	$.ajax({
		type: "get",
		url: base_url + "tourist/create",
		data: form_data,
		success: function(data){
			$("#add-new-tourist").html(data);
			$(".create-new-tourist").fadeOut();
			$("#tourist-mini-selector").fadeOut();
			$("#first_name").val(first_name);
			$("#last_name").val(last_name);
		}
	});
});

$(".insert-new-tourist").live("click",function(){
	my_tour = $("#tour_id").val();
	my_payer = $("#payer_id").val();
	form_data = {
			tour_id: my_tour,
			payer_id: my_payer,
			first_name: $("#first_name").val(),
			last_name: $("#last_name").val(),
			shirt_size: $("#shirt_size").val(),
			ajax: '1'
	};
	$.ajax({
		type: "post",
		data: form_data,
		url: base_url + "tourist/insert_new",
		success: function(data){
			$(".create-new-tourist").fadeOut();
			$("#add-new-tourist").children().remove();
			$("#payer-tourist-list").html(data);
			tourist_count = $("#payer-tourist-list tr").length;
			$("#tourist_count").val(tourist_count);
			calculate_cost(1);
			$(".create-new-tourist").fadeIn();
			$("#tourist-mini-selector").fadeIn();
			$("#tourist-dropdown").val("");
		}
		
	});
	

	
});

$(".select-letter").live("click", function(){
	my_id = this.id.split("_");
	my_payer = my_id[1];
	my_tour = my_id[2];
	form_data = {
			payer_id: my_payer,
			tour_id: my_tour,
			ajax: 1
	};
	$.ajax({
		type: "get",
		url: base_url + "letter/select",
		data: form_data,
		success: function(data){
			show_popup("Select Letter",data,"auto");
		}
	});

});


$(".insert-merge-note").live("click", function(){
	$(this).fadeOut();
	$.ajax({
		type: "get",
		url: base_url + "merge/create_note",
		success: function(data){
			$("#merge-note").html(data);
		}
	});
});

$(".edit-merge-note").live("click", function(){
	$(this).fadeOut();
	my_id = $("#id").val();
	form_data = {
			id: my_id,
			field: "note",
			ajax: 1
	};
	$.ajax({
		type: "get",
		url: base_url + "merge/get_note",
		data: form_data,
		success: function(data){
			$("#merge-note").html(data);
			console.log(data);

		}
	});
});

$(".save-note").live("click", function(){
	my_id = $("#id").val();
	my_note = $("#note").val();
	form_data = {
			id: my_id,
			field: "note",
			value: my_note,
			ajax: 1
	};
	$.ajax({
		type: "post",
		url: base_url + "merge/update_value",
		data: form_data,
		success: function(data){
			$("#merge-note").html(data);
			$("#note-button-block").html("<span class='button edit small edit-merge-note'>Edit Custom Note</span>");
		}
	});
});

});//end document load



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
	$("#total_cost").html(( parseInt(tourist_count) * (parseInt(tour_price)) + parseInt(room_rate) - parseInt(discount)));
	if(include_amt){
		$("#amt_due").html(
				(parseInt(tourist_count) * (parseInt(tour_price)))-amt_paid -parseInt(discount)  + parseInt(room_rate)
		);
	}

}
