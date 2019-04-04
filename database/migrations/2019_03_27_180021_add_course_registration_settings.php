<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseRegistrationSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('allow_registration')->default(true);
            $table->boolean('auto_confirm')->default(false);
            $table->unsignedInteger('max_participants')->nullable();
            $table->unsignedInteger('max_role_difference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('allow_registration');
            $table->dropColumn('auto_confirm');
            $table->dropColumn('max_participants');
            $table->dropColumn('max_role_difference');
        });
    }
}
