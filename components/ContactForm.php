<?php namespace TheWebsiteGuy\FormWizard\Components;

use TheWebsiteGuy\FormWizard\Classes\FormWizard;
use TheWebsiteGuy\FormWizard\Models\Form;

class ContactForm extends FormWizard
{
    public function init()
    {
        parent::init();

        // Force the alias to be set properly for shared partials
        if (empty($this->alias)) {
            $this->alias = strtolower('ContactForm');
        }
    }

    public function onRun()
    {
        parent::onRun();
        $form = Form::where('code', 'contactForm')->first();
        if ($form) {
            $this->page['form_fields'] = $form->form_fields;
            $this->form_fields = $form->form_fields;
            
            $sections = $form->form_sections ?? [];
            if (is_string($sections)) {
                $decoded = json_decode($sections, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $sections = $decoded;
                }
            }
            $this->page['form_sections'] = $sections;
        }
    }

    public function componentDetails()
    {
        return [
            'name'        => 'Contact Form',
            'description' => 'A basic contact form',
            'icon'        => 'fw-icon-hat-wizard',
        ];
    }

    public function defineProperties()
    {
        $properties = parent::defineProperties();
        
        // Lock the form_code to this specific form
        if (isset($properties['form_code'])) {
            $properties['form_code']['default'] = 'contactForm';
            $properties['form_code']['type'] = 'string';
            $properties['form_code']['readOnly'] = true;
        }

        return $properties;
    }
}