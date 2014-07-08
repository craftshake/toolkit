<?php

namespace Craft;

class Toolkit_PluginRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'toolkit_plugins';
    }

    protected function defineAttributes()
    {
        return array(
            'name'      => AttributeType::String,
            'settings'  => AttributeType::Mixed,
        );
    }

    public function defineRelations()
    {
        return array(
            'releases' => array(static::HAS_MANY, 'Toolkit_ReleaseRecord', 'pluginId'),
        );
    }

    public function defineIndexes()
    {
        return array(
            array('columns' => array('name'), 'unique' => true),
        );
    }

    public function scopes()
    {
        return array(
            'ordered' => array('order' => 'name'),
        );
    }
}
