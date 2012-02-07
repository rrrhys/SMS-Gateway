<h3>Templates already set up for you to use:</h3>
<table class="table table-striped">
<thead>
	<tr class="horz_header">
	<th class="first">Name</th>
	<th>Text</th>
	<th>Fields Used</th>
	<th>Times Used</th>
	<th class="last">Actions</th>
	</tr>
</thead>
<tbody id="templates_received_wrapper">

</tbody>
<tr><td colspan=5><a href="/user/add_template">Add new Template</a></td>
</table>
<script type="text/javascript">

function templates_into_table(){
		$("#templates_received_wrapper").html("");
		if(templates.templates_received.templates.length == 0)
		{
			$("#templates_received_wrapper").append(
				"<tr class=\"bottom\"><td colspan=5>There are no existing templates. <a href='/user/add_template'>Create one</a></td></tr>"
			);
		}
		row_alt_class = "regular";
		row_bottom_class = "";
		$.each(templates.templates_received.templates,function(index,template){
		if(index == templates.templates_received.templates.length-1)
		{
			row_bottom_class = "bottom";
		}
		row_alt_class = row_alt_class == "regular"? "alt" : "regular";
			$("#templates_received_wrapper").append(
				"<tr class=\"" + row_bottom_class + "\"><td class=\"first\">" + template.name + "</td><td>" + template.text + "</td><td>Name, Time</td><td>5</td><td class=\"last\">Edit | Delete</td></tr>"
			);
		});
		}

$(function(){
	
	templates.load_templates(templates_into_table);
	
	
});
</script>
