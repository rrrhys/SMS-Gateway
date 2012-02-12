<div class="span8 offset2">
	<form method="post" id="login_form" class="form-horizontal">
        <fieldset>
          <legend>Log-in</legend>
			<div class="control-group">
				<label for="emailaddress" class="control-label">Email Address</label>
				<div class="controls">
					<input type="text" name="emailaddress" id="emailaddress" class="span3 input-xlarge" />
				</div>		
			</div>
			<div class="control-group">
				<label for="password" class="control-label">Password</label>
				<div class="controls">
					<input type="password" name="password" id="password" class="span3" />
				</div>	
			</div>

			<div class="form-actions">
				<div class="buttons">
					<a href="#" class="btn btn-large btn-primary" onClick="document.forms['login_form'].submit();"><i class="icon-ok icon-white"></i> Login</a>
				</div>	
			</div>
		</fieldset>

	</form>
</div>