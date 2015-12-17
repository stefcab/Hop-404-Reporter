<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="box">
	<div class="tbl-ctrls">
		<h1><?=lang('email_notif_page_title')?></h1>

		<?=form_open($action_url, '', $form_hidden)?>
		<fieldset class="col-group required <?php if (isset($form_error_email)) echo 'invalid';?>">
			<div class="setting-txt col w-8">
				<h3><?=lang('email_notif_email_label')?></h3>
				<em><?=lang('email_notif_email_desc')?></em>
			</div>
			<div class="setting-field col w-8 last">
				<input type="text" name="email_address" checked="checked" id="email_address" <?php if (isset($form_value_email)) echo 'value="'.$form_value_email.'"';?>>
				<?php if (isset($form_error_email)) echo '<em>'.$form_error_email.'</em>';?>
			</div>
		</fieldset>
		<fieldset class="col-group required <?php if (isset($form_error_url_filter)) echo 'invalid';?>">
			<div class="setting-txt col w-8">
				<h3><?=lang('email_notif_url_label')?></h3>
				<em><?=lang('email_notif_url_desc')?></em>
			</div>
			<div class="setting-field col w-8 last">
				<input type="text" name="url_filter" checked="checked" id="email_address" <?php if (isset($form_value_url_filter)) echo 'value="'.$form_value_url_filter.'"';?>>
				<?php if (isset($form_error_url_filter)) echo '<em>'.$form_error_url_filter.'</em>';?>
			</div>
		</fieldset>
		<fieldset class="col-group required <?php if (isset($form_error_interval)) echo 'invalid';?>">
			<div class="setting-txt col w-8">
				<h3><?=lang('email_notif_interval_label')?></h3>
				<em><?=lang('email_notif_interval_desc')?></em>
			</div>
			<div class="setting-field col w-8 last">
				<select name="notification_interval" id="notification_interval">
					<option value="once"><?=lang('email_notif_interval_once')?></option>
					<option value="always"><?=lang('email_notif_interval_always')?></option>
				</select>&nbsp;
				<?php if (isset($form_error_interval)) echo '<em>'.$form_error_interval.'</em>';?>
			</div>
		</fieldset>

		<fieldset class="form-ctrls">
			<?=form_submit(array('name' => 'submit', 'value' => lang('email_notif_submit'), 'class' => 'btn submit'))?>
		</fieldset>
		<?=form_close()?>
	</div>
</div>
