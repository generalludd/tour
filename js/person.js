$(document).ready(function(){
	$('#person_search').live('keyup', function(event) {
		var person_search = this.value;
		if (person_search.length > 2 && person_search != "find people") {
			search_words = person_search.split(' ');
			my_name = search_words.join('%');
			var form_data = {
				ajax: 1,
				name: my_name
			};
			$.ajax({
				url: base_url + "person/find_by_name",
				type: 'GET',
				data: form_data,
				success: function(data){
					//remove the search_list because we don't want to have a ton of them. 

					$("#search_list").css({"z-index": 1000}).html(data).position({
						my: "left top",
						at: "left bottom",
						of: $("#person_search"), 
						collision: "fit"
					}).show();
			}
			});
		}else{
			$("#search_list").hide();
        	$("#search_list").css({"left": 0, "top": 0});


		}
	});// end stuSearch.keyup
	

	$('#person_search').live('focus', function(event) {
		$('#person_search').val('').css( {
			color : 'black'
		});
	});
	
	
	$('#person_search').live('blur', function(event) {
		
		$("#search_list").fadeOut();
		$('#person_search').css({color:'#666'}).val('find people');
		//$("#search_list").remove();
		
		
	});
	
	$(".create-person").live("click",function(){
		form_data = {
				ajax: '1'
		};
		$.ajax({
			type:"get",
			url: base_url + "person/create",
			data: form_data,
			success: function(data){
				show_popup("Creating a New Person", data, "auto");
			}
		});
	});


});