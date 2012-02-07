<?require_once("site_required.php");?>
<html>
	<head><title></title></head>
	<link href='http://fonts.googleapis.com/css?family=Maven+Pro|Nunito:700&subset=latin&v2' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/css.css" />
	
	<body>
	
		<div id="container">
				<? include("header.php"); ?>
				You have the following templates set up:
			<table cellspacing=0 cellpadding=0 class="bordered">
				<tr>
					<th>Template Name</th>
					<th>Template Text</th>
					<th>Times Used</th>
					<th>Delete</th>
				</tr>
				<tbody id="templates_list">

				</tbody>

			</table>
		</div>
		
	</body>
	<script type="text/javascript" src="scripts/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="scripts/site.js"></script>
	<script type="text/javascript">
	$(function(){
		$.post("./get_templates.php",{},function(data){
			$("#templates_list").html("");
			var data_obj = $.parseJSON(data);
			if(data_obj.templates.length > 0)
			{
				$.each(data_obj.templates,function(key,template){
					$("#templates_list").append("<tr><td>" + template.name + "</td><td>" + template.text + "</td><td>" + template.times_used + "</td><td></td></tr>");
				});
			}
			else
			{
				$("#templates_list").append("<tr class=\"template\"><td colspan=\"4\">You have no templates. Create One</td></tr>");
			}
		});
	});
	</script>
</html>