<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMembershipSignupComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership', function (Blueprint $table) {
            $table->text('signup_comment')->nullable();
            $table->string('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership', function (Blueprint $table) {
            $table->dropColumn('signup_comment');
            $table->dropColumn('payment_method');
        });
    }
}
