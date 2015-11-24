<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="box">
	<div class="tbl-ctrls">
		<fieldset class="tbl-search right">
			<a class="btn tn action" href="<?=ee('CP/URL')->make('addons/settings/hop_404_reporter/settings')?>"><?=lang('settings')?></a>
		</fieldset>
		<h1><?=lang('404_url_title')?></h1>

		<?=lang('404_url_lis_description')?>

		<?=form_open(ee('CP/URL')->make('addons/settings/hop_404_reporter'), 'id="url_filter"')?>
			<fieldset class="shun">
				<legend><?=lang('filter_urls')?></legend>
				<div class="group">
					<label for="keywords" class="js_hide"><?=lang('keywords')?> </label><?=form_input('search', $filter_keywords, 'class="field shun" placeholder="'.lang('keywords').'"')?><br />

					<?=form_dropdown('referrer_url_f', $filter_referrer_url_options, $filter_referrer_url_selected, 'id="referrer_url_f"').NBS.NBS?>
					<?=form_dropdown('date_range', $filter_date_range_options, $filter_date_range_selected, 'id="date_range"').NBS.NBS?>
					<?=form_submit('submit', lang('search'), 'class="submit" id="search_button"').NBS.NBS?>

				</div>
			</fieldset>
		<?=form_close()?>

		<?=form_open($action_url, array('name' => 'target', 'id' => 'target'))?>
		<?php
		// echo $table_html;
		// echo $pagination_html;

		$this->view('_shared/table', $table);
		print_r( $pagination);
		?>

		<fieldset class="tbl-bulk-act hidden">
			<select name="bulk_action">
				<option><?=lang('--with_selected--')?></option>
				<option value="delete" data-confirm-trigger="selected" rel="modal-confirm-remove"><?=lang('delete_selected')?></option>
			</select>
			<input class="btn submit" data-conditional-modal="confirm-trigger" type="submit" value="<?=lang('submit')?>">
		</fieldset>

		<?=form_close()?>
	</div>
</div>
