//object definitions.
var notifications = [];
var last_control_id = 0;
adding_notification = 0;
var notification = function(){
this.text = "";
this.seconds_elapsed = 0;
this.control_id = "";
};


function notifier(){
//'something has happened' icon for user

	/*$.post("/user/get_dashboard_changes_count_json,{},function(data){
	
	});*/


//visible notifications to user
	$.post("/user/get_notifications_json",{},function(data){
		var dataobj = $.parseJSON(data);
		$.each(dataobj.notifications,function(index,inbound_notification)
		{
			add_notification("Message sent to " + inbound_notification.phone);
		});
	});
		$.each(notifications,function(index,this_notification)
	{
		this_notification.seconds_elapsed += 2;
		if(this_notification.seconds_elapsed == 10)
		{
			var remove_control_id = this_notification.control_id;
			$("#" + remove_control_id).animate({"opacity" : 0},500);
			setTimeout(function(){$("#" + remove_control_id).remove();},1000);
			notifications.splice(index,1);
			
		}
	});
	
	while(notifications.length > 5)
	{
		var remove_control_id = notifications[0].control_id;
		$("#" + remove_control_id).animate({"opacity" : 0},500);
		setTimeout(function(){$("#" + remove_control_id).remove();},1000);
		
		notifications.splice(0,1);
	}
}



function add_notification(notification_text){
	last_control_id +=1;
	var new_notification = new notification();
	new_notification.control_id = "notification_" + last_control_id;
	new_notification.text = notification_text
	new_notification.seconds_elapsed = 0;
	$("#notification_shell").append("<div id='" + new_notification.control_id + "'style='opacity: 0; position: absolute; bottom: 0; background-color: #fff; border: 1px solid #ccc; padding: 10px; margin: 10px; font-size: 9pt;'>" + 
		"<strong>Notification Received</strong><br />" +
		new_notification.text + "</div>"
	);
	for(i=0; i < notifications.length;i++)
	{
	this_notification = notifications[i];
		var bottom_pos = parseInt($("#" + this_notification.control_id).css("bottom").replace("px",""));
		bottom_pos += 55;
		$("#" + this_notification.control_id).animate({"bottom" : bottom_pos},200);
	}
	notifications.push(new_notification);
	$("#" + new_notification.control_id).animate({"opacity":1},500);
}
$(function(){
	setInterval(notifier,2000);

});