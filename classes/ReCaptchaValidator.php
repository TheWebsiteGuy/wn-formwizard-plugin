<?php
namespace TheWebsiteGuy\FormWizard\Classes;

use Illuminate\Support\Facades\Request;
use TheWebsiteGuy\FormWizard\Models\Settings;

class ReCaptchaValidator
{
    public function validateReCaptcha($attribute, $value, $parameters)
    {
        $secret_key = Settings::get('recaptcha_secret_key');
        $recaptcha = post('g-recaptcha-response');
        $ip = Request::getClientIp();
        $URL = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$recaptcha&remoteip=$ip";
        $response = json_decode(file_get_contents($URL), true);

        if (Settings::get('recaptcha_version', 'v2') == 'v3') {
            $threshold = Settings::get('recaptcha_score_threshold', 0.5);
            return ($response['success'] == true && isset($response['score']) && $response['score'] >= $threshold);
        }

        return ($response['success'] == true);
    }
}
