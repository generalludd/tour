$(document).ready(function() {
			$(".color-create").live("click", function() {
				my_id = this.id.split("_")[1];
				form_data = {
					common_id : my_id,
					ajax : 1
				};
				$.ajax({
					type : "get",
					url : base_url + "color/create",
					data : form_data,
					success : function(data) {
						show_popup("Add a new color", data, "auto");
					}
				});
			});
			
			$(".color-insert").live("click",function(){
				if($("#add_order").attr("checked")){
					$.ajax({
						type: "post",
						url: base_url + "color/insert",
						data: $("#color-editor").serializeArray(),
						success: function(data) {
							$("#ui-dialog-title-popup").html("New Order");
							$("#popup").html(data);
						}
					});
					return false;
				}
			
			});

		$(".flag-add").live("click", function() {
			my_id = $("#id").val();
			form_data = {
					id: my_id,
					ajax: 1
			};
			$.ajax({
				type: "get",
				url: base_url + "color/add_flag",
				data: form_data,
				success: function(data){
					$("#flag-list").append(data);
				}
			});
		});
		
		$(".flag-insert").live("change",function(){
			my_id = $("#id").val();
			my_flag = $(this).val();
			form_data = {
					color_id: my_id,
					name: my_flag,
					ajax: 1
			};
			$.ajax({
				type: "post",
				url: base_url + "color/insert_flag",
				data: form_data,
				success: function(data){
					$("#flag-list").html(data);
				}
			});
		});
		
		$(".flag-delete").live("click",function(){
			my_id = $(this).parent().attr("id").split("_")[1];
			my_color = $("#id").val();
			form_data = {
					id: my_id,
					color_id: my_color,
					ajax: 1
			};
			$.ajax({
				type: "post",
				url: base_url + "color/delete_flag",
				data: form_data,
				success: function(data){
					$("#flag-list").html(data);
				}
			});
			
		});
		
		$(".color-delete").live("click",function(){
			form_data = {
					id: $("#id").val(),
					ajax: 1
			};
			question = confirm("Are you sure you want to delete this color? This is permanent and will delete all related orders and flags!");
			if(question){
				query = confirm("Are you absolutely sure? This is quite permanent and undoable!");
				if(query){
					$.ajax({
						type: "post",
						url: base_url + "color/delete",
						data: form_data,
						success: function(data){
							document.location.href = base_url + "common/view/" + data;
						}
					});
				}
			}
		});
});