<fieldset id="filters">
	<legend><?php echo lang('global:filters'); ?></legend>
	
	<?php echo form_open(''); ?>
		<?php echo form_hidden('f_module', $module_details['slug']); ?>
		<ul>  
			<li class="span3">
        		<?php echo lang('blog_status_label', 'f_status'); ?>
        		<?php echo form_dropdown('f_status', array(0 => lang('global:select-all'), 'draft'=>lang('blog_draft_label'), 'live'=>lang('blog_live_label'))); ?>
    		</li>
			<li class="span3">
        		<?php echo lang('blog_category_label', 'f_category'); ?>
       			<?php echo form_dropdown('f_category', array(0 => lang('global:select-all')) + $categories); ?>
    		</li>
			<li class="span3"><?php echo form_input('f_keywords'); ?></li>
			<li class="span3" style="font-size:13px;"><?php echo anchor(current_url() . '#', lang('buttons.cancel'), 'class="pullright btn btn-danger"'); ?></li>
		</ul>
	<?php echo form_close(); ?>
</fieldset>