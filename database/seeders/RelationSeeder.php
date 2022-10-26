<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Relation;
use Illuminate\Support\Carbon;

class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Relation::truncate();

        $dataFile = fopen(base_path("database/seeders/beginData/relation.csv"), "r");

        $dataRow = true;
        while (($data = fgetcsv($dataFile, 4000, ";")) !== FALSE) {
            if (!$dataRow) {
                if (preg_replace('/[^0-9]/', '', $data['1']) !== '' && preg_replace('/[^0-9]/', '', $data['2']) !== ''):
                    Relation::create([
                        "parent_objid" => $data['0'],
                        "begda" => Carbon::parse($data['1']),
                        "endda" => Carbon::parse($data['2']),
                        "objid" => $data['3'],
                        "npp" => $data['4'],
                    ]);
                endif;
            }
            $dataRow = false;
        }

        fclose($dataFile);

    }

}
