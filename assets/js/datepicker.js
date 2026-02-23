/**
 * FormWizard – Flatpickr date picker initialiser
 * Runs on first load and after every Winter CMS AJAX update.
 */
(function () {
    'use strict';

    function initDatepickers(root) {
        root = root || document;
        var inputs = root.querySelectorAll('.fw-form input[type="date"]');
        inputs.forEach(function (input) {
            if (input._flatpickr) return; // already initialised
            flatpickr(input, {
                allowInput: true,
                dateFormat: 'Y-m-d',
                disableMobile: false
            });
        });
    }

    // On initial page load
    document.addEventListener('DOMContentLoaded', function () {
        initDatepickers();
    });

    // Re-init after Winter CMS AJAX partial updates
    document.addEventListener('ajaxUpdate', function () {
        initDatepickers();
    });
}());
