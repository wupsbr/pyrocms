<div class="container">
			<h2><?php echo $module_details['name'] ? anchor('admin/'.$module_details['slug'], $module_details['name']) : lang('global:dashboard'); ?></h2>
			
			<?php if ( $this->uri->segment(2) ) { echo '<span class="divider-vertical"></span>'; } ?>
			<small class="">
				<?php echo $module_details['description'] ? $module_details['description'] : ''; ?>
			</small>
			<div class="btn-group">
				<?php foreach ($module_details['sections'] as $name => $section): ?>
				<?php if(isset($section['name']) && isset($section['uri'])): ?>
				<button class="<?php if ($name === $active_section) echo 'current' ?>">
				<?php echo anchor($section['uri'], (lang($section['name']) ? lang($section['name']) : $section['name'])); ?>
				<?php if ($name === $active_section): ?>
				<?php endif; ?>
				</button>
				<?php endif; ?>
				<?php endforeach; ?>
  		</div>
	
			<?php file_partial('shortcuts'); ?>
	
		</div>