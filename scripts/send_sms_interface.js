

$(function(){

	
	function user_selected_template()
	{
		$("#template_header").hide();
		$("#template_fields").html("");
			var selected = $(".template:selected");
			$.each(templates.templates_received.templates,function(index,template){
				if(template.id == $(selected).attr('id'))
				{
					//this is the template chosen.
					$("#sms_text").text(template.text);
					$("#base_sms_text").val(template.text);
					if(template.fields_required.length > 0)
					{
						$("#template_fields").hide();
						$.each(template.fields_required,function(index,field_req){
							if(field_req != ""){
								$("#template_fields").append(
									"<label class='control-label' for='field_req_" + field_req + "'>" + field_req + "</label>" +
									"  <div class='controls'>" +
									"    <input type='text' name='field_req_" + field_req + "' id='field_req_" + field_req + "' class='input-xlarge required_field' />" +
									"  </div><br />");
								//$("#template_fields").append("<tr><th>" + field_req + ": </th><td><input type=\"text\" id=\"field_req_" + field_req + "\" class=\"required_field\" /></td></tr>\r\n");
							}
						});
						$("#template_fields").show();
						
					}
				}
			});
			}
			
	function clear_form_fields()
	{
	$("#sms_text").val("");
	$("#sms_phone_number").val("");
	}
	

	
	var sms_preview = function(sms_text)
	{
	
        $("#sms_body").text("");
        $("#sms_body").append("<div id=\"sms_bottom\"></div>");
		$("#sms_body").append(sms_text);
$("#iphone").show();
		$("#iphone_sms").append("<div id=\"sms_heading\">Text from +61 404 123 300</div>");

	};
	$("#iphone").live('click',function(){
	$("#iphone").hide();
	});
	templates.load_templates(function(){
			$("#templates").html("");
			$("#templates").append("<option class=\"template\" id=\"NOTEMPLATE\" value=\"No Template\" selected>No Template</option>");
			$.each(templates.templates_received.templates,function(key,template){
			$("#templates").append("<option class=\"template\" id=\"" + template.id + "\" value=\"" + template.name + "\">" + template.name + "</option>");
			});
	})
	clear_form_fields();
	$("#wait_activation").hide();
	$("#specify_time_wrapper").hide();
		$("#now_button").click(function(){
			$("#specify_time_wrapper").hide();
			$("#specify_button").css({"font-weight":"normal"});
			$("#now_button").css({"font-weight": "bold"});
			$("#queue_type").val("now");
		});
		$("#specify_button").click(function(){
			$("#specify_time_wrapper").show();
			$("#now_button").css({"font-weight":"normal"});
			$("#specify_button").css({"font-weight": "bold"});
			$("#queue_type").val("datepick");
		});

		$("#send_sms_submit,#send_sms_from_preview").live('click',function(){
		//get rid of the iphone preview.
			$("#washout").remove();
			$("#washout_display").remove();
			show_waiting();
			var sms_package = {};
			sms_package.phone_number = $("#sms_phone_number").val();
			sms_package.text = $("#sms_text").val();
			sms_package.token = $("#login_token").val();
			//set up time.
			if($("#queue_type").val() == "datepick")
			{
				sms_package.time = $("#specify_day").val() + "/" + $("#specify_month").val() + "/" + $("#specify_year").val() + " "+ $("#specify_time").val();
			}
			else
			{
				sms_package.time = "now";
			}
			var package_to_send = JSON.stringify(sms_package);
			//for(i = 0; i < 500; i++)
			//{
			$.post("/user/queue_sms/",
				{	
					'action': 'send',
					'phone': sms_package.phone_number,
					'message_text': sms_package.text,
					'schedule': sms_package.time,
					'billing_id': sms_package.token,
				},
				function(data){
				dataobj = $.parseJSON(data);
				window.location = "/user/create_sms";
				clear_form_fields();
				hide_waiting();
				
				
				}
			);
			//}
			
			//reset form

			templates.load_templates();

			user_selected_template();
			clear_form_fields();
		});
		$("#templates").change(function(){user_selected_template();});
		
			$(".required_field").live('change',function(){
				//user is entering data in a template field for this sms, so live update the text for them.
				var base_text = $("#base_sms_text").val();
				$(".required_field").each(function(){
					var data_field_id = $(this).attr('id');
					var text_placeholder = "{" + data_field_id.substr(10,data_field_id.length - 10) + "}";
					var text_entered = $(this).val();
					base_text = base_text.replace(text_placeholder,text_entered);
				});
				$("#sms_text").val(base_text);
				//alert(base_text);
			});
	
	$("#preview_sms").click(function(){sms_preview($("#sms_text").val());});
	
	
	$("#close_preview_box").live('click',function(){
	$("#washout").remove();
	$("#washout_display").remove();
	});
	
	$("#specify_time").timePicker();
	
});