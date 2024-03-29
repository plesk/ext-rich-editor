<?php
// Copyright 1999-2024. WebPros International GmbH. All rights reserved.
class Modules_RichEditor_FileManager_Action extends pm_FileManager_Action
{
    public function getTitle()
    {
        pm_Context::init('rich-editor');

        return pm_Locale::lmsg('actionTitle');
    }

    public function getHref()
    {
        pm_Context::init('rich-editor');

        $params = array(
            'currentDir=' . urlencode($this->_item['currentDir']),
            'file=' . urlencode($this->_item['name']),
        );

        if (isset($this->_item['domainId'])) {
            $params[] = 'domainId=' . urlencode($this->_item['domainId']);
        }

        return pm_Context::getBaseUrl() . '?' . implode('&', $params);
    }

    public function isActive()
    {
        if ($this->_item['isDirectory'] || !in_array(pathinfo($this->_item['name'], PATHINFO_EXTENSION), ['html', 'htm'])) {
            return false;
        }

        return true;
    }
}
