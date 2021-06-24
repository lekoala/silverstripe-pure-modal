<?php

namespace LeKoala\PureModal;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\View\Requirements;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\LiteralField;

/**
 * A simple pure css modal for usage in the cms
 *
 * If your content contains a form, it should be loaded through an iframe
 * because you cannot nest forms
 *
 * @author lekoala
 */
class PureModal extends LiteralField
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $iframe;

    /**
     * @var bool
     */
    protected $iframeTop;

    public function __construct($name, $title, $content)
    {
        parent::__construct($name, $content);
        $this->title = $title;
    }

    public function FieldHolder($properties = array())
    {
        Requirements::javascript('lekoala/silverstripe-pure-modal: client/pure-modal.js');
        Requirements::css('lekoala/silverstripe-pure-modal: client/pure-modal.css');

        $attrs = '';
        $modalID = 'modal_' . $this->name;

        $onclick = '';
        if ($this->iframe) {
            $onclick = "resizeIframe(document.getElementById(this.getAttribute('for') + '_iframe'))";
        }
        $content = '';
        $content .= '<label for="' . $modalID . '" class="btn btn-primary"' . $attrs . ' onclick="' . $onclick . '">';
        $content .= $this->title;
        $content .= '</label>';
        $content .= '<div class="pure-modal from-top">';
        // This is how we show the modal
        $content .= '<input id="' . $modalID . '" class="checkbox" type="checkbox">';
        $content .= '<div class="pure-modal-overlay">';
        // Close in overlay
        $content .= '<label for="' . $modalID . '" class="o-close"></label>';
        $content .= '<div class="pure-modal-wrap">';
        // Close icon
        $content .= '<label for="' . $modalID . '" class="close">&#10006;</label>';
        // Iframe if set and top
        if ($this->iframe && $this->iframeTop) {
            $content .= '<iframe id="' . $modalID . '_iframe" src="' . $this->iframe . '" width="100%%" style="max-height:400px" frameBorder="0" scrolling="auto"></iframe>';
        }
        $content .= $this->content;
        // Iframe if set and not top
        if ($this->iframe && !$this->iframeTop) {
            $content .= '<iframe id="' . $modalID . '_iframe"  src="' . $this->iframe . '" width="100%%" style="max-height:400px" frameBorder="0" scrolling="auto"></iframe>';
        }
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $this->content = $content;

        return parent::FieldHolder($properties);
    }

    /**
     * Build an url for the current controller and pass along some parameters
     *
     * If you want to call actions on a model, use getModelLink
     *
     * @param string $action
     * @param array $params
     * @return string
     */
    public function getControllerLink($action, array $params = null)
    {
        if ($params === null) {
            $params = [];
        }
        $ctrl = Controller::curr();
        if ($ctrl instanceof ModelAdmin) {
            $allParams = $ctrl->getRequest()->allParams();
            $modelClass = $ctrl->getRequest()->param('ModelClass');
            $action = $modelClass . '/' . $action;
            $params = array_merge($allParams, $params);
        }
        if (!empty($params)) {
            $action .= '?' . http_build_query($params);
        }
        return $ctrl->Link($action);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getIframe()
    {
        return $this->iframe;
    }

    /**
     * Link to iframe src
     *
     * @param string $iframe
     * @return $this
     */
    public function setIframe($iframe)
    {
        $this->iframe = $iframe;

        return $this;
    }

    /**
     * Helper for setIframe link using conventions
     *
     * @param string $action
     * @return $this
     */
    public function setIframeAction($action)
    {
        return $this->setIframe($this->getControllerLink($action));
    }

    /**
     * @return bool
     */
    public function getIframeTop()
    {
        return $this->iframeTop;
    }

    /**
     * Should the iframe be above the content
     *
     * @param bool $iframeTop
     * @return $this
     */
    public function setIframeTop($iframeTop)
    {
        $this->iframeTop = $iframeTop;
        return $this;
    }
}
