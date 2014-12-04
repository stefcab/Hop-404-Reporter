<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h3><?=lang('404_url_title')?></h3>

<?=lang('404_url_lis_description')?>

<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=hop_404_reporter', 'id="url_filter"')?>
	<fieldset class="shun">
		<legend><?=lang('filter_urls')?></legend>
		<div class="group">
			<label for="keywords" class="js_hide"><?=lang('keywords')?> </label><?=form_input('keywords', $filter_keywords, 'class="field shun" placeholder="'.lang('keywords').'"')?><br />

			<?=form_dropdown('referrer_url_f', $filter_referrer_url_options, $filter_referrer_url_selected, 'id="referrer_url_f"').NBS.NBS?>
			<?=form_dropdown('date_range', $filter_date_range_options, $filter_date_range_selected, 'id="date_range"').NBS.NBS?>
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
<div class="tableSubmit">
	<?=form_submit('submit', lang('submit'), 'class="submit"').NBS.NBS?>
	<?=form_dropdown('action', $options, '', 'id="url_action"').NBS.NBS?>
</div>
<?=form_close()?>