<div class="span8 offset2">
	<form method="post" id="register_form" class="form-horizontal" action="/user/register/">
        <fieldset>
          <legend>Register</legend>
			<div class="control-group">
				<label for="emailaddressr" class="control-label">Email Address</label>
				<div class="controls">
					<input type="text" name="emailaddressr" id="emailaddressr" class="span3 input-xlarge" />
				</div>		
			</div>
			<div class="control-group">
				<label for="passwordr" class="control-label">Password</label>
				<div class="controls">
					<input type="password" name="passwordr" id="passwordr" class="span3" />
				</div>	
			</div>
			<div class="control-group">
				<label for="passwordconfirmr" class="control-label">Confirm Password</label>
				<div class="controls">
					<input type="password" name="passwordconfirmr" id="passwordconfirmr" class="span3" />
				</div>	
			</div>
			<div class="control-group">
				<label for="phoner" class="control-label">Mobile Phone #</label>
				<div class="controls">
					<input type="text" name="phoner" id="phoner" class="span3" />
				</div>	
			</div>
			<div class="control-group">
				<label for="phoner" class="control-label">Time Zone</label>
				<div class="controls">
					<?=timezone_menu('UP10','span3')?>
				</div>	
			</div>
			<div class="form-actions">
				<div class="buttons">
					<a href="#" class="btn btn-large btn-primary" id="submit_create_button"><i class="icon-ok icon-white"></i> Register</a>
				</div>	
			</div>
		</fieldset>
	</form>
</div>
