<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ObjectRGD;
use Illuminate\Support\Carbon;

class ObjectRGDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ObjectRGD::truncate();

        $dataFile = fopen(base_path("database/seeders/beginData/object.csv"), "r");

        $dataRow = true;
        while (($data = fgetcsv($dataFile, 4000, ";")) !== FALSE) {
            if (!$dataRow) {
                if (preg_replace('/[^0-9]/', '', $data['1']) !== '' && preg_replace('/[^0-9]/', '', $data['2']) !== ''):
                    ObjectRGD::create([
                        "objid" => $data['0'],
                        "begda" => Carbon::parse($data['1']),
                        "endda" => Carbon::parse($data['2']),
                        "stext" => $data['3'],
                    ]);
                endif;
            }
            $dataRow = false;
        }

        fclose($dataFile);

    }
}
