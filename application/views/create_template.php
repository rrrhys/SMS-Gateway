<div class="span8 offset2">

	<div class="form-horizontal">
	<fieldset>
		<legend>Create a new template</legend>
		<p>You can use templates to store commonly used SMS messages for re-use later.</p>
	
		      <div class="control-group">
	            <label class="control-label" for="templates">Template Name</label>
	            <div class="controls">
	             	<input type="text" name="sms_name" id="sms_name" class="span4"/>
		        </div>
		      </div>
		      <div class="control-group">
		      	<label class="control-label" for="new_field">Required Field</label>
		      	<div class="controls">
		      		<input type="text" name="new_field" id="new_field" class="span3" style="float:left;"/>
		      		<a id="btn_add_field" class="btn btn-info" style="float:left;margin-left:10px;"><i class="icon-plus-sign icon-white"></i> Add</a>
		      	</div>
		      	
		      </div>
		      <div class="control-group">
		      	<label for="sms_text" class="control-label">Template Contents</label>
		      	<div class="controls">
		      		<textarea name="sms_text" id="sms_text"class="span4"></textarea>
		      	</div>
		      </div>
		      <div class="form-actions">
		      	<input type="submit" value="Save Template" id="btn_save_template" class="btn btn-success" />
		      </div>


	   </fieldset>       
	</div>
</div>



<table cellpadding=0 cellspacing=0 style="width: 600px; margin: auto;">

	<tr>
		<th class="fixed_width">Save as new Template</th>
		<td></td>
	</tr>
</table>



<input type="hidden" name="sms_fields" id="sms_fields" value="" />
<script type="text/javascript">
var new_template = {};
new_template.fields_required = [];
$(function(){
	$("#btn_add_field").click(function(){
		var new_field = $("#new_field").val();
		var existing_text = $("#sms_text").val();
		$("#sms_text").val(existing_text + "{" + new_field + "}");
		var sms_fields = $("#sms_fields").val();
		$("#sms_fields").val(sms_fields + "|" + new_field);
		
	});
	
	$("#btn_save_template").click(function(){
		$.post("/user/save_template",{
			'name': $("#sms_name").val(),
			'text': $("#sms_text").val(),
			'fields_required': $("#sms_fields").val(),
		},function(data){
			window.location = "/user/templates";
			return false;
		});
	});
});
</script>
