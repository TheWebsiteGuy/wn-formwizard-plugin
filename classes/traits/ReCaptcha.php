<?php
namespace TheWebsiteGuy\FormWizard\Classes\Traits;

use TheWebsiteGuy\FormWizard\Classes\BackendHelpers;
use TheWebsiteGuy\FormWizard\Models\Settings;
use Winter\Translate\Classes\Translator;

trait ReCaptcha
{
    /**
     * @var string The active locale code.
     */
    public $activeLocale;

    private function isReCaptchaEnabled()
    {
        return ($this->property('recaptcha_enabled') && Settings::get('recaptcha_site_key') != '' && Settings::get('recaptcha_secret_key') != '');
    }

    private function isReCaptchaMisconfigured()
    {
        return ($this->property('recaptcha_enabled') && (Settings::get('recaptcha_site_key') == '' || Settings::get('recaptcha_secret_key') == ''));
    }

    private function getReCaptchaLang($lang = '')
    {
        if (BackendHelpers::isTranslatePlugin()) {
            $lang = '&hl=' . $this->activeLocale = Translator::instance()->getLocale();
        } else {
            $lang = '&hl=' . $this->activeLocale = app()->getLocale();
        }
        return $lang;
    }

    private function loadReCaptcha()
    {
        $version = Settings::get('recaptcha_version', 'v2');

        if ($version == 'v3') {
            $siteKey = Settings::get('recaptcha_site_key');
            $this->addJs('https://www.google.com/recaptcha/api.js?render=' . $siteKey . $this->getReCaptchaLang(), ['async', 'defer']);
            $this->addJs('/plugins/thewebsiteguy/formwizard/assets/js/recaptcha_v3.js');
        } else {
            $this->addJs('https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' . $this->getReCaptchaLang(), ['async', 'defer']);
            $this->addJs('/plugins/thewebsiteguy/formwizard/assets/js/recaptcha.js');
        }
    }
}
