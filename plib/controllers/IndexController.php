<?php
// Copyright 1999-2024. WebPros International GmbH. All rights reserved.
class IndexController extends pm_Controller_Action
{

    public function indexAction()
    {
        $this->view->uplevelLink = '/smb/file-manager/';

        $file = $this->_getParam('file');

        if (null === $file) {
            $this->_forward('overview');
            return;
        }

        $currentDir = $this->_getParam('currentDir');
        $content = $this->_getFileData($currentDir, $file);

        $this->view->pageTitle = $this->lmsg('indexPageTitle') . ': '
            . $this->view->escape($currentDir) . '/' . $this->view->escape($file);

        $this->view->headScript()->appendFile("//cdnjs.cloudflare.com/ajax/libs/tinymce/6.3.1/tinymce.min.js");

        $form = new pm_Form_Simple();

        $form->addElement('textarea', 'fileData', array(
            'value' => $content,
            'class' => 'f-max-size',
            'rows' => 40,
            'decorators' => array('ElementWithoutLabel'),
        ));

        $form->addControlButtons(array(
            'cancelLink' => '^/smb/file-manager/',
        ));

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $this->_setFileData($currentDir, $file, $form->getValue('fileData'));

            $this->_status->addMessage('info', $this->lmsg('fileDataSaved'));
            $this->_helper->json(array('redirect' => '^/smb/file-manager/'));
        }

        $this->view->form = $form;
        $this->view->domainName = $this->getDomain()->getName();
    }

    public function overviewAction()
    {
        $this->view->pageTitle = $this->lmsg('overviewPageTitle');
    }

    private function _setFileData($currentDir, $file, $fileData)
    {
        $fileManager = $this->_getFileManager();
        $filePath = $fileManager->getFilePath("$currentDir/$file");
        $fileManager->filePutContents($filePath, $fileData);
    }

    private function _getFileData($currentDir, $file)
    {
        $fileManager = $this->_getFileManager();
        $filePath = $fileManager->getFilePath("$currentDir/$file");
        return $fileManager->fileGetContents($filePath);
    }

    private function _getFileManager()
    {
        static $fileManager;
        if (!$fileManager) {
            $fileManager = new pm_FileManager($this->getDomain()->getId());
        }
        return $fileManager;
    }

    private function getDomain()
    {
        if ($this->_hasParam('domainId')) {
            $domainId = $this->_getParam('domainId');
            if (!pm_Session::getClient()->hasAccessToDomain($domainId)) {
                throw new pm_Exception(pm_Locale::lmsg('accessDenied'));
            }
            return new pm_Domain($domainId);
        }
        return pm_Session::getCurrentDomain();
    }

}
