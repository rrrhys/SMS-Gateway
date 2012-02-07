var templates = {};
templates.load_templates = function()
	{
		//call JSON from server really.
		templates.templates_received = {};
		/*templates.templates_received.templates = [];
		var template_1 = {};
		template_1.name = "Car Ready today";
		template_1.id = "TEMPLATE1";
		template_1.fields_required = [];
		template_1.fields_required.push("name");
		template_1.fields_required.push("time");
		template_1.text = "Hi {name}, your car will be ready at {time} today.";
		templates.templates_received.templates.push(template_1);*/
		$.post("./get_templates.php",{},function(data){
			var dataobj = $.parseJSON(data);
			$("#templates").html("");
			$("#templates").append("<option class=\"template\" id=\"NOTEMPLATE\" value=\"No Template\" selected>No Template</option>");
			templates.templates_received.templates = dataobj.templates;
			$.each(dataobj.templates,function(key,template){
			$("#templates").append("<option class=\"template\" id=\"" + template.id + "\" value=\"" + template.name + "\">" + template.name + "</option>");
			});
		});
		/*$("#templates").html("");
		$("#templates").append("<option class=\"template\" id=\"NOTEMPLATE\" value=\"No Template\" selected>No Template</option>");
		$.each(templates.templates_received.templates,function(key,template){
			
			$("#templates").append("<option class=\"template\" id=\"" + template.id + "\" value=\"" + template.name + "\">" + template.name + "</option>");
		});*/

	}
var show_waiting = function()
	{
	$("body").append("<div id=\"washout\"></div>");
		$("#washout").css({"width" : $("body").width(),
							"height" : $("body").height(),
							"left" : $("body").offset().left,
							"top" : $("body").offset().top});
	$("body").append("<div id=\"washout_display\"></div>");
	$("#washout_display").append("<div id=\"waiting\">Waiting waiting..</div");
	$("#waiting").append("<div id=\"spinner_wrapper\"></div>");
	}
var hide_waiting = function()
	{
	$("#washout").remove();
	$("#washout_display").remove();
	
	}