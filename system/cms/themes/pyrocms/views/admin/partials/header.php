<noscript>
	<span>PyroCMS requires that JavaScript be turned on for many of the functions to work correctly. Please turn JavaScript on and reload the page.</span>
</noscript>

<div class="navbar navbar-inverse navbar-static-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<?php echo anchor('admin', $this->settings->site_name, 'class="brand"'); ?>
			<div class="nav-collapse collapse">
				<nav>
					<?php file_partial('navigation'); ?>
				</nav>
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>
<div class="subbar">
	<div class="container">
		<h2><?php echo $module_details['name'] ? anchor('admin/'.$module_details['slug'], $module_details['name']) : lang('global:dashboard'); ?></h2>
	
		<small>
			<?php if ( $this->uri->segment(2) ) { echo '&nbsp; | &nbsp;'; } ?>
			<?php echo $module_details['description'] ? $module_details['description'] : ''; ?>
		</small>

		<?php file_partial('shortcuts'); ?>

	</div>
</div>

<?php if ( ! empty($module_details['sections'])) file_partial('sections'); ?>