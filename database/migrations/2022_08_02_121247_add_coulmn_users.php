<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoulmnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('twitter')->nullable()->after('zip_code');
            $table->text('linkedin')->nullable()->after('twitter');
            $table->string('organization')->nullable()->after('linkedin');
            $table->bigInteger('is_verified')->default(0)->after('organization');
            $table->enum('is_completed', ['true', 'false'])->default('false')->after('is_verified');
            $table->string('otp')->nullable()->after('is_completed');
            $table->dateTime('otp_expires')->nullable()->after('otp');
            $table->string('device_token')->nullable()->after('otp_expires');
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('twitter');
            $table->dropColumn('linkedin');
            $table->dropColumn('organization');
            $table->dropColumn('is_verified');
            $table->dropColumn('is_completed');
            $table->dropColumn('otp');
            $table->dropColumn('otp_expires');
            $table->dropColumn('device_token');
            $table->string('email')->nullable(false)->change();
        });
    }
}
