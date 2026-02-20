<?php

namespace TheWebsiteGuy\FormWizard\Updates;

use Winter\Storm\Support\Facades\Schema;
use Winter\Storm\Database\Updates\Migration;

class AddGroupField extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('thewebsiteguy_formwizard_records', 'group')) {
            Schema::table('thewebsiteguy_formwizard_records', function ($table) {
                $table->string('group')->nullable();
                $table->index('group');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('thewebsiteguy_formwizard_records', 'group')) {
            Schema::table('thewebsiteguy_formwizard_records', function ($table) {
                $table->dropColumn('group');
            });
        }
    }
}
