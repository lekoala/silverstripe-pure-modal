<?php

namespace LeKoala\PureModal;

use SilverStripe\Forms\FieldList;
use SilverStripe\View\Requirements;
use SilverStripe\Forms\LiteralField;

/**
 * Custom modal action
 * Requires cms-actions to work out of the box
 */
class PureModalAction extends LiteralField
{
    /**
     * @var FieldList
     */
    protected $fieldList;

    /**
     * A custom title for the dialog button
     * @var string
     */
    protected $dialogButtonTitle;

    /**
     * Should it show the dialog button
     * @var boolean
     */
    protected $showDialogButton = true;

    /**
     * An icon for this button
     * @var string
     */
    protected $buttonIcon;

    /**
     * @var boolean
     */
    protected $shouldRefresh = false;

    public function __construct($name, $title)
    {
        $name = 'doCustomAction[' . $name . ']';
        $this->title = $title;
        $this->name = $name;
    }

    public function FieldHolder($properties = array())
    {
        Requirements::javascript('lekoala/silverstripe-pure-modal: client/pure-modal.js');
        Requirements::css('lekoala/silverstripe-pure-modal: client/pure-modal.css');

        $modalContent = '';
        foreach ($this->fieldList as $field) {
            $modalContent .= $field->FieldHolder();
        }

        $attrs = '';
        $modalID = 'modal_' . $this->name;

        $content = '';
        $content .= '<label for="' . $modalID . '" class="btn btn-info"' . $attrs . '>';
        $content .= $this->getButtonTitle();
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
        $content .= $modalContent;

        // Add actual button
        if($this->showDialogButton) {
            $content .= '<button type="submit" name="action_' . $this->name . '" class="btn action btn btn-info custom-action">
                <span class="btn__title">' . $this->getButtonTitle() . '</span>
            </button>';
        }

        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $this->content = $content;

        return parent::FieldHolder($properties);
    }

    /**
     * Get the title with icon if set
     *
     * @return string
     */
    protected function getButtonTitle()
    {
        $title = $this->title;
        if ($this->buttonIcon) {
            $title = '<span class="font-icon-' . $this->buttonIcon . '"></span> ' . $title;
        }
        return $title;
    }

    /**
     * Get the dialog button title with icon if set
     *
     * @return string
     */
    protected function getDialogButtonTitle()
    {
        $title = $this->buttonTitle ?: $this->title;
        if ($this->buttonIcon) {
            $title = '<span class="font-icon-' . $this->buttonIcon . '"></span> ' . $title;
        }
        return $title;
    }

    /**
     * Set dialog button customised button title
     *
     * @return self
     */
    public function setDialogButtonTitle($value)
    {
        $this->buttonTitle = $value;
        return $this;
    }


    /**
     * Get an icon for this button
     *
     * @return string
     */
    public function getButtonIcon()
    {
        return $this->buttonIcon;
    }

    /**
     * Set an icon for this button
     *
     * Feel free to use SilverStripeIcons constants
     *
     * @param string $buttonIcon An icon for this button
     * @return $this
     */
    public function setButtonIcon(string $buttonIcon)
    {
        $this->buttonIcon = $buttonIcon;
        return $this;
    }

    /**
     * Get whether it must display the dialog button
     *
     * @return boolean
     */
    protected function getShowDialogButton()
    {
        return $this->showDialogButton;
    }

    /**
     * Set whether it must display the dialog button
     *
     * @return self
     */
    public function setShowDialogButton($value)
    {
        $this->showDialogButton = !!$value;
        return $this;
    }



    /**
     * Get the value of fieldList
     * @return FieldList
     */
    public function getFieldList()
    {
        return $this->fieldList;
    }

    /**
     * Set the value of fieldList
     *
     * @param FieldList $fieldList
     * @return $this
     */
    public function setFieldList(FieldList $fieldList)
    {
        $this->fieldList = $fieldList;
        return $this;
    }

    /**
     * Required for cms-actions
     * @return string
     */
    public function actionName()
    {
        return rtrim(str_replace('doCustomAction[', '', $this->name), ']');
    }

    /**
     * Get the value of shouldRefresh
     * @return mixed
     */
    public function getShouldRefresh()
    {
        return $this->shouldRefresh;
    }

    /**
     * Set the value of shouldRefresh
     *
     * @param mixed $shouldRefresh
     * @return $this
     */
    public function setShouldRefresh($shouldRefresh)
    {
        $this->shouldRefresh = $shouldRefresh;
        return $this;
    }
}
