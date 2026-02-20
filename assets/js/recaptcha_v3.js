function refreshReCaptchaV3(siteKey, action) {
    grecaptcha.ready(function () {
        grecaptcha.execute(siteKey, { action: action }).then(function (token) {
            var inputs = document.querySelectorAll('.g-recaptcha-response-v3');
            inputs.forEach(function (input) {
                input.value = token;
            });
        });
    });
}

function initReCaptchaV3(siteKey, action) {
    refreshReCaptchaV3(siteKey, action);
    setInterval(function () {
        refreshReCaptchaV3(siteKey, action);
    }, 90 * 1000);
}
