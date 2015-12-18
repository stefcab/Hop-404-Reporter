<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="box">
	<div class="tbl-ctrls">
		<h1><?=lang('404_url_list_title')?></h1>

		<?=lang('404_url_list_description')?>

		<?= $filters ?>

		<?=form_open($action_url, array('name' => 'target', 'id' => 'target'))?>
		<?php
		// echo $table_html;
		// echo $pagination_html;

		//$this->view('_shared/table', $table);
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
	'name'      => 'modal-confirm-delete',
	'form_url'	=> ee('CP/URL')->make('addons/settings/hop_404_reporter/modify_urls'),
	'hidden'	=> array(
		'bulk_action'	=> 'delete'
	)
);

$modal = $this->make('ee:_shared/modal_confirm_remove')->render($modal_vars);
ee('CP/Modal')->addModal('delete', $modal);
?>
