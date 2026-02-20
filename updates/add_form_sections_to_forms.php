<?php
namespace TheWebsiteGuy\FormWizard\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class AddFormSectionsToForms extends Migration
{
    public function up()
    {
        Schema::table('thewebsiteguy_formwizard_forms', function ($table) {
            $table->text('form_sections')->nullable()->after('form_fields');
        });
    }

    public function down()
    {
        Schema::table('thewebsiteguy_formwizard_forms', function ($table) {
            $table->dropColumn('form_sections');
        });
    }
}
