
<ul class="bullets" style="margin-bottom:15px">
	<li style="width:25%; float: left;"><a href="<?=BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=blacklist'.AMP.'method=view_blacklist'?>"><?=lang('ref_view_blacklist')?></a></li>
	<li>
		<a href="<?=BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=blacklist'.AMP.'method=ee_blacklist'?>"><?=lang('pmachine_blacklist')?></a>
	</li>
	<li style="width:25%; float: left;"><a href="<?=BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=blacklist'.AMP.'method=view_whitelist'?>"><?=lang('ref_view_whitelist')?></a></li>
	<li>
		<a href="<?=BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=blacklist'.AMP.'method=ee_whitelist'?>"><?=lang('pmachine_whitelist')?></a>
	</li>
</ul>

<?php if ($allow_write_htaccess):?>

	<h3><?=lang('write_htaccess_file')?></h3>

	<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=blacklist'.AMP.'method=save_htaccess_path')?>

	<p>
		<?=lang('htaccess_server_path', 'htaccess_path')?> 
		<?=form_input('htaccess_path', set_value('htaccess_path', $htaccess_path), 'size="35"')?>
		<?=form_error('htaccess_path')?>
	</p>

	<p>
		<?=form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'))?>
	</p>

	<?=form_close()?>

<?php endif;?>