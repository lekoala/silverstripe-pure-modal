<?php

namespace LeKoala\PureModal;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DatalessField;

/**
 * Custom modal action
 * Requires cms-actions to work out of the box
 */
class PureModalAction extends DatalessField
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

    /**
     * Whether to place the button in a dot-menu.
     * @see https://github.com/lekoala/silverstripe-cms-actions
     * @var bool
     */
    protected $dropUp = false;

    public function __construct($name, $title)
    {
        $name = 'doCustomAction[' . $name . ']';
        $this->title = $title;
        $this->name = $name;
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
     * Get the dropUp value
     * Called by ActionsGridFieldItemRequest to determine placement
     *
     * @see https://github.com/lekoala/silverstripe-cms-actions
     * @return bool
     */
    public function getDropUp()
    {
        return $this->dropUp;
    }

    /**
     * Set the value of dropUp
     *
     * @see https://github.com/lekoala/silverstripe-cms-actions
     * @param bool $is
     * @return $this
     */
    public function setDropUp($is)
    {
        $this->dropUp = !!$is;
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

    public function getModalID()
    {
        return 'modal_' . $this->name;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
