<?php

use Illuminate\Database\Seeder;

class AlterPasswordClientSecret extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Laravel\Passport\Client::query()
            ->where('id', 2)->update([
                'secret' => 'bXKWFmfsJsQJn9iwDU2K4qEvMAb9uYvqlQqSFRiR',
            ]);
    }
}
