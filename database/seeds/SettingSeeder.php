<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key' => 'tax',
                'value' => '5',
                'data_type' => 'int',
                'remark' => 'The percentage of amount that government takes for tax.'
            ],
            [
                'key' => 'default-pagination',
                'value' => '15',
                'data_type' => 'int',
                'remark' => 'The default pagiantion of the list models.'
            ]
        ]);
    }
}
