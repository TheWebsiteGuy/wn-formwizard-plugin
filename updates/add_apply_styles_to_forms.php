<?php
namespace TheWebsiteGuy\FormWizard\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class AddApplyStylesToForms extends Migration
{
    public function up()
    {
        Schema::table('thewebsiteguy_formwizard_forms', function ($table) {
            $table->boolean('apply_styles')->default(true)->after('description');
        });
    }

    public function down()
    {
        Schema::table('thewebsiteguy_formwizard_forms', function ($table) {
            $table->dropColumn('apply_styles');
        });
    }
}
