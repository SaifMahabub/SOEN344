<?php

namespace App\Data\TDGs;

use App\Data\Equipment;
use App\Singleton;
use DB;

/**
 * @method static EquipmentTDG getInstance()
 */
class EquipmentTDG extends Singleton
{
    /**
     * Gets a specific Equipment from the database by id
     *
     * @param int $id
     * @return \stdClass|null
     */
    public function find(int $id)
    {
        $equipments = DB::select('SELECT * FROM equipment WHERE id = ?', [$id]);

        if (empty($equipments)) {
            return null;
        }

        return $equipments[0];
    }

    /**
     * Gets all equipment from the database
     *
     * @return array
     */
    public function findAll()
    {
        $equipments = DB::select('SELECT * FROM equipment');

        return $equipments;
    }
}
