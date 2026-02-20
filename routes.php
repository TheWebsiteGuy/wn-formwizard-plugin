<?php
use Illuminate\Support\Facades\Route;

Route::
        namespace('TheWebsiteGuy\FormWizard\Classes\FilePond')
    ->prefix('thewebsiteguy/formwizard')
    ->group(function () {
        Route::post('/process', 'FilePondController@upload')->name('filepond.upload');
        Route::delete('/process', 'FilePondController@delete')->name('filepond.delete');
    });
