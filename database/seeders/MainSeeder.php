<?php

namespace Database\Seeders;

use App\Models\city;
use App\Models\contract;
use App\Models\education;
use App\Models\jenis_perusahaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use App\Models\province;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // Jenis Perusahaan
        $JenisPerusahaanCsvFile = fopen(base_path("database/data/jenis_perusahaan.csv"), "r");

        while (($data = fgetcsv($JenisPerusahaanCsvFile, 2000, ",")) !== FALSE) {
                jenis_perusahaan::create([
                    "jenis_perusahaan" => $data['0']
                ]);
        }

        fclose($JenisPerusahaanCsvFile);
        // Provinsi
        $ProvinceCsvFile = fopen(base_path("database/data/provinsi.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($ProvinceCsvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                province::create([
                    "id" => $data['0'],
                    "province" => $data['2']
                ]);
            }
            $firstline = false;
        }

        fclose($ProvinceCsvFile);

        // Kota
        $CityCsvFile = fopen(base_path("database/data/city.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($CityCsvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                city::create([
                    "id" => $data['0'],
                    "city" => $data['2'],
                    "id_province"=>$data['1']
                ]);
            }
            $firstline = false;
        }

        fclose($CityCsvFile);

        // Education
        $EducationCsvFile = fopen(base_path("database/data/education.csv"), "r");

        while (($data = fgetcsv($EducationCsvFile, 2000, ",")) !== FALSE) {
                education::create([
                    'education'=>$data['0']
                ]);
        }
        fclose($EducationCsvFile);

        // Contract
        $ContractCsvFile = fopen(base_path("database/data/contract.csv"), "r");

        while (($data = fgetcsv($ContractCsvFile, 2000, ",")) !== FALSE) {
            contract::create([
                "contract" => $data['0'],
            ]);
        }
        fclose($ContractCsvFile);

        // Pekerjaan
        $PekerjaanCsvFile = fopen(base_path("database/data/pekerjaan.csv"),'r');
        while (($data = fgetcsv($PekerjaanCsvFile, 2000, ",")) !== FALSE) {
            contract::create([
                "contract" => $data['0'],
            ]);
        }
        fclose($PekerjaanCsvFile);

    }
}
