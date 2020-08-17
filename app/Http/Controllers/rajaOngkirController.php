<?php

namespace App\Http\Controllers;
use Kavist\RajaOngkir\Facades\RajaOngkir;

use Illuminate\Http\Request;
use App\rajaongkir\city;
use App\rajaongkir\courier;
use App\rajaongkir\province;

class rajaOngkirController extends Controller
{
    public function getCity(Request $request){

        $this->validate($request,[
            'id'=> 'integer',
            'name'=> 'string',
            'postcode'=> 'integer',
            'provinceId'=> 'integer',
            'type'=> 'string'
        ]);
        
        $id = $request->input('id');
        $name = $request->input('name');
        $postcode = $request->input('postcode');
        $provinceId = $request->input('provinceId'); 
        $type = $request->input('type'); 

        if($id) {
                $city = city::where('city_id', $id)->get();

                if($city)
                return ResponseFormatter::success($city, 'Data Kota Berdasarkan ID Berhasil Di Ambil');
                else
                return ResponseFormatter::error(null, 'Data Gagal di ambil cek kembali ID', 404);

        }elseif ($name) {
                $city = city::where('city_name',like,'%'.$name.'%')->get();

                if($city)
                return ResponseFormatter::success($city, 'Data Kota Berdasarkan Nama Kota Berhasil Di Ambil');
                else
                return ResponseFormatter::error(null, 'Data Gagal di ambil cek kembali Nama Kota', 404);

        }elseif($postcode) {
                $city = city::where('post_code', $postcode)->get();

                if($city)
                return ResponseFormatter::success($city, 'Data Kota Berdasarkan Post Code Berhasil Di Ambil');
                else
                return ResponseFormatter::error(null, 'Data Gagal di ambil cek kembali Post Code', 404);

        }elseif($provinceId) {
                $city = city::where('province_id', $provinceId)->get();

                if($city)
                return ResponseFormatter::success($city, 'Data Kota Berdasarkan Province ID Berhasil Di Ambil');
                else
                return ResponseFormatter::error(null, 'Data Gagal di ambil cek kembali Province ID', 404);

        }elseif($type) {
                $city = city::where('type', $type)->get();

                if($city)
                return ResponseFormatter::success($city, 'Data Kota Berdasarkan Type Berhasil Di Ambil');
                else
                return ResponseFormatter::error(null, 'Data Gagal di ambil cek kembali Type', 404);

        }else {
                $city = city::all();

                return ResponseFormatter::success($city, 'Semua Data Berhasil Diambil');
        }
    }

    public function getProvince(Request $request) {

        $this->validate($request, [
            'id' => 'integer',
            'name' => 'string'
        ]);
        
        $id = $request->input('id');
        $name = $request->input('name');

        if($id){
                $province = province::where('province_id', $id)->get();

                if($province)
                return ResponnseFormatter::success($province,"Data Kota Berdasarkan ID Berhasil Diambil");
                else
                return ResponseFormatter::error(null, 'Data Gagal Di ambil Cek Kambali ID');
        }elseif($name) {
                $province = province::where('province_name', $id)->get();
                
                if($province)
                return ResponnseFormatter::success($province,"Data Kota Berdasarkan ID Berhasil Diambil");
                else
                return ResponseFormatter::error(null, 'Data Gagal Di ambil Cek Kambali ID');
        }else{
                $province = province::all();

                return ResponnseFormatter::success($province,"Data Kota Berdasarkan ID Berhasil Diambil");

        }
    }

    public function getCourier() {
            $courier = courier::all();

            return ResponnseFormatter::success($courier,"Data Kurir Berhasil Di Ambil");

    }

    public function getCost(Request $request) {

        $this->validate($request, [
            'origin' => 'required|integer',
            'destination' => 'required|integer',
            'weight' => 'required|integer',
            'courier' => 'required|array'
        ]);

        $courier = $request->input('courier');
        $cost = [];

        foreach($courier as $cr){
            $daftarProvinsi = RajaOngkir::ongkosKirim([
                'origin'        => $request->input('origin'),            // ID kota/kabupaten asal
                'destination'   => $request->input('destination'),       // ID kota/kabupaten tujuan
                'weight'        => $request->input('weight'),            // berat barang dalam gram
                'courier'       => $cr                                   // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
            ])->get();

            // $cost = $daftarProvinsi;
            array_push($cost, $daftarProvinsi);
        }

        return ResponseFormatter::success($cost,"Data Berhasil Diambil");

    }
}