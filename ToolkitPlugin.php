<?php

namespace Craft;

class ToolkitPlugin extends BasePlugin
{
	public function getName()
	{
		return Craft::t('Toolkit');
	}

	public function getVersion()
	{
		return '0.9.1';
	}

	public function getDeveloper()
	{
		return 'Mario Friz';
	}

	public function getDeveloperUrl()
	{
		return 'http://craftshake.com';
	}

	public function hasCpSection()
	{
		return true;
	}

	public function registerCpRoutes()
	{
		return array(
			'toolkit/plugins/(?P<pluginId>\d+)' => 'toolkit/plugins/_releases',
			'toolkit/plugins/(?P<pluginId>\d+)/settings' => 'toolkit/plugins/_settings',
			'toolkit/plugins/(?P<pluginId>\d+)/releases' => 'toolkit/plugins/_releases',
			'toolkit/plugins/(?P<pluginId>\d+)/releases/new' => 'toolkit/plugins/_editrelease',
			'toolkit/plugins/(?P<pluginId>\d+)/releases/(?P<releaseId>\d+)' => 'toolkit/plugins/_editrelease',
			'toolkit/plugins/(?P<pluginId>\d+)/releases/(?P<releaseId>\d+)/build' => array('action' => 'toolkit/plugins/buildRelease'),
			'toolkit/plugins/(?P<pluginId>\d+)/releases/(?P<releaseId>\d+)/download' => array('action' => 'toolkit/plugins/downloadRelease'),
		);
	}
}
