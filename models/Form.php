<?php
namespace TheWebsiteGuy\FormWizard\Models;

use Model;

/**
 * Model
 */
class Form extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'thewebsiteguy_formwizard_forms';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'code' => 'required|alpha_dash|unique:thewebsiteguy_formwizard_forms',
    ];

    /**
     * @var array Attribute names to encode and decode using JSON.
     */
    public $jsonable = ['form_fields', 'form_sections'];

    public function afterSave()
    {
        \TheWebsiteGuy\FormWizard\Classes\ComponentGenerator::generate($this);
    }

    public function afterDelete()
    {
        \TheWebsiteGuy\FormWizard\Classes\ComponentGenerator::delete($this);
    }
}
