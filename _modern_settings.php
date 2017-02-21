<?php
/**
 * Function of modern theme
 *
 * XXAdd login link to footer.
 *
 * @author  Prakai Nadee <prakai@rmuti.ac.th>
 * @version 1.0.0
 */

defined('INC_ROOT') || die('Direct access is not allowed.');

wCMS::addListener('js', 'loadModernJS');
wCMS::addListener('css', 'loadModernCSS');
wCMS::addListener('settings', 'displayModernSettings');

function loadModernJS($args) {
	$script = <<<'EOT'
<script src="plugins/_modern_settings/js/settings.js"></script>
EOT;
	array_push($args[0], $script);
	return $args;
}

function loadModernCSS($args) {
	$script = <<<'EOT'
<link rel="stylesheet" href="plugins/_modern_settings/css/settings.css" type="text/css" media="screen" charset
="utf-8">
EOT;
	array_push($args[0], $script);
	return $args;
}

function displayModernSettings ($args) {
	if ( ! wCMS::$loggedIn) return $args;

	$settingNav = <<<'EOT'
	<!-- Settings Navigation Bar -->
	<nav class="navbar navbar-default navbar-fixed-top navbar-settings">
	<div class="container">
		<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-settings" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<span class="title">Settings</span>
		</div>
		<div id="navbar-settings" class="navbar-collapse collapse">
		<ul class="nav navbar-nav navbar-right">
			<li><a id="pageSettings" class="active" href="#" onclick="openPanel('page')">Page</a></li>
			<li><a id="pluginsSettings" href="#" onclick="openPanel('plugins')">Plugins</a></li>
			<li><a id="siteSettings" href="#" onclick="openPanel('site')">Site</a></li>
		</ul>
		</div><!--/.nav-collapse -->
	</div>
	</nav>
	<!-- /Settings Navigation Bar -->
	<div id="save"><h2>Saving...</h2></div>
EOT;
	$settingNav.='<div class="settings"></div>';

	$pagePanel = '
<div id="pagePanel" class="overlay">
	<!-- Button to close the overlay navigation -->
	<a href="javascript:void(0)" class="closebtn" onclick="closePanel(\'page\')">&times;</a>
	<!-- Overlay content -->
	<div class="overlay-content">
		<div class="container-fluid">
			<div class="col-xs-12 col-sm-8 col-sm-offset-2">
				<div class="text-left">
';
		if (!wCMS::$newPage) {
			foreach (['title', 'description', 'keywords'] as $key)
				$pagePanel .= '
					<div>'.(($key == 'title') ? '
						<label >Page title, description and keywords</label>' : '').'
						<span id="'.$key.'" class="change editText">'.(@wCMS::getPage(wCMS::$currentPage)->$key != '' ? @wCMS::getPage(wCMS::$currentPage)->$key : 'Page '.$key.', unique for each page').'</span>
					</div>';
		}
	$pagePanel .= '
					<div class="marginTop20"></div>
					<a href="'.wCMS::url('?delete='.wCMS::$currentPage).'" class="btn btn-danger'.(wCMS::$newPage ? ' hide' : '').'" onclick="return confirm(\'Really delete page?\')">Delete current page ('.wCMS::$currentPage.')</a>
				</div>
			</div>
		</div>
	</div>
	<!-- /Overlay content -->
</div>
';

	$sitePanel = '
<div id="sitePanel" class="overlay">
	<!-- Button to close the overlay navigation -->
	<a href="javascript:void(0)" class="closebtn" onclick="closePanel(\'site\')">&times;</a>
	<!-- Overlay content -->
	<div class="overlay-content">
		<div class="container-fluid">
			<div class="col-xs-12 col-sm-8 col-sm-offset-2">
				<div class="text-left">
					<div class="form-group">
						<label for="themeSelect">Themes</label>
						<div class="change">
						<select id="themeSelect" class="form-control" name="themeSelect" onchange="fieldSave(\'theme\',this.value);">';
		foreach (glob(constant('INC_ROOT').'/themes/*', constant('GLOB_ONLYDIR')) as $dir) $sitePanel .= '<option value="'.basename($dir).'"'.(basename($dir) == wCMS::getConfig('theme') ? ' selected' : '').'>'.basename($dir).' theme'.'</option>';
	$sitePanel .= '
						</select>
						</div>
					</div>
					<div class="marginTop20">
						<label for="siteTitle">Website title</label>
						<span id="siteTitle" class="change editText">'.(wCMS::getConfig('siteTitle') != '' ? wCMS::getConfig('siteTitle') : '').'</span>
					</div>
					<div class="marginTop20">
						<label for="copyright">Web footer</label>
						<span id="copyright" class="change editText">'.(wCMS::getConfig('copyright') != '' ? wCMS::getConfig('copyright') : '').'</span>
					</div>
					<div class="marginTop20">
						<label for="menuItems" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Enter a new page name in a new line">Menu items</label>
						<span id="menuItems" class="change editText">
';
		if (empty(wCMS::getConfig('menuItems')))
			$sitePanel .= mb_convert_case(wCMS::getConfig('defaultPage'), MB_CASE_TITLE);
		foreach (wCMS::getConfig('menuItems') as $key)
			$sitePanel .= $key.'<br>';
		$sitePanel = preg_replace('/(<br>)+$/', '', $sitePanel);
		$sitePanel .= '
						</span>
					</div>
					<div class="marginTop20">
						<label for="defaultPage" data-toggle="tooltip" data-placement="right" title="To make another page as your default homepage, rename this to another existing page">Default homepage</label>
						<span id="defaultPage" class="change editText">'.wCMS::getConfig('defaultPage').'</span>
					</div>
					<div class="marginTop20">
						<label for="login" data-toggle="tooltip" data-placement="right" title="eg: your-domain.com/yourLoginURL">Login URL</label>
						<span id="login" class="change editText">'.wCMS::getConfig('login').'</span>
					</div>
					<div class="marginTop20"">
						<label for="pwForm">Change password</label>
						<form class="change" id="pwForm" action="'.wCMS::url(wCMS::$currentPage).'" method="post">
							<div class="form-group"><input type="password" name="old_password" class="form-control" placeholder="Old password"></div>
							<div class="form-group"><input type="password" name="content" class="form-control" placeholder="New password"></div>
							<input type="hidden" name="fieldname" value="password">
							<button type="submit" class="btn btn-info">Change password</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Overlay content -->
</div>
';
	if(($k = array_search('displayModernSettings', wCMS::$listeners['settings'])) !== false) {
		unset(wCMS::$listeners['settings'][$k]);
	}

	$pluginsPanel = wCMS::hook('settings', $pluginsPanel);
	if (@is_array($pluginsPanel))
		$pluginsPanel = implode('', $pluginsPanel);

	$pluginsPanel = '
<div id="pluginsPanel" class="overlay">
	<!-- Button to close the overlay navigation -->
	<a href="javascript:void(0)" class="closebtn" onclick="closePanel(\'plugins\')">&times;</a>
	<!-- Overlay content -->
	<div class="overlay-content">
		<div class="container-fluid">
			<div class="col-xs-12 col-sm-8 col-sm-offset-2">
				<div class="text-left">
				<div class="marginTop20">'.$pluginsPanel.'</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Overlay content -->
</div>
';
	$args[0] = $settingNav.$pagePanel.$pluginsPanel.$sitePanel;
	return $args;
}
