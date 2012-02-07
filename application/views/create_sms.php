		<div id="send_sms">
		<form action="" class="form-horizontal">
<div class="span8">
      <form class="form-horizontal">
        <fieldset>
          <legend>Create a new SMS</legend>
          <div class="control-group">
            <label class="control-label" for="templates">Template Name</label>
            <div class="controls">
             	<select name="templates" id="templates">
					
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
          <div id="template_fields" class="control-group">

          </div>
          <div class="control-group">
          	<label for="sms_text" class="control-label">Contents</label>
	          <div class="controls ">
	          	<input type="hidden" id="base_sms_text" value="" />
				<textarea name="sms_text" id="sms_text" class="input-xlarge"></textarea>
	          </div>
          </div>
          
        </fieldset>
      </form>
    </div>
		</form>

		<table cellspacing=0 cellpadding=0 style="width: 600px;margin: auto; padding-top: 50px;">
		<tr>
			<th class="fixed_width">Template Name:</th>
			<td>
				
			</td>
		</tr>
			<tr>
				<th class="fixed_width">Send an SMS to:</th>
				<td></td>
			</tr>
		
		<!--<tbody id="template_fields">
			
		</tbody -->
			<tr>
				<th class="fixed_width">Saying:</th>
				<td>
				</td>
			</tr>
			<tr>
				<th class="fixed_width">When:</th>
				<td>
				<input type="hidden" id="queue_type" value="" />
				<label for="now_button" id="now_button_label" class="radio">
					<input type="radio" name="radio_group" id="now_button" />Send Instantly
				</label><br />
				<label for="specify_button" id="specify_button_label">
					<input type="radio" name="radio_group" id="specify_button" />Specify a time
				</label>
					<span id="specify_time_wrapper">
						<select name="specify_date" id="specify_date">
							<option value="1 July 2011">1 July 2011</option>
							<option value="2 July 2011">2 July 2011</option>
							<option value="3 July 2011">3 July 2011</option>
							<option value="4 July 2011">4 July 2011</option>
							<option value="5 July 2011">5 July 2011</option>
							<option value="6 July 2011">6 July 2011</option>
							<option value="7 July 2011">7 July 2011</option>
							<option value="8 July 2011">8 July 2011</option>
							<option value="9 July 2011">9 July 2011</option>
							<option value="2011-07-10">10 July 2011</option>
							<option value="2011-07-11">11 July 2011</option>
							<option value="2011-07-12">12 July 2011</option>
							<option value="2011-07-13">13 July 2011</option>
						</select>
						<select name="specify_day" id="specify_day">
							<option value="<?=date("Y")?>"><?=date("Y")?></option>
							<option value="<?=date("Y")+1?>"><?=date("Y")+1?></option>
						</select>
						<input type="text" id="specify_time" name="specify_time" />
						<!-- <select name="specify_time" id="specify_time">
							<option value="8:00">8am</option>
							<option value="9:00">9am</option>
							<option value="10:00">10am</option>
							<option value="11:00">11am</option>
							<option value="12:00">12am</option>
							<option value="113:00">1pm</option>
							<option value="14:00">2pm</option>
							<option value="15:00">3pm</option>
							<option value="16:00">4pm</option>
							<option value="17:00">5pm</option>
							<option value="20:55">8.55pm</option>
							<option value="21:00">9pm</option>
						</select -->		
					</span>
				</td>
			</tr>
			<tr>
				<th class="fixed_width">OK?</th>
				<td>
				<!--input type="submit" value="Send" id="send_sms_submit" /><input type="button" value="Preview" id="preview_sms" /-->
				<div class="buttons">
					<a href="#" class="btn btn-successt" id="send_sms_submit">Send</a>
					<a href="#" class="btn" id="preview_sms">Preview</a>
				</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
			</tr>
		</table>
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
	