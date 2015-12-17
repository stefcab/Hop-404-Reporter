<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="box">
	<div class="tbl-ctrls">
		<h1><?=lang('settings_pagetitle')?></h1>
		
		<?=form_open($action_url, '', $form_hidden)?>
		<fieldset class="col-group">
			<div class="setting-txt col w-8">
				<h3>Is Hop 404 reporter on?</h3>
				<em>If Hop 404 reporter is off, no URL will be recorded, no emails will be sent.</em>
			</div>
			<div class="setting-field col w-8 last">
				<label class="choice mr <?php if($settings["enabled"]=='y') echo 'chosen';?> yes">
					<input type="radio" <?php if($settings["enabled"]=='y') echo 'checked="checked"';?> name="enabled" value="y" > yes
				</label>
				<label class="choice <?php if($settings["enabled"]=='n') echo 'chosen';?> no">
					<input type="radio" name="enabled" value="n" <?php if($settings["enabled"]!='y') echo 'checked="checked"';?>> no
				</label>
			</div>
		</fieldset>
		<fieldset class="col-group">
			<div class="setting-txt col w-8">
				<h3>Do we send email notifications ?</h3>
				<em>If not, the 404 urls will be recorded but no email will be send.</em>
			</div>
			<div class="setting-field col w-8 last">
				<label class="choice mr <?php if($settings["send_email_notifications"]=='y') echo 'chosen';?> yes">
					<input type="radio" name="send_email_notifications" value="y" <?php if($settings["send_email_notifications"]=='y') echo 'checked="checked"';?> > yes
				</label>
				<label class="choice <?php if($settings["send_email_notifications"]=='n') echo 'chosen';?> no">
					<input type="radio" name="send_email_notifications" value="n" <?php if($settings["send_email_notifications"]!='y') echo 'checked="checked"';?> > no
				</label>
			</div>
		</fieldset>
		<fieldset class="col-group">
			<div class="setting-txt col w-8">
				<h3>Is Hop 404 reporter referrer tracking on ?</h3>
				<em>Referrer tracking will save the referrer URL for each 404 URL occurring.</em>
			</div>
			<div class="setting-field col w-8 last">
				<label class="choice mr <?php if($settings["referrer_tracking"]=='y') echo 'chosen';?> yes">
					<input type="radio" name="referrer_tracking" value="y" <?php if($settings["referrer_tracking"]=='y') echo 'checked="checked"';?> > yes
				</label>
				<label class="choice <?php if($settings["referrer_tracking"]=='n') echo 'chosen';?> no">
					<input type="radio" name="referrer_tracking" value="n" <?php if($settings["referrer_tracking"]=='n') echo 'checked="checked"';?> > no
				</label>
			</div>
		</fieldset>
		<fieldset class="col-group required <?php if (isset($form_error_email_address_sender)) echo 'invalid';?>">
			<div class="setting-txt col w-8">
				<h3>Sender email address</h3>
				<em>This email address will be used as the sender for each notification sent.</em>
			</div>
			<div class="setting-field col w-8 last">
				<input type="text" name="email_address_sender" id="email_address_sender" value="<?=$settings['email_address_sender']?>">
				<?php if (isset($form_error_email_address_sender)) echo '<em>'.$form_error_email_address_sender.'</em>';?>
			</div>
		</fieldset>
		<fieldset class="col-group required <?php if (isset($form_error_email_notification_subject)) echo 'invalid';?>">
			<div class="setting-txt col w-8">
				<h3>Notification email subject</h3>
				<em>Subject of the email sent when a 404 occurs.</em>
			</div>
			<div class="setting-field col w-8 last">
				<input type="text" name="email_notification_subject" id="email_notification_subject" value="<?=$settings['email_notification_subject']?>">
				<?php if (isset($form_error_email_notification_subject)) echo '<em>'.$form_error_email_notification_subject.'</em>';?>
			</div>
		</fieldset>
		<fieldset class="col-group required <?php if (isset($form_error_email_template)) echo'invalid';?>">
			<div class="setting-txt col w-8">
				<h3>Notification email template</h3>
				<em>You can modify the email people will receive when a 404 occurs.<br> Tags available {site_url}, {404_url}, {referrer_url}, {404_date}, {404_time}</em>
			</div>
			<div class="setting-field col w-8 last">
				<textarea name="404_email_template" value="Lucis Trust" id="404_email_template" class="input fullfield" rows="7"><?=$settings["email_template"]?></textarea>
				<?php if (isset($form_error_email_template)) echo '<em>'.$form_error_email_template.'</em>'; ?>
			</div>
		</fieldset>

		<fieldset class="form-ctrls">
			<?=form_submit(array('name' => 'submit', 'value' => lang('settings_save'), 'class' => 'btn submit'))?>
		</fieldset>
		
		<?=form_close()?>
	</div>
</div>
