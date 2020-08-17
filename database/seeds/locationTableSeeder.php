<?php

use Illuminate\Database\Seeder;
use App\rajaongkir\province;
use App\rajaongkir\city;
use Kavist\RajaOngkir\Facades\RajaOngkir;


class locationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provinces')->truncate();
        DB::table('cities')->truncate();

        $province = RajaOngkir::provinsi()->all();
        foreach($province as $provinceRow){
            province::create([
                'province_id' => $provinceRow['province_id'],
                'province_name' => $provinceRow['province']
            ]);
            $city = RajaOngkir::kota()->dariProvinsi($provinceRow['province_id'])->get();
            foreach($city  as $cityRaw) {
                city::create([
                    'province_id' => $provinceRow['province_id'],
                    'city_id' => $cityRaw['city_id'],
                    'type' => $cityRaw['type'],
                    'city_name' => $cityRaw['city_name'],
                    'post_code' => $cityRaw['postal_code']
                ]);
            }
        }

    }
}
