<?php

namespace LeKoala\PureModal;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\DatalessField;
use SilverStripe\Forms\FormField;
use SilverStripe\View\SSViewer;

/**
 * A simple pure css modal for usage in the cms
 *
 * If your content contains a form, it should be loaded through an iframe
 * because you cannot nest forms (warning, this gets tricky with "move_modal_to_body")
 *
 * @author lekoala
 */
class PureModal extends DatalessField
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $iframe;

    /**
     * @var bool
     */
    protected $iframeTop;

    /**
     * @param string $name
     * @param string $title
     * @param string $content
     */
    public function __construct($name, $title, $content)
    {
        $this->setContent($content);

        parent::__construct($name, $title);
    }

    /**
     * @return string
     */
    public static function getMoveModalScript()
    {
        if (!self::config()->move_modal_to_body) {
            return '';
        }
        return "document.body.appendChild(this.parentElement.querySelector('.pure-modal'));this.onclick=null;";
    }

    /**
     * @return bool
     */
    public static function getOverlayTriggersCloseConfig()
    {
        return boolval(self::config()->overlay_triggers_close);
    }

    /**
     * For template usage
     * @return bool
     */
    public function getOverlayTriggersClose()
    {
        return PureModal::getOverlayTriggersCloseConfig();
    }

    /**
     * @return array<string,mixed>
     */
    public function getAttributes()
    {
        $attrs = [];
        // Move modal to body to avoid nesting issues
        $attrs['onclick'] = self::getMoveModalScript();
        // Since the frame is hidden, we need to compute size on click
        // TODO: fix this as it may report incorrect height
        if ($this->getIframe() && self::config()->compute_iframe_height) {
            $attrs['onclick'] .= "var i=document.querySelector('#" . $this->getIframeID() . "');i.style.height = 0; setTimeout(function() {i.style.height = i.contentWindow.document.body.scrollHeight + 'px';},100);";
        }
        return $attrs;
    }

    /**
     * Render a dialog
     *
     * @param Controller $controller
     * @param array<string,mixed> $customFields
     * @return string
     */
    public static function renderDialog($controller, $customFields = null)
    {
        // Set empty content by default otherwise it will render the full page
        if (empty($customFields['Content'])) {
            $customFields['Content'] = '';
        }

        $templates = SSViewer::get_templates_by_class(static::class, '_Dialog', __CLASS__);
        $templatesWithSuffix = SSViewer::chooseTemplate($templates);

        return $controller->renderWith($templatesWithSuffix, $customFields);
    }

    /**
     * Sets the content of this field to a new value.
     *
     * @param string|FormField $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Build an url for the current controller and pass along some parameters
     *
     * If you want to call actions on a model, use getModelLink
     *
     * @param string $action
     * @param array<string,mixed> $params
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

    /**
     * @return string
     */
    public function getModalID()
    {
        return 'modal_' . $this->name;
    }

    /**
     * @return string
     */
    public function getIframeID()
    {
        return $this->getModalID() . '_iframe';
    }
}
