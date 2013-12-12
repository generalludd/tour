$(document).ready(function(){

	
	$(".edit-field").live("click",function() {
		edit_field($(this));
	});

	$(".save-field").live("blur", function() {
		save_field($(this));
	});
	
	$(".dropdown .save-field").live("change", function(){
		save_field($(this));
	});
	
	$(".checkbox .save-checkbox").live("click",function(){
		save_field($(this));
	});
	
	$(".multiselect .save-multiselect").live("click",function(){
		save_field($(this));
	});
	
});

function show_popup(my_title,data,popup_width,x,y){
	if(!popup_width){
		popup_width=300;
	}
	var myDialog=$('<div id="popup">').html(data).dialog({
		autoOpen:false,
		title: my_title,
		modal: true,
		width: popup_width
	});
	
	if(x) {
		myDialog.dialog({position:x});
	}


	myDialog.fadeIn().dialog('open',{width: popup_width});

	return false;
}

function create_dropdown(my_field, my_category, my_value)
{
	form_data = {
			field: my_field,
			category: my_category,
			value: my_value
	};
	$.ajax({
		type: "get",
		url: base_url + "menu/get_dropdown",
		data: form_data,
		success: function(output){
			return output;
		}
	
	});
}


/**
 * takes a dom element and gets the parent with the .field-envelope class. The id of this envelope item
 * is field_[name] where [name] is the db field name (without brackets) .
 * @param me
 */
function edit_field(me)
{
	my_field = me.parents(".field-envelope").attr("id").split("-")[1];
	my_value = me.html();
	if(me.hasClass("dropdown")){
		my_category = me.attr("menu");
		form_data = {
				field: my_field,
				category: my_category,
				value: my_value
		};
		$.ajax({
			type: "get",
			url: base_url + "menu/get_dropdown",
			data: form_data,
			success: function(output){
				me.html(output).removeClass("edit-field");
			}
		
		});
	}else if(me.hasClass("multiselect")){
			my_category = me.attr("menu");
			form_data = {
					field: my_field,
					category: my_category,
					value: my_value
			};
			$.ajax({
				type: "get",
				url: base_url + "menu/get_multiselect",
				data: form_data,
				success: function(output){
					me.html(output).removeClass("edit-field");
				}
			
			});
		
	}else if(me.hasClass("textarea")){
		me.html("<br/><textarea name='" + my_field + "'class='save-field'>" + my_value + "</textarea>").removeClass("edit-field");
		
	}else if(me.hasClass("checkbox")){
		my_category = me.attr("menu");
		form_data = {
				field: my_field,
				category: my_category,
				value: my_value
		};
		$.ajax({
			type: "get",
			url: base_url + "menu/get_checkbox",
			data: form_data,
			success: function(output){
				me.html(output).removeClass("edit-field");
			}
		});
		
	}else{
		if(me.attr("format")){
			my_format = me.attr("format");
			if(my_format == "currency"){
				my_value = my_value.split("$")[1];
			}
		}
	me.html(
			"<input type='text' name='" + my_field
					+ "' class='save-field' value='"
					+ my_value + "'/>").removeClass("edit-field");
	$(".save-field").focus();
	}
}


function save_field(me)
{
	table = $(me).parents(".grouping").attr("id");
	my_parent = $(me).parents("span");
	my_id = $("#id").val();
	if(table == "order"){
		my_id = $("#order_id").val();
	}
	my_format = $(me).parents("span").attr("format");
	if(my_format == "multiselect"){
		my_field = $(me).parent().children("select").attr("name");
		my_value =  $("#sunlight").val() || [];
		my_value = my_value.join(",",my_value);
	}else{
		my_field = $(me).attr("name");
		my_value = $(me).val();
	}
	form_data = {
		field: my_field,
		value: my_value,
		format: my_format,
		id: my_id
	};
	my_url =  base_url +  table + "/update_value";
	$.ajax({
		type: "post",
		url: my_url,
		data: form_data,
		success: function(output){
			my_parent.addClass("edit-field").html(output);
			
		}
	});
}
	