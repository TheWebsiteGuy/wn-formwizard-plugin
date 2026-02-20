<?php

namespace TheWebsiteGuy\FormWizard\Updates;

use Winter\Storm\Support\Facades\Schema;
use Winter\Storm\Database\Updates\Migration;
use TheWebsiteGuy\FormWizard\Models\Record;

class AddUnreadField extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('thewebsiteguy_formwizard_records', 'unread')) {
            // CREATE FIELD SETTING EXISTING RECORDS TO READ
            Schema::table('thewebsiteguy_formwizard_records', function ($table) {
                $table->boolean('unread')->default(0)->after('ip');
            });

            // UPDATE DEFAULT STATE TO UNREAD FOR NEW RECORDS
            Schema::table('thewebsiteguy_formwizard_records', function ($table) {
                $table->boolean('unread')->default(1)->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('thewebsiteguy_formwizard_records', 'unread')) {
            Schema::table('thewebsiteguy_formwizard_records', function ($table) {
                $table->dropColumn('unread');
            });
        }
    }
}
