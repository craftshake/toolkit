<?php

namespace Craft;

class Toolkit_PluginsController extends BaseController
{
	public function actionSavePlugin()
	{
		$this->requirePostRequest();

		$name = craft()->request->getRequiredPost('plugin');
		$settings = craft()->request->getRequiredPost('settings');

		$settings['excluded'] = preg_split ('/\r\n|\n|\r/', $settings['excluded']);

		$plugin = new Toolkit_PluginModel();
		$plugin->name = $name;
		$plugin->settings = $settings;

		if (craft()->toolkit_plugins->savePlugin($plugin))
		{
			craft()->userSession->setNotice(Craft::t('Plugin in now tracked.'));
			$this->redirectToPostedUrl();
		}
		else
		{
			craft()->userSession->setError(Craft::t('Couldn’t track this plugin.'));
		}

		craft()->urlManager->setRouteVariables(array(
			'plugin' => $plugin,
		));
	}

	public function actionSaveRelease()
	{
		$this->requirePostRequest();

		$version = craft()->request->getRequiredPost('version');
		$added = craft()->request->getPost('added');
		$improved = craft()->request->getPost('improved');
		$fixed = craft()->request->getPost('fixed');
		$pluginId = craft()->request->getRequiredPost('pluginId');
		$releaseId = craft()->request->getPost('releaseId');

		$release = new Toolkit_ReleaseModel();
		$release->version = $version;
		$release->added = $added;
		$release->improved = $improved;
		$release->fixed = $fixed;
		$release->pluginId = $pluginId;

		if ($releaseId)
		{
			$release->id = $releaseId;
		}

		if (craft()->toolkit_plugins->saveRelease($release))
		{
			craft()->userSession->setNotice(Craft::t('Release has been saved.'));
			$this->redirectToPostedUrl();
		}
		else
		{
			var_dump($release->getErrors());
			craft()->userSession->setError(Craft::t('Couldn’t save this release.'));
		}

		craft()->urlManager->setRouteVariables(array(
			'release' => $release,
		));
	}

	public function actionBuildRelease()
	{
		//$this->redirect('toolkit/plugins/')
	}
}
