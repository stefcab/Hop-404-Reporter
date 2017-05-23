<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h3><?=lang('email_page_title') ?></h3>

<?=lang('email_page_description')?>

<div class="action-buttons">
<span class="button"><a title="<?=lang('email_add_notification') ?>" class="submit" href="<?=$add_email_notif_action?>"><?=lang('email_add_notification') ?></a></span>&nbsp;&nbsp;
</div>
<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter'.AMP.'method=display_emails', 'id="url_filter"')?>
	<fieldset class="shun">
		<legend><?=lang('filter_email_notifications')?></legend>
		<div class="group">
			<label for="keywords" class="js_hide"><?=lang('keywords')?> </label><?=form_input('keywords', $filter_keywords, 'class="field shun" placeholder="'.lang('keywords').'"')?><br />
			<?=form_dropdown('interval_f', $filter_interval_options, $filter_interval_selected, 'id="interval_f"').NBS.NBS?>
			<?=form_submit('submit', lang('search'), 'class="submit" id="search_button"').NBS.NBS?>
			<img src="<?=$cp_theme_url?>images/indicator.gif" class="searchIndicator" alt="Search Indicator" style="margin-bottom: -5px; visibility: hidden;" width="16" height="16" />
		</div>
	</fieldset>
<?=form_close()?>
<?=form_open($action_url, array('name' => 'target', 'id' => 'target'))?>
<?php
echo $table_html;
echo $pagination_html;
?>
<div class="action-buttons">
<span class="button" style="float:left;"><a title="<?=lang('email_add_notification') ?>" class="submit" href="<?=$add_email_notif_action?>"><?=lang('email_add_notification') ?></a></span>&nbsp;&nbsp;
</div>
<div class="tableSubmit">
	<?=form_submit('submit', lang('submit'), 'class="submit"').NBS.NBS?>
	<?=form_dropdown('action', $options, '', 'id="url_action"').NBS.NBS?>
</div>
<?=form_close()?>