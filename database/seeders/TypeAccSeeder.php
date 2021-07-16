<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TypeAccSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type_acc = [
            [
                'type_name' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'type_name' => 'Nhân viên',
                'created_at' => Carbon::now(),
                'updated_at'=> Carbon::now()
            ]
        ];
        DB::table('type_acc')->insert($type_acc);
    }
}
