<header class="" id="overview">
	<noscript>
		<span>PyroCMS requires that JavaScript be turned on for many of the functions to work correctly. Please turn JavaScript on and reload the page.</span>
	</noscript>
	
	<div class="navbar navbar-pyro navbar-static-top">
		<div class="navbar-inner">
			<div class="container">
				<!--
<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
-->			<a class="plus visible-phone btn btn-navbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<!-- <a class="plus visible-phone"></a> -->
				<?php echo anchor('admin', $this->settings->site_name, 'class="brand"'); ?>
				<div class="nav-collapse collapse">
					<nav>
						<?php file_partial('navigation'); ?>
					</nav>
					<form class="navbar-search pull-right">
						<input type="text" class="search-query" placeholder="Search">
					</form>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<div class="subbar">
		<?php file_partial('subbar'); ?>
	</div>
	
	<!-- <?php if ( ! empty($module_details['sections'])) file_partial('sections'); ?> -->
</header>