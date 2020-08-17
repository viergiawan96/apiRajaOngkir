<?php

use Illuminate\Database\Seeder;

use App\rajaongkir\couriers;

class couriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('couriers')->truncate();
        $data = [
            ['code' => 'jne', 'title' => 'JNE'],
            ['code' => 'pos', 'title' => 'POS'],
            ['code' => 'tiki', 'title' => 'TIKI']
        ];

        Couriers::insert($data);
    }
}
