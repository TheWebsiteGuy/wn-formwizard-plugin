<?php
namespace TheWebsiteGuy\FormWizard\Classes;

use Winter\Storm\Support\Str;
use TheWebsiteGuy\FormWizard\Models\Form;
use File;

class ComponentGenerator
{
    public static function generate(Form $form)
    {
        $code = Str::studly($form->code);
        $className = preg_match('/Form$/i', $code) ? $code : $code . 'Form';
        $directory = plugins_path('thewebsiteguy/formwizard/components');
        $viewDirectory = $directory . '/' . strtolower($className);
        $filePath = $directory . '/' . $className . '.php';

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0777, true);
        }

        if (!File::isDirectory($viewDirectory)) {
            File::makeDirectory($viewDirectory, 0777, true);
        }

        // Generate Class
        $content = self::getClassContent($className, $form);
        File::put($filePath, $content);

        // Generate Default View
        $sourceView = plugins_path('thewebsiteguy/formwizard/classes/partials/default.htm');
        if (File::exists($sourceView)) {
            File::put($viewDirectory . '/default.htm', File::get($sourceView));
        }

        // Copy required partials for native Twig resolution
        $partials = ['filepond.htm', 'recaptcha.htm', 'flash.htm', 'js/recaptcha.htm', 'js/reset-form.htm'];
        foreach ($partials as $partial) {
            $source = plugins_path('thewebsiteguy/formwizard/classes/partials/' . $partial);
            $dest = $viewDirectory . '/' . $partial;
            if (!File::isDirectory(dirname($dest))) {
                File::makeDirectory(dirname($dest), 0777, true);
            }
            if (File::exists($source)) {
                File::put($dest, File::get($source));
            }
        }
    }

    public static function delete(Form $form)
    {
        $code = Str::studly($form->code);
        $className = preg_match('/Form$/i', $code) ? $code : $code . 'Form';
        $directory = plugins_path('thewebsiteguy/formwizard/components');
        $viewDirectory = $directory . '/' . strtolower($className);
        $filePath = $directory . '/' . $className . '.php';

        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        if (File::isDirectory($viewDirectory)) {
            File::deleteDirectory($viewDirectory);
        }
    }

    private static function getClassContent($className, $form)
    {
        return <<<PHP
<?php namespace TheWebsiteGuy\FormWizard\Components;

use TheWebsiteGuy\FormWizard\Classes\FormWizard;
use TheWebsiteGuy\FormWizard\Models\Form;

class {$className} extends FormWizard
{
    public function init()
    {
        parent::init();

        // Force the alias to be set properly for shared partials
        if (empty(\$this->alias)) {
            \$this->alias = strtolower('{$className}');
        }
    }

    public function onRun()
    {
        parent::onRun();
        \$form = Form::where('code', '{$form->code}')->first();
        if (\$form) {
            \$this->page['form_fields'] = \$form->form_fields;
            \$this->form_fields = \$form->form_fields;
            
            \$sections = \$form->form_sections ?? [];
            if (is_string(\$sections)) {
                \$decoded = json_decode(\$sections, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array(\$decoded)) {
                    \$sections = \$decoded;
                }
            }
            \$this->page['form_sections'] = \$sections;
        }
    }

    public function componentDetails()
    {
        return [
            'name'        => '{$form->name}',
            'description' => '{$form->description}',
            'icon'        => 'fw-icon-hat-wizard',
        ];
    }

    public function defineProperties()
    {
        \$properties = parent::defineProperties();
        
        // Lock the form_code to this specific form
        if (isset(\$properties['form_code'])) {
            \$properties['form_code']['default'] = '{$form->code}';
            \$properties['form_code']['type'] = 'string';
            \$properties['form_code']['readOnly'] = true;
        }

        return \$properties;
    }
}
PHP;
    }
}
