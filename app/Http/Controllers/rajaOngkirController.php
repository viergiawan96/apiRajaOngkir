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
                return ResponseFormatter::error(null, 'Data Gagal Di ambil Cek Kembali ID', 404);
        }elseif($name) {
                $province = province::where('province_name', $id)->get();
                
                if($province)
                return ResponnseFormatter::success($province,"Data Kota Berdasarkan ID Berhasil Diambil");
                else
                return ResponseFormatter::error(null, 'Data Gagal Di ambil Cek Kembali ID', 404);
        }else{
                $province = province::all();

                return ResponnseFormatter::success($province,"Data Kota Berdasarkan ID Berhasil Diambil", 404);

        }
    }

    public function getCourier() {
            $courier = courier::all();

            return ResponnseFormatter::success($courier,"Data Kurir Berhasil Di Ambil");

    }

    private function getCost(Request $request) {

        $this->validate($request, [
            'origin' => 'required|integer',
            'destination' => 'required|integer',
            'weight' => 'required|integer',
            'courier' => 'required|array'
        ]);
        
        $cityOrigin = city::where('city_id', $request->input('origin'))->get();
        $cityDestination = city::where('city_id', $request->input('destination'))->get();

        if(!$cityOrigin)
                return ResponseFormatter::error(null, 'ID Kota Asal Salah', 404);
        if(!$cityDestination)
                return ResponseFormatter::error(null, 'ID Kota Asal Tujuan', 404);


        $courier = $request->input('courier');
        $cost = [];
        $origin_details= '';
        $destination_details= '';

        foreach($courier as $cr){
            $daftarProvinsi = RajaOngkir::ongkosKirim([
                'origin'        => $request->input('origin'),            // ID kota/kabupaten asal
                'destination'   => $request->input('destination'),       // ID kota/kabupaten tujuan
                'weight'        => $request->input('weight'),            // berat barang dalam gram
                'courier'       => $cr                                   // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
            ])->get();
           //  array_push($cost, $daftarProvinsi);
           if(!empty($daftarProvinsi)) {
                foreach($daftarProvinsi['rajaongkir']['results'] as $cost) {
                        if(!empty($cost['costs'])) {
                                foreach($cost['costs'] as $costDetail) {
                                        $serviceName = strtoupper($cost['code'] .'-'. $costDetail['service']);
                                        $costAmount = $costDetail['cost'][0]['value'];
                                        $etd = $costDetail['cost'][0]['etd'];
                                        
                                        $results = [
                                                'service' => $serviceName,
                                                'cost' => $costAmount,
                                                'etd' => $etd,
                                                'courier' => $cr,
                                        ];

                                        $cost[] = $results;
                                }
                        }
                }

                $origin_details = $daftarProvinsi['rajaongkir']['origin_details']['city_name'];
                $destination_details = $daftarProvinsi['rajaongkir']['destination_details']['city_name'];
           }
        }
        $response = [
                'origin' => $request->input('origin'),
                'origin_details' => $origin_details,
                'destination' => $request->input('destination'),
                'destination_details' => $destination_details,
                'weight' => $request->input('weight'),
                'results' => $cost,
        ];

        if(!empty($cost))
            return ResponseFormatter::success($response,"Data Berhasil Diambil");
        else
            return ResponseFormatter::error(null, 'Koneksi Bermasalah Silahkan Di Coba Kembali');

    }
}