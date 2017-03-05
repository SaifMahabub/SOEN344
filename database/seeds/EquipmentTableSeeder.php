<?php

use Illuminate\Database\Seeder;

class EquipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $equipmentNames = ['projector', 'computer'];

        foreach ($equipmentNames as $equipmentName) {
            DB::table('equipment')->insert([
                'name' => $equipmentName,
                'amount' => rand(1,3),
            ]);
        }
    }
}
