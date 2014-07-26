<?php

namespace Craft;

class Toolkit_PluginModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'id'   => AttributeType::Number,
			'name' => AttributeType::String,
			'settings' => AttributeType::Mixed,
		);
	}

	public function getReleases()
	{
		return craft()->toolkit_plugins->getReleasesByPluginId($this->id);
	}

	public function isBuilt()
	{
		$plugin = craft()->plugins->getPlugin($this->name);
		return craft()->toolkit_plugins->releaseExists($this->name, $plugin->version);
	}
}
