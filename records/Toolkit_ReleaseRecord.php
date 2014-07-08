<?php

namespace Craft;

class Toolkit_ReleaseRecord extends BaseRecord
{
	public function getTableName()
	{
		return 'toolkit_releases';
	}

	protected function defineAttributes()
	{
		return array(
			'version'     => array('maxLength' => 15, 'column' => ColumnType::Char, 'required' => true),
			'releaseDate' => array(AttributeType::DateTime, 'required' => true),
			'added'       => AttributeType::Mixed,
			'improved'    => AttributeType::Mixed,
			'fixed'       => AttributeType::Mixed,
		);
	}

	public function defineRelations()
	{
		return array(
			'plugin' => array(static::BELONGS_TO, 'Toolkit_PluginRecord', 'onDelete' => static::CASCADE),
		);
	}

	public function scopes()
	{
		return array(
			'ordered' => array('order' => 'name'),
		);
	}
}
