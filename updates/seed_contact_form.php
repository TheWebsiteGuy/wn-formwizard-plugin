<?php
namespace TheWebsiteGuy\FormWizard\Updates;

use TheWebsiteGuy\FormWizard\Models\Form;
use Winter\Storm\Database\Updates\Seeder;

class SeedContactForm extends Seeder
{
    public function run()
    {
        if (Form::where('code', 'contactForm')->exists()) {
            return;
        }

        $form = new Form;
        $form->name = 'Contact Form';
        $form->code = 'contactForm';
        $form->description = 'A basic contact form';
        $form->apply_styles = 1;
        $form->form_sections = [
            [
                "title" => "Contact Details",
                "columns" => "2",
                "show_title" => "0",
                "fields" => [
                    [
                        "label" => "Name",
                        "span" => "left",
                        "code" => "name",
                        "type" => "text",
                        "required" => "1",
                        "placeholder" => "",
                        "validation_rules" => "",
                        "options" => "",
                        "cssClass" => "form-control",
                        "wrapperClass" => "form-group"
                    ],
                    [
                        "label" => "Phone",
                        "span" => "right",
                        "code" => "phone",
                        "type" => "text",
                        "required" => "0",
                        "placeholder" => "",
                        "validation_rules" => "",
                        "options" => "",
                        "cssClass" => "form-control",
                        "wrapperClass" => "form-group"
                    ],
                    [
                        "label" => "Email",
                        "span" => "full",
                        "code" => "email",
                        "type" => "email",
                        "required" => "1",
                        "placeholder" => "",
                        "validation_rules" => "",
                        "options" => "",
                        "cssClass" => "form-control",
                        "wrapperClass" => "form-group"
                    ]
                ]
            ],
            [
                "title" => "Content",
                "columns" => "1",
                "show_title" => "0",
                "fields" => [
                    [
                        "label" => "Message",
                        "span" => "full",
                        "code" => "message",
                        "type" => "text",
                        "required" => "0",
                        "placeholder" => "",
                        "validation_rules" => "",
                        "options" => "",
                        "cssClass" => "form-control",
                        "wrapperClass" => "form-group"
                    ]
                ]
            ]
        ];
        $form->save();
    }
}
