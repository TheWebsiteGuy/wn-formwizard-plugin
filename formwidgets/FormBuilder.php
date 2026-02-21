<?php
namespace TheWebsiteGuy\FormWizard\FormWidgets;

use Backend\Classes\FormWidgetBase;

class FormBuilder extends FormWidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'formbuilder';

    public function init()
    {
    }

    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('formbuilder');
    }

    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    public function loadAssets()
    {
        $this->addCss('css/formbuilder.css', 'TheWebsiteGuy.FormWizard');
        $this->addJs('js/formbuilder.js', 'TheWebsiteGuy.FormWizard');
    }

    public function getSaveValue($value)
    {
        if (!$value) {
            return json_encode([]);
        }

        return $value;
    }
}
