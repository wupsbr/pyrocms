<!doctype html>

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js" lang="en"> 		   <![endif]-->

<head>
	<meta charset="utf-8">

	<!-- You can use .htaccess and remove these lines to avoid edge case issues. -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $template['title'].' - '.lang('cp_admin_title');?></title>

	<base href="<?php echo base_url(); ?>" />

	<!-- Mobile viewport optimized -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

	<!-- CSS. No need to specify the media attribute unless specifically targeting a media type, leaving blank implies media=all -->
	<?php echo Asset::css('main/style.css'); ?>
	<?php echo Asset::css('main/responsive.css'); ?>
	<?php echo Asset::css('main/animate.css'); ?>
	<!-- End CSS-->

	<!-- Load up some favicons -->
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-precomposed.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-57x57-precomposed.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-114x114-precomposed.png">

	<!-- metadata needs to load before some stuff -->
	<?php file_partial('metadata'); ?>

</head>

<body>
	<div id="viewport">
		<?php file_partial('aside'); ?>
		<div id="page">
			<div id="wrapper">
				<div id="close-mask"></div>
				<?php file_partial('header'); ?>
				<section id="content">
					<div class="container">
						<div id="content-body">
							<div class="row-fluid">
								<?php file_partial('notices'); ?>
								<?php echo $template['body']; ?>
							</div>
						</div>
					</div>
				</section>
				<div id="push"></div>
			</div><!-- !end wrapper -->
			<footer>
				<div class="container">
					<div class="row-fluid">
						<div class="span8">
							<p>Copyright &copy; 2009 - <?php echo date('Y'); ?> PyroCMS &nbsp; -- &nbsp; Version <?php echo CMS_VERSION.' '.CMS_EDITION; ?> &nbsp; -- &nbsp; Rendered in {elapsed_time} sec. using {memory_usage}.</p>
						</div>
						<div class="span4">
							<ul id="lang">
								<form action="<?php echo current_url(); ?>" id="change_language" method="get">
									<select class="chzn" name="lang" onchange="this.form.submit();">
										<?php foreach($this->config->item('supported_languages') as $key => $lang): ?>
										<option value="<?php echo $key; ?>" <?php echo CURRENT_LANGUAGE == $key ? 'selected="selected"' : ''; ?>>
												<?php echo $lang['name']; ?>
											</option>
									<?php endforeach; ?>
									</select>
								</form>
							</ul>
						</div>
					</div>
				</div>
			</footer>
			<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6. chromium.org/developers/how-tos/chrome-frame-getting-started -->
			<!--[if lt IE 7 ]>
			<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
			<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
			<![endif]-->
		</div><!-- !end page -->
	</div><!-- !end viewport -->
	<div id="ajax-modal" class="modal hide fade" tabindex="-1"></div>

</body>
</html>