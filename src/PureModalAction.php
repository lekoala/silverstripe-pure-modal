<?php

namespace LeKoala\PureModal;

use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\DatalessField;

/**
 * Custom modal action
 * Requires cms-actions to work out of the box
 */
class PureModalAction extends DatalessField
{
    /**
     * Default classes applied in constructor by the FormField
     * @config
     * @var array
     */
    private static $default_classes = ["btn", "btn-info"];

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

    /**
     * @var boolean
     */
    protected $fillHeight = false;

    public function __construct($name, $title)
    {
        $name = 'doCustomAction[' . $name . ']';
        $this->title = $title;
        $this->name = $name;

        parent::__construct($name, $title);
    }

    public function getOverlayTriggersClose()
    {
        return PureModal::getOverlayTriggersCloseConfig();
    }

    /**
     * If the modal is in the body, it needs to go back to the form to submit properly
     * @return string
     */
    public function SubmitOnClickScript()
    {
        if (!PureModal::getMoveModalScript()) {
            return '';
        }
        $formId = PureModal::config()->edit_form_id;
        return "event.stopPropagation();var f=document.getElementById('$formId');var m=this.closest('.pure-modal');m.style.display='none';f.appendChild(m);f.submit();";
    }

    /**
     * Move modal when clicking on the open button. Trigger only once.
     * @return string
     */
    public static function getMoveModalScript()
    {
        if (!PureModal::config()->move_modal_to_body) {
            return '';
        }
        $formId = PureModal::config()->edit_form_id;
        return "document.getElementById('$formId').appendChild(this.parentElement.querySelector('.pure-modal'));this.onclick=null;";
    }

    public function getAttributes()
    {
        $attrs = [];
        // Move modal to form to avoid nesting issues
        // We cannot append to body because this breaks form submission
        $attrs['onclick'] = self::getMoveModalScript();
        return $attrs;
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
        $title = $this->dialogButtonTitle ?: $this->title;
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
        $this->dialogButtonTitle = $value;
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
     * Set a new type of btn-something. It will remove any existing btn- class
     * @param string $type Leave blank to simply remove default button type
     * @return $this
     */
    public function setButtonType($type = null)
    {
        if ($this->extraClasses) {
            foreach ($this->extraClasses as $k => $v) {
                if (strpos($k, 'btn-') !== false) {
                    unset($this->extraClasses[$k]);
                }
            }
        }
        if ($type) {
            $btn = "btn-$type";
            $this->extraClasses[$btn] = $btn;
        }
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
        foreach ($fieldList->dataFields() as $f) {
            $f->addExtraClass('no-change-track');
        }
        return $this;
    }

    /**
     * Make sure our nested fieldlist is bound to form
     *
     * @param Form $form
     * @return $this
     */
    public function setForm($form)
    {
        //@link https://github.com/unclecheese/silverstripe-display-logic/pull/155#issuecomment-1540966157
        if ($this->fieldList) {
            foreach ($this->fieldList as $field) {
                $field->setForm($form);
            }
        }
        return parent::setForm($form);
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
     * You might want to call also setButtonType(null) for better styles
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

    public function getFillHeight()
    {
        return $this->fillHeight;
    }

    public function setFillHeight($fillHeight)
    {
        $this->fillHeight = $fillHeight;
        return $this;
    }
}
