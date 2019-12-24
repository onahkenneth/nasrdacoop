<?php

use Illuminate\Database\Seeder;
use App\Center;

class CentersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('centers')->truncate();

        Center::create([
            'name' => 'ARCSSTE'
        ]);

        Center::create([
            'name' => 'ASTAL UYO'
        ]);

        Center::create([
            'name' => 'CAR'
        ]);

        Center::create([
            'name' => 'CBSS'
        ]);

        Center::create([
            'name' => 'CGG'
        ]);

        Center::create([
            'name' => 'COPINE'
        ]);

        Center::create([
            'name' => 'CSTD'
        ]);

        Center::create([
            'name' => 'CSTP'
        ]);

        Center::create([
            'name' => 'NASRDA'
        ]);

        Center::create([
            'name' => 'NCRS'
        ]);

        Center::create([
            'name' => 'NIGCOMSAT'
        ]);
    }
}
