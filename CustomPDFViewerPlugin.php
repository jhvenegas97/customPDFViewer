<?php

namespace APP\plugins\generic\CustomPDFViewer;

use PKP\plugins\GenericPlugin;
use PKP\template\TemplateManager;
use PKP\plugins\Hook;

class CustomPDFViewerPlugin extends GenericPlugin {
    /**
     * @see Plugin::register()
     */
    function register($category, $path, $mainContextId = null) {
        $success = parent::register($category, $path, $mainContextId);
        if (!$success) return false;

        if ($this->getEnabled()) {
            Hook::add('TemplateManager::display', [$this, 'addPdfButton']);
        }
        return true;
    }

    /**
     * Get the display name of this plugin.
     * @return string
     */
    function getDisplayName() {
        return __('plugins.generic.CustomPDFViewer.displayName');
    }

    /**
     * Get the description of this plugin.
     * @return string
     */
    function getDescription() {
        return __('plugins.generic.CustomPDFViewer.description');
    }

    /**
     * Adds a button for PDFs in the galley section.
     */
    function addPdfButton($hookName, $args) {
        $templateMgr = $args[0];
        $template = $args[1];

        if ($template === 'frontend/pages/article.tpl') {
            $article = $templateMgr->getTemplateVars('article');
            $galleys = $article->getGalleys();
            
            foreach ($galleys as $galley) {
                if ($galley->getFileType() === 'application/pdf') {
                    $pdfUrl = $galley->getRemoteURL() ?: $galley->getLocalizedFileUrl();
                    $buttonHtml = '<a href="' . $pdfUrl . '" class="btn btn-primary" target="_blank">' . __('plugins.generic.CustomPDFViewer.viewPdf') . '</a>';
                    $templateMgr->assign('pdfButton', $buttonHtml);
                    break;
                }
            }
        }
    }
}
