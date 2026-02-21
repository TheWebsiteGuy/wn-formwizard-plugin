<?php
namespace TheWebsiteGuy\FormWizard;

use Backend\Facades\Backend;
use Illuminate\Support\Facades\Lang;
use TheWebsiteGuy\FormWizard\Classes\BackendHelpers;
use TheWebsiteGuy\FormWizard\Classes\GDPR;
use TheWebsiteGuy\FormWizard\Classes\UnreadRecords;
use TheWebsiteGuy\FormWizard\Models\Settings;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Winter\Storm\Support\Facades\Validator;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'thewebsiteguy.formwizard::lang.plugin.name',
            'description' => 'thewebsiteguy.formwizard::lang.plugin.description',
            'author' => 'The Website Guy',
            'icon' => 'icon-hat-wizard',
            'homepage' => 'https://github.com/thewebsiteguy/wn-formwizard-plugin',
        ];
    }

    public function registerNavigation()
    {
        if (Settings::get('global_hide_button', false)) {
            return [];
        }

        return [
            'forms' => [
                'label' => 'thewebsiteguy.formwizard::lang.menu.label',
                'icon' => 'icon-hat-wizard',
                'url' => BackendHelpers::getBackendURL(['thewebsiteguy.formwizard.access_records' => 'thewebsiteguy/formwizard/records', 'thewebsiteguy.formwizard.access_exports' => 'thewebsiteguy/formwizard/exports'], 'thewebsiteguy.formwizard.access_records'),
                'permissions' => ['thewebsiteguy.formwizard.*'],
                'sideMenu' => [
                    'records' => [
                        'label' => 'thewebsiteguy.formwizard::lang.menu.records.label',
                        'icon' => 'icon-database',
                        'url' => Backend::url('thewebsiteguy/formwizard/records'),
                        'permissions' => ['thewebsiteguy.formwizard.access_records'],
                        'counter' => UnreadRecords::getTotal(),
                        'counterLabel' => 'Un-Read Messages',
                    ],
                    'forms' => [
                        'label' => 'Forms',
                        'icon' => 'icon-list-alt',
                        'url' => Backend::url('thewebsiteguy/formwizard/forms'),
                        'permissions' => ['thewebsiteguy.formwizard.access_forms'],
                    ],
                    'exports' => [
                        'label' => 'thewebsiteguy.formwizard::lang.menu.exports.label',
                        'icon' => 'icon-download',
                        'url' => Backend::url('thewebsiteguy/formwizard/exports'),
                        'permissions' => ['thewebsiteguy.formwizard.access_exports'],
                    ],
                ],
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'config' => [
                'label' => 'thewebsiteguy.formwizard::lang.menu.label',
                'description' => 'thewebsiteguy.formwizard::lang.menu.settings',
                'category' => SettingsManager::CATEGORY_CMS,
                'icon' => 'icon-hat-wizard',
                'class' => 'TheWebsiteGuy\FormWizard\Models\Settings',
                'permissions' => ['thewebsiteguy.formwizard.access_settings'],
                'order' => 500,
            ],
        ];
    }

    public function registerPermissions()
    {
        return [
            'thewebsiteguy.formwizard.access_settings' => ['tab' => 'thewebsiteguy.formwizard::lang.permissions.tab', 'label' => 'thewebsiteguy.formwizard::lang.permissions.access_settings'],
            'thewebsiteguy.formwizard.access_records' => ['tab' => 'thewebsiteguy.formwizard::lang.permissions.tab', 'label' => 'thewebsiteguy.formwizard::lang.permissions.access_records'],
            'thewebsiteguy.formwizard.access_exports' => ['tab' => 'thewebsiteguy.formwizard::lang.permissions.tab', 'label' => 'thewebsiteguy.formwizard::lang.permissions.access_exports'],
            'thewebsiteguy.formwizard.access_forms' => ['tab' => 'thewebsiteguy.formwizard::lang.permissions.tab', 'label' => 'Access Forms'],
            'thewebsiteguy.formwizard.gdpr_cleanup' => ['tab' => 'thewebsiteguy.formwizard::lang.permissions.tab', 'label' => 'thewebsiteguy.formwizard::lang.permissions.gdpr_cleanup'],
        ];
    }

    public function registerComponents()
    {
        $components = [];

        // Register Components in the components directory
        $path = __DIR__ . '/components';
        if (is_dir($path)) {
            foreach (glob($path . '/*.php') as $filename) {
                $className = 'TheWebsiteGuy\FormWizard\Components\\' . basename($filename, '.php');
                $alias = 'formWizard' . basename($filename, '.php');
                $components[$className] = $alias;
            }
        }

        return $components;
    }

    public function registerFormWidgets()
    {
        return [
            'TheWebsiteGuy\FormWizard\FormWidgets\FormBuilder' => [
                'label' => 'Form Builder',
                'code' => 'fw_formbuilder'
            ]
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'thewebsiteguy.formwizard::mail.notification' => Lang::get('thewebsiteguy.formwizard::lang.mails.form_notification.description'),
            'thewebsiteguy.formwizard::mail.autoresponse' => Lang::get('thewebsiteguy.formwizard::lang.mails.form_autoresponse.description'),
        ];
    }

    public function boot()
    {
        \Event::listen('backend.page.beforeDisplay', function ($controller) {
            $controller->addCss('/plugins/thewebsiteguy/formwizard/assets/css/backend.css');
        });
    }

    public function register()
    {
        $this->app->resolving('validator', function () {
            Validator::extend('recaptcha', 'TheWebsiteGuy\FormWizard\Classes\ReCaptchaValidator@validateReCaptcha');
        });
    }

    public function registerSchedule($schedule)
    {
        $schedule->call(function () {
            GDPR::cleanRecords();
        })->daily();
    }
}
