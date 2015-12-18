<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="box">
	<div class="tbl-ctrls">
		<fieldset class="tbl-search right">
			<a class="btn tn action" href="<?= ee('CP/URL')->make('addons/settings/hop_404_reporter/add_email')?>"><?=lang('email_add_notification') ?></a>
		</fieldset>
		<h1><?=lang('email_page_title')?></h1>

		<?=lang('email_page_description')?>
		
		<?= $filters ?>

		<?=form_open($action_url, array('name' => 'target', 'id' => 'target'))?>
		<?php
		$this->embed('ee:_shared/table', $table);
		print_r($pagination);
		?>
		<span class="button" style="float:left;"><a title="<?=lang('email_add_notification') ?>" class="submit" href=""></a></span>&nbsp;&nbsp;

		<fieldset class="tbl-bulk-act hidden">
			<select name="bulk_action">
				<option><?=lang('--with_selected--')?></option>
				<option value="reset"><?=lang('email_reset_selected')?></option>
				<option value="delete" data-confirm-trigger="selected" rel="modal-confirm-remove"><?=lang('delete_selected')?></option>
			</select>
			<input class="btn submit" data-conditional-modal="confirm-trigger" type="submit" value="<?=lang('submit')?>">
		</fieldset>
		<?=form_close()?>
	</div>
</div>
