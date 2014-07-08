<?php

namespace Craft;

class Toolkit_ReleaseModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'id'          => AttributeType::Number,
			'version'     => AttributeType::String,
			'releaseDate' => AttributeType::DateTime,
			'added'       => AttributeType::Mixed,
			'improved'    => AttributeType::Mixed,
			'fixed'       => AttributeType::Mixed,
			'pluginId'    => AttributeType::Number,
		);
	}

	public function getPlugin()
	{
		return craft()->toolkit_plugins->getPluginById($this->pluginId);
	}
}
