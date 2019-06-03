<?php

use Illuminate\Database\Seeder;
use App\Transfer;

class TransfersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Transfer::class, 10)->create();
    }
}
