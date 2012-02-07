<form method="post" action="/user/preferences" id="preferences_form">
<h3>Timezone</h3>
<input type="hidden" name="preferences_submit" value="yes" />
<p>The timezone you select affects the time your messages are sent, so please choose carefully!</p>
<?=timezone_menu($preferences['timezone'])?>
<br />
<h3>Notifications</h3>
<input type="checkbox" name="notify_on_reply" id="notify_on_reply" <?=$preferences['notify_on_reply'] == 1 ? "Checked" : ""?> /><label for="notify_on_reply">Notify me by email when somebody replies</label><br />
<input type="checkbox" name="use_popup_notifications" id="use_popup_notifications" <?=$preferences['use_popup_notifications'] == 1 ? "Checked" : ""?> /><label for="use_popup_notifications">Also popup notifications for SMS sent & received when I'm logged in</label>

<h3>Budget cap</h3>
<p>The budget cap is used to help you keep track of your spending each month.<br />
Require password once I've spent $<input type="text" name="budget_cap" id="budget_cap" value="<?=$preferences['budget_cap']?>" /> in 30 days.</p>

<div class="buttons">
					<a href="#" class="button save-big" onClick="document.forms['preferences_form'].submit();">Save Preferences</a>
				</div>
</form>
<h3 style="clear: both;">Anything else?</h3>
<ul>
	<li><a href="/user/change_password">Change Password</a></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
</ul>
