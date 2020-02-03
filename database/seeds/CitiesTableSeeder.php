<?php

use Illuminate\Database\Seeder;
use App\Models\City;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = RajaOngkir::Kota()->all();

        City::truncate();
        foreach ($cities as $city) {
            City::firstOrCreate([
                'city_id' => $city['city_id'],
                'province_id' => $city['province_id'],
                'province' => $city['province'],
                'type' => $city['type'],
                'name' => $city['city_name'],
                'postal_code' => $city['postal_code'],
            ]);
        }
    }
}
