<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h3><?=lang('email_notif_page_title')?></h3>

<?=form_open($action_url, '', $form_hidden)?>

<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr class="even">
			<th style="width:50%;" class=""><?=lang('preference')?></th><th><?=lang('setting')?></th>
		</tr>
	</thead>
	<tbody>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="email_address"><?=lang('email_notif_email_label')?></label></strong><div class="subtext"><?=lang('email_notif_email_desc')?></div>
			</td>
			<td>
				<input type="text" name="email_address" checked="checked" id="email_address" <?php if (isset($form_value_email)) echo 'value="'.$form_value_email.'"';?>>&nbsp;
				<?php if (isset($form_error_email)) echo $form_error_email;?>
			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="url_filter"><?=lang('email_notif_url_label')?></label></strong><div class="subtext"><?=lang('email_notif_url_desc')?></div>
			</td>
			<td>
				<input type="text" name="url_filter" checked="checked" id="url_filter" <?php if (isset($form_value_url_filter)) echo 'value="'.$form_value_url_filter.'"';?>>&nbsp;
				<?php if (isset($form_error_url_filter)) echo $form_error_url_filter;?>
			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="notification_interval"><?=lang('email_notif_interval_label')?></label></strong>
				<div class="subtext"><?=lang('email_notif_interval_desc')?></div>
			</td>
			<td>
				<select name="notification_interval" id="notification_interval">
					<option value="once"><?=lang('email_notif_interval_once')?></option>
					<option value="always"><?=lang('email_notif_interval_always')?></option>
				</select>&nbsp;
				<?php if (isset($form_error_interval)) echo $form_error_interval;?>
			</td>
		</tr>
	</tbody>
</table>

<?=form_submit(array('name' => 'submit', 'value' => lang('email_notif_submit'), 'class' => 'submit'))?>
<?=form_close()?>