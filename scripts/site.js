var templates = {};
templates.load_templates = function(callback)
	{
		templates.templates_received = {};
		$.post("/user/get_templates_json/",{},function(data){
			var dataobj = $.parseJSON(data);
			templates.templates_received.templates = dataobj.templates;
			if(typeof(callback) === "function")
			{
			callback();
			}
		});
			
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
	
var load_dashboard = function()
{
	load_dashboard_sent();
	load_dashboard_queued();
}
var load_dashboard_sent = function()
{
	row_alt_class = "regular";
	row_bottom_class = "";
	$.post("/user/load_dashboard_sent_json",{},function(data){
		var dataobj = $.parseJSON(data);
		$("#sms_sent_wrapper").html("");
		if(dataobj.sms_sent.length == 0)
		{
		$("#sms_sent_wrapper").append("<tr class=\"bottom\"><td colspan=\"5\" class=\"first last\">There are no SMS that have been sent.</td></tr>");
		}
		$.each(dataobj.sms_sent,function(index,sms_sent)
		{
		row_alt_class = row_alt_class == "regular"? "alt" : "regular";
		var msg_text = sms_sent.message_text;
		if(msg_text.length > 40)
			{
			msg_text = msg_text.substr(0,35) + "...";
			}
		if(index == dataobj.sms_sent.length-1)
		{
			row_bottom_class = "bottom";
		}
			$("#sms_sent_wrapper").append("<tr class=\"" + row_alt_class + " " + row_bottom_class + "\"><td class=\"first\">" + sms_sent.phone + "</td><td class='sms_column'><span title=\"" + sms_sent.message_text + "\">" + msg_text + "</span></td><td></td><td>" + sms_sent.time_sent + "</td><td class=\"last\"></td></tr>");
		});
	});
}
var load_dashboard_queued = function()
{
	row_alt_class = "regular";
	row_bottom_class = "";
	$.post("/user/load_dashboard_queued_json",{},function(data){
		var dataobj = $.parseJSON(data);
		$("#sms_queued_wrapper").html("");
		if(dataobj.sms_queued.length == 0)
		{
		$("#sms_queued_wrapper").append("<tr class=\"bottom\"><td colspan=\"4\" class=\"first last\">There are no SMS currently queued.</td></tr>");
		}
		$.each(dataobj.sms_queued,function(index,sms_queued)
		{
			row_alt_class = row_alt_class == "regular"? "alt" : "regular";
			var msg_text = sms_queued.message_text;
			if(msg_text.length > 40)
			{
			msg_text = msg_text.substr(0,35) + "...";
			}
		if(index == dataobj.sms_queued.length-1)
		{
			row_bottom_class = "bottom";
		}
			$("#sms_queued_wrapper").append("<tr class=\"" + row_alt_class + " " + row_bottom_class + "\"><td class=\"first\">" + sms_queued.phone + "</td><td class='sms_column'><span title=\"" + sms_queued.message_text + "\">" + msg_text + "</span></td><td>" + sms_queued.schedule + "</td><td class=\"last\"></td></tr>");
		});
	});
}
$(function(){
	$(".alert").alert('close')
})