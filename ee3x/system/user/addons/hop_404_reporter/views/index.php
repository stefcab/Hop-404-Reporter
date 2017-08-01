<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="box">
	<div class="tbl-ctrls">
		<h1><?=lang('404_url_list_title')?></h1>

		<?= ee('CP/Alert')->getAllInlines() ?>

		<?=lang('404_url_list_description')?>

		<?=form_open($search_url, array('name' => 'search', 'id' => 'search'))?>
		<fieldset class="tbl-search right">
			<input type="text" name="search" value="<?=$search_keywords?>" />
			<input class="btn submit" value="<?=lang('search')?>" type="submit">
		</fieldset>
		<?=form_close()?>

		<?=form_open($action_url, array('name' => 'target', 'id' => 'target'))?>

		<?= $filters ?>

		<?php

		$this->embed('ee:_shared/table', $table);
		echo $pagination;
		?>

		<fieldset class="tbl-bulk-act hidden">
			<select name="bulk_action">
				<option>
					<?=lang('--with_selected--')?>
				</option>
				<option value="delete" data-confirm-trigger="selected" rel="modal-confirm-delete">
					<?=lang('delete_selected')?>
				</option>
			</select>
			<input class="btn submit" data-conditional-modal="confirm-trigger" type="submit" value="<?=lang('submit')?>">
		</fieldset>

		<?=form_close()?>
	</div>
</div>

<?php
$modal_vars = array(
	'name'		=> 'modal-confirm-delete',
	'form_url'	=> ee('CP/URL')->make('addons/settings/hop_404_reporter/modify_urls'),
	'hidden'	=> array(
		'bulk_action'	=> 'delete'
	)
);
ee()->javascript->set_global(
	'lang.remove_confirm',
	'URL: <b>### URLs</b>'
);
$modal = $this->make('ee:_shared/modal_confirm_remove')->render($modal_vars);
ee('CP/Modal')->addModal('delete', $modal);
?>
