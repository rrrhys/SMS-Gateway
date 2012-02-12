	<div class="span8 offset2">
      <form class="form-horizontal">
        <fieldset>
          <legend>Create a new SMS</legend>
          <div class="control-group">
            <label class="control-label" for="templates">Template Name</label>
            <div class="controls">
             	<select name="templates" id="templates" class="span4">
					
				</select>
              	<p class="help-block">Choose an existing template or create a new one <a href="/user/templates">here</a></p>
	        </div>
          </div>
          <div class="control-group">
	            <label class="control-label" for="sms_phone_number">Phone Number</label>
	            <div class="controls">
	            <input type="text" name="sms_phone_number" id="sms_phone_number" class="input-xlarge" />
	            </div>
          </div>
          <div id="template_fields">

          </div>
          <div class="control-group">
          	<label for="sms_text" class="control-label">Contents</label>
	          <div class="controls ">
	          	<input type="hidden" id="base_sms_text" value="" />
				<textarea name="sms_text" id="sms_text" class="input-xlarge"></textarea>
	          </div>
          </div>
          <div class="control-group">
          	<label for="" class="control-label">Schedule</label>
          	<div class="controls">
          		<input type="hidden" id="queue_type" value="" />
          		<label for="now_button" id="now_button_label" class="radio">
					<input type="radio" name="radio_group" id="now_button" />Send Instantly
				</label>
				<label for="specify_button" id="specify_button_label" class="radio">
					<input type="radio" name="radio_group" id="specify_button" />Specify a time
				</label>
				<div id="specify_time_wrapper">

						<select name="specify_day" id="specify_day" class="span1">
						<?for($i=1;$i<=31;$i++){
							?><option value="<?=$i?>"><?=$i?></option><?
						}?>
						</select>
						<select name="specify_month" id="specify_month" class="span2">
						<?for($i=1;$i<=12;$i++){
							?><option value="<?=$i?>"><?=date("F",mktime(1,1,1,$i,1,2000))?></option><?
						}?>
						</select>
						<select name="specify_year" id="specify_year" class="span1">
							<option value="<?=date("Y")?>"><?=date("Y")?></option>
							<option value="<?=date("Y")+1?>"><?=date("Y")+1?></option>
						</select>
						<input type="text" id="specify_time" name="specify_time" class="span1" />
	
				</div>
          	</div>
          </div>
          <div class="form-actions">
          	    <div class="buttons">
					<a href="#" class="btn btn-success" id="send_sms_submit">Send</a>
					<a href="#" class="btn" id="preview_sms">Preview</a>
				</div>

          </div>
          
          
        </fieldset>

      </form>
    </div>

		</div>
		
		</div>
		
		<div id="iphone">
			<div id="iphone_sms">
				<div id="sms_top">
				</div>
				<div id="sms_body">
					<div id="sms_bottom">
					</div>
				</div>
				<div id="sms_heading">Text from +61 404 123 300</div>
			</div>
		</div>
<script type="text/javascript" src="/scripts/send_sms_interface.js"></script>
	