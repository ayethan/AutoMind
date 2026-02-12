<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarMadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("car_mades")->insert([
            ["name" => "Audi"],
            ["name" => "Ferrari"],
            ["name" => "Honda"],
            ["name" => "Hyundai"],
            ["name" => "Mercedes"],
            ["name" => "Kia"],
            ["name" => "Jeep"],
            ["name" => "Mini"],
            ["name" => "Chevrolet"],
            ["name" => "Jaguar"],
            ["name" => "Nissan"],
            ["name" => "Mitsubishi"],
            ["name" => "Suzuki"],
            ["name" => "Tata"],
            ["name" => "Volkswagen"],
            ["name" => "BMW"],
            ["name" => "Land Rover"],
            ["name" => "Toyota"],
        ]);
    }
}
