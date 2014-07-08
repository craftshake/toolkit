<?php

namespace Craft;

class ToolkitVariable
{
	public function getPlugins()
	{
		return craft()->toolkit_plugins->getPlugins();
	}

	public function getPluginById($id)
	{
		return craft()->toolkit_plugins->getPluginById($id);
	}

	public function getReleaseById($id)
	{
		return craft()->toolkit_plugins->getReleaseById($id);
	}

	public function isReleased($version, $releases)
	{
		foreach ($releases as $release) {
			if ($release->version == $version)
			{
				return true;
			}
		}

		return false;
	}
}
