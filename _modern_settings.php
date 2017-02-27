<?php
/**
 * Function of modern theme
 *
 * XXAdd login link to footer.
 *
 * @author  Prakai Nadee <prakai@rmuti.ac.th>
 * @version 1.0.0
 */

 if(defined('VERSION'))
  	define('version', VERSION);
 if(version<'2.0.0')
     defined('INC_ROOT') OR die('Direct access is not allowed.');

 if(version<'2.0.0') {
	 wCMS::addListener('css', 'loadModernCSS');
     wCMS::addListener('js', 'loadModernJS');
	 wCMS::addListener('settings', 'displayModernSettings');
 } else {
	 wCMS::addListener('onStyle', 'loadModernCSS');
     wCMS::addListener('onJavaScript', 'loadModernJS');
	 wCMS::addListener('onSettings', 'displayModernSettings');
 }


function loadModernJS($args) {
	$script = <<<'EOT'

<script src="plugins/_modern_settings/js/settings.js"></script>
EOT;
	if(version<'2.0.0')
		array_push($args[0], $script);
	else
		$args[0].=$script;
	return $args;
}

function loadModernCSS($args) {
	$script = <<<'EOT'

<link rel="stylesheet" href="plugins/_modern_settings/css/settings.css" type="text/css" media="screen" charset
="utf-8">
EOT;
	if(version<'2.0.0')
		array_push($args[0], $script);
	else
		$args[0].=$script;
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
	if (version<'2.0.0') {
		if (!wCMS::$newPage) {
			foreach (['title', 'description', 'keywords'] as $key)
				$pagePanel .= '
					<div>'.(($key == 'title') ? '
						<label >Page title, description and keywords</label>' : '').'
						<span id="'.$key.'" class="setbox editText">'.(@wCMS::getPage(wCMS::$currentPage)->$key != '' ? @wCMS::getPage(wCMS::$currentPage)->$key : 'Page '.$key.', unique for each page').'</span>
					</div>';
		}
		$pagePanel .= '
					<div class="marginTop20"></div>
					<a href="'.wCMS::url('?delete='.wCMS::$currentPage).'" class="btn btn-danger'.(wCMS::$newPage ? ' hide' : '').'" onclick="return confirm(\'Really delete page?\')">Delete current page ('.wCMS::$currentPage.')</a>';
	} else {
		if (wCMS::$currentPageExists) {
			foreach (['title', 'description', 'keywords'] as $key)
				$pagePanel .= '
				<div>'.(($key == 'title') ? '
					<label >Page title, description and keywords</label>' : '').'
					<span data-target="pages" id="' . $key . '" class="setbox editText">' . (wCMS::get('pages',wCMS::$currentPage)->$key != '' ? wCMS::get('pages',wCMS::$currentPage)->$key : '') . '</span>
				</div>';
		}
		$pagePanel .= '
					<div class="marginTop20"></div>
					<a href="' . wCMS::url('?delete=' . wCMS::$currentPage) . '" class="btn btn-danger' . ( ! wCMS::$currentPageExists ? ' hide' : '') . '" onclick="return confirm(\'Really delete page?\')">Delete current page (' . wCMS::$currentPage . ')</a>';
	}
	$pagePanel .= '
				</div>
			</div>
		</div>
	</div>
</div>
';
	if (version<'2.0.0') {
		$siteTitle = (wCMS::getConfig('siteTitle') != '' ? wCMS::getConfig('siteTitle') : '');
		$copyright = (wCMS::getConfig('copyright') != '' ? wCMS::getConfig('copyright') : '');
		$defaultPage = wCMS::getConfig('defaultPage');
		$loginURL = wCMS::getConfig('login');
	} else {
		$siteTitle = (wCMS::get('config','site_title') != '' ? wCMS::get('config','site_title') : '');
		$copyright = (wCMS::get('blocks','footer')->html != '' ? wCMS::get('blocks','footer')->html : '') ;
		$defaultPage = wCMS::get('config','default');
		$loginURL = wCMS::get('config','login');
	}
	$sitePanel = '
<div id="sitePanel" class="overlay">
	<!-- Button to close the overlay navigation -->
	<a href="javascript:void(0)" class="closebtn" onclick="closePanel(\'site\')">&times;</a>
	<!-- Overlay content -->
	<div class="overlay-content">
		<div class="container-fluid">
			<div class="col-xs-12 col-sm-8 col-sm-offset-2">
				<div class="text-left">
					<div>
						<label for="'.((version<'2.0.0')?'siteTitle':'site_title').'">Website title</label>
						<span id="'.((version<'2.0.0')?'siteTitle':'site_title').'" data-target="config" class="setbox editText">'.$siteTitle.'</span>
					</div>
					<div class="marginTop20">
						<label for="'.((version<'2.0.0')?'copyright':'footer').'">Web footer</label>
						<span id="'.((version<'2.0.0')?'copyright':'footer').'" data-target="blocks" class="setbox editText">'.$copyright.'</span>
					</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group marginTop20">
        						<label for="themeSelect">Themes</label>
        						<div class="setbox">
						';
	if (version<'2.0.0') {
		$sitePanel .= '<select id="themeSelect" class="form-control" name="themeSelect" onchange="fieldSave(\'theme\',this.value);">';
		foreach (glob(constant('INC_ROOT').'/themes/*', constant('GLOB_ONLYDIR')) as $dir) $sitePanel .= '<option value="'.basename($dir).'"'.(basename($dir) == wCMS::getConfig('theme') ? ' selected' : '').'>'.basename($dir).' theme'.'</option>';
	} else {
		$sitePanel .= '<select class="form-control" name="themeSelect" onchange="fieldSave(\'theme\',this.value,\'config\');">';
		foreach (glob(__DIR__ . '/../../themes/*', GLOB_ONLYDIR) as $dir) $sitePanel .= '<option value="' . basename($dir) . '"' . (basename($dir) == wCMS::get('config','theme') ? ' selected' : '') . '>' . basename($dir) . ' theme'.'</option>';
	}
	$sitePanel .= '
            						</select>
        						</div>
        					</div>
                        </div>
                        <div class="col-md-6">
        					<div class="marginTop20">
        						<label for="'.((version<'2.0.0')?'menuItems':'list').'" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Enter a new page name in a new line">Menu items</label>
        						<span id="'.((version<'2.0.0')?'menuItems':'list').'" data-target="config" class="setbox editText">
';
	if (version<'2.0.0') {
		if (empty(wCMS::getConfig('menuItems')))
			$sitePanel .= mb_convert_case(wCMS::getConfig('defaultPage'), MB_CASE_TITLE);
		foreach (wCMS::getConfig('menuItems') as $key)
			$sitePanel .= $key.'<br>';
		$sitePanel = preg_replace('/(<br>)+$/', '', $sitePanel);
	} else {
		foreach (wCMS::get('config','list') as $key)
		 	$sitePanel .= $key.'<br>';
		$sitePanel = preg_replace('/(<br>)+$/', '', $sitePanel);

	}
	$sitePanel .= '
        						</span>
        					</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
        					<div class="marginTop20">
        						<label for="'.((version<'2.0.0')?'defaultPage':'default').'" data-toggle="tooltip" data-placement="right" title="To make another page as your default homepage, rename this to another existing page">Default homepage</label>
        						<span id="'.((version<'2.0.0')?'defaultPage':'default').'" data-target="config" class="setbox editText">'.$defaultPage.'</span>
        					</div>
        					<div class="marginTop20">
        						<label for="login" data-toggle="tooltip" data-placement="right" title="eg: your-domain.com/yourLoginURL">Login URL</label>
        						<span id="login" data-target="config" class="setbox editText">'.$loginURL.'</span>
        					</div>
                        </div>
                        <div class="col-md-6">
        					<div class="marginTop20"">
        						<label>Change password</label>
        						<form class="setbox" action="'.wCMS::url(wCMS::$currentPage).'" method="post">
        							<div class="form-group"><input type="password" name="old_password" class="form-control" placeholder="Old password"></div>
        							<div class="form-group"><input type="password" name="'.((version<'2.0.0')?'content':'new_password').'" class="form-control" placeholder="New password"></div>
        							<input type="hidden" name="fieldname" value="password">
        							<button type="submit" class="btn btn-info">Change password</button>
        						</form>
        					</div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
';

	if (version<'2.0.0') {
		if(($k = array_search('displayModernSettings', wCMS::$listeners['settings'])) !== false) {
			unset(wCMS::$listeners['settings'][$k]);
		}
	} else {
		if(($k = array_search('displayModernSettings', wCMS::$_listeners['onSettings'])) !== false) {
			unset(wCMS::$_listeners['onSettings'][$k]);
		}
	}
	$pluginsPanel = '
<div id="pluginsPanel" class="overlay">
	<!-- Button to close the overlay navigation -->
	<a href="javascript:void(0)" class="closebtn" onclick="closePanel(\'plugins\')">&times;</a>
	<!-- Overlay content -->
	<div class="overlay-content">
		<div class="container-fluid">
			<div class="col-xs-12 col-sm-8 col-sm-offset-2">
				<div class="text-left">
';

	if (version<'2.0.0') {
		$pluginsPanel = wCMS::hook('settings', $pluginsPanel);
	} else {
		$pluginsPanel = wCMS::_hook('onSettings', $pluginsPanel);
	}
	if (@is_array($pluginsPanel))
		$pluginsPanel = implode('', $pluginsPanel);

	$pluginsPanel .= '
				</div>
			</div>
		</div>
	</div>
</div>
';

	$args[0] = $settingNav.$pagePanel.$pluginsPanel.$sitePanel;
	return $args;
}
