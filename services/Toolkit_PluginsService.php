<?php

namespace Craft;

class Toolkit_PluginsService extends BaseApplicationComponent
{
	public function getPluginById($id)
	{
		$pluginRecord = Toolkit_PluginRecord::model()->findById($id);

		return Toolkit_PluginModel::populateModel($pluginRecord);
	}

	public function getPlugins()
	{
		$pluginRecords = Toolkit_PluginRecord::model()->findAll();

		return Toolkit_PluginModel::populateModels($pluginRecords);
	}

	public function getReleasesByPluginId($id)
	{
		$releaseRecords = Toolkit_ReleaseRecord::model()->findAllByAttributes(array(
			'pluginId' => $id
		));

		return Toolkit_ReleaseModel::populateModels($releaseRecords);
	}

	public function getReleaseById($id)
	{
		$releaseRecord = Toolkit_ReleaseRecord::model()->findById($id);

		return Toolkit_ReleaseModel::populateModel($releaseRecord);
	}

	public function savePlugin(Toolkit_PluginModel $plugin)
	{
		$pluginRecord = $this->_getPluginRecord($plugin);

		$pluginRecord->name = $plugin->name;
		$pluginRecord->settings = $plugin->settings;

		if ($pluginRecord->validate())
		{
			$pluginRecord->save(false);
			$plugin->id = $pluginRecord->id;

			return true;
		}
		else
		{
			$plugin->addErrors($pluginRecord->getErrors());

			return false;
		}
	}

	public function saveRelease(Toolkit_ReleaseModel $release)
	{
		$releaseRecord = $this->_getReleaseRecord($release);

		$releaseRecord->version = $release->version;
		$releaseRecord->added = $release->added;
		$releaseRecord->improved = $release->improved;
		$releaseRecord->fixed = $release->fixed;
		$releaseRecord->pluginId = $release->pluginId;

		if ($releaseRecord->releaseDate == null)
		{
			$releaseRecord->releaseDate = new DateTime();
			var_dump($releaseRecord->releaseDate);
		}

		if ($releaseRecord->validate())
		{
			$releaseRecord->save(false);
			$release->id = $releaseRecord->id;

			return true;
		}
		else
		{
			$release->addErrors($releaseRecord->getErrors());

			return false;
		}
	}

	public function buildRelease($release)
	{
		$plugin = $release->getPlugin();

		$file = $plugin->name . '-' . $release->version;

		// Build release
		$directory = IOHelper::normalizePathSeparators(craft()->path->getPluginsPath() . $plugin->name);
		$filter = $plugin->settings['excluded'];

		$fileinfos = new \RecursiveIteratorIterator (
			new \RecursiveCallbackFilterIterator(
				new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
				function ($fileInfo, $key, $iterator) use ($filter) {
					return $fileInfo->isFile() || !in_array($fileInfo->getBaseName(), $filter);
				}
			)
		);

		$destZip = craft()->path->getTempPath().$file.'.zip';
		if (IOHelper::fileExists($destZip))
		{
			IOHelper::deleteFile($destZip, true);
		}

		IOHelper::createFile($destZip);

		foreach($fileinfos as $pathname => $fileinfo) {
			if (!Zip::add($destZip, $pathname, $directory))
			{
				IOHelper::deleteFile($destZip, true);
				return false;
			}
		}

		return true;
	}

	public function downloadRelease($release)
	{
		$fileName = $release->getPlugin()->name.'-'.$release->version;

		if (($filePath = IOHelper::fileExists(craft()->path->getTempPath().$fileName.'.zip')) == true)
		{
			craft()->request->sendFile(IOHelper::getFileName($filePath), IOHelper::getFileContents($filePath), array('forceDownload' => true));
		}
	}

	public function releaseExists($name, $version)
	{
		return IOHelper::fileExists(craft()->path->getTempPath().$name . '-' . $version.'.zip');
	}

	private function _getPluginRecord(Toolkit_PluginModel $plugin)
	{
		if ($plugin->id)
		{
			$pluginRecord = Toolkit_PluginRecord::model()->findById($plugin->id);

			if (!$pluginRecord)
			{
				throw new Exception(Craft::t('No plugin exists with the ID “{id}”', array('id' => $plugin->id)));
			}
		}
		else
		{
			$pluginRecord = new Toolkit_PluginRecord();
		}

		return $pluginRecord;
	}

	private function _getReleaseRecord(Toolkit_ReleaseModel $release)
	{
		if ($release->id)
		{
			$releaseRecord = Toolkit_ReleaseRecord::model()->findById($release->id);

			if (!$releaseRecord)
			{
				throw new Exception(Craft::t('No release exists with the ID “{id}”', array('id' => $release->id)));
			}
		}
		else
		{
			$releaseRecord = new Toolkit_ReleaseRecord();
		}

		return $releaseRecord;
	}
}
