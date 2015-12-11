<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="box">
	<div class="tbl-ctrls">
		<fieldset class="tbl-search right">
			<a class="btn tn action" href="<?=ee('CP/URL')->make('addons/settings/hop_404_reporter')?>"><?=lang('nav_index')?></a>
			<a class="btn tn action" href="<?=ee('CP/URL')->make('addons/settings/hop_404_reporter/display_emails')?>"><?=lang('nav_emails')?></a>
			<a class="btn tn action" href="<?=ee('CP/URL')->make('addons/settings/hop_404_reporter/settings')?>"><?=lang('nav_settings')?></a>
			<a class="btn tn action" href="<?=ee('CP/URL')->make('addons/settings/hop_404_reporter/support')?>"><?=lang('nav_support')?></a>
		</fieldset>
		<h1><?=lang('settings_pagetitle')?></h1>
		
		<?=form_open($action_url, '', $form_hidden)?>
		<div class="tbl-wrap">
			<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr class="even">
						<th style="width:50%;" class="">Preference</th><th>Setting</th>
					</tr>
				</thead>
				<tbody>
					<tr class="<?php echo alternator('even', 'odd');?>">
						<td>
							<strong><label for="enabled">Is Hop 404 reporter on?</label></strong>
							<div class="subtext">If Hop 404 reporter is off, no URL will be recorded, no emails will be sent.</div>
						</td>
						<td>
							<input type="radio" name="enabled" value="y" <?php if($settings["enabled"]=='y') echo 'checked="checked"';?> id="enabled_y" label="yes">&nbsp;
							<label for="enabled_y">Yes</label>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="enabled" value="n" <?php if($settings["enabled"]!='y') echo 'checked="checked"';?> id="enabled_n" label="no">&nbsp;
							<label for="enabled_n">No</label>
							&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
					</tr>
					<tr class="<?php echo alternator('even', 'odd');?>">
						<td>
							<strong><label for="enabled">Do we send email notifications ?</label></strong>
							<div class="subtext">If not, the 404 urls will be recorded but no email will be send.</div>
						</td>
						<td>
							<input type="radio" name="send_email_notifications" value="y" <?php if($settings["send_email_notifications"]=='y') echo 'checked="checked"';?> id="send_email_notifications_y" label="yes">&nbsp;
							<label for="send_email_notifications_y">Yes</label>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="send_email_notifications" value="n" <?php if($settings["send_email_notifications"]!='y') echo 'checked="checked"';?> id="send_email_notifications_n" label="no">&nbsp;
							<label for="send_email_notifications_n">No</label>
							&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
					</tr>
					<tr class="<?php echo alternator('even', 'odd');?>">
						<td>
							<strong><label for="referrer_tracking">Is Hop 404 reporter referrer tracking on ?</label></strong>
							<div class="subtext">Referrer tracking will save the referrer URL for each 404 URL occurring.</div>
						</td>
						<td>
							<input type="radio" name="referrer_tracking" value="y" <?php if($settings["referrer_tracking"]=='y') echo 'checked="checked"';?> id="referrer_tracking_y" label="yes">&nbsp;
							<label for="referrer_tracking_y">Yes</label>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="referrer_tracking" value="n" <?php if($settings["referrer_tracking"]!='y') echo 'checked="checked"';?> id="referrer_tracking_n" label="no">&nbsp;
							<label for="referrer_tracking_n">No</label>
							&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
					</tr>
					<tr class="<?php echo alternator('even', 'odd');?>">
						<td>
							<strong><label for="email_address_sender">Sender email address</label></strong><div class="subtext">This email address will be used as the sender for each notification sent.</div>
						</td>
						<td>
							<input type="text" name="email_address_sender" id="email_address_sender" value="<?=$settings['email_address_sender']?>">&nbsp;
							<?php if (isset($form_error_email_address_sender)) echo $form_error_email_address_sender;?>
						</td>
					</tr>
					<tr class="<?php echo alternator('even', 'odd');?>">
						<td>
							<strong><label for="email_notification_subject">Notification email subject</label></strong><div class="subtext">Subject of the email sent when a 404 occurs.</div>
						</td>
						<td>
							<input type="text" name="email_notification_subject" id="email_notification_subject" value="<?=$settings['email_notification_subject']?>">&nbsp;
							<?php if (isset($form_error_email_notification_subject)) echo $form_error_email_notification_subject;?>
						</td>
					</tr>
					<tr class="<?php echo alternator('even', 'odd');?>">
						<td>
							<strong><label for="404_email_template">Notification email template</label></strong>
							<div class="subtext">You can modify the email people will receive when a 404 occurs.<br> Tags available {site_url}, {404_url}, {referrer_url}, {404_date}, {404_time}</div>
						</td>
						<td>
							<textarea type="text" name="404_email_template" value="Lucis Trust" id="404_email_template" class="input fullfield" rows="7"><?=$settings["email_template"]?></textarea>&nbsp;
							<?php if (isset($form_error_email_template)) echo $form_error_email_template; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<fieldset class="form-ctrls">
			<?=form_submit(array('name' => 'submit', 'value' => lang('settings_save'), 'class' => 'btn submit'))?>
		</fieldset>
		
		<?=form_close()?>
	</div>
</div>