<?php

use Illuminate\Database\Migrations\Migration;

class AlterUserDevicesChangeDeviceIdToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `user_devices` CHANGE COLUMN `device_id` `token` VARCHAR(255) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `user_devices` CHANGE COLUMN `token` `device_id` VARCHAR(255) NOT NULL');
    }
}
