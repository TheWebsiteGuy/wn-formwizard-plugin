<?php
namespace TheWebsiteGuy\FormWizard\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateFormsTable extends Migration
{
    public function up()
    {
        Schema::create('thewebsiteguy_formwizard_forms', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->text('form_fields')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thewebsiteguy_formwizard_forms');
    }
}
