<?php

namespace App\Data\IdentityMaps;

use App\Data\Equipment;
use App\Singleton;

/**
 * @method static EquipmentIdentityMap getInstance()
 */
class EquipmentIdentityMap extends Singleton
{
    private $memory = [];

    /**
     * @param int $id
     * @return Equipment|null
     */
    public function get(int $id)
    {
        if (isset($this->memory[$id])) {
            return $this->memory[$id];
        }

        return null;
    }

    /**
     * @param Equipment $equipment
     */
    public function add(Equipment $equipment)
    {
        $memory[$equipment->getId()] = $equipment;
    }

    /**
     * @param Equipment $equipment
     */
    public function delete(Equipment $equipment)
    {
        $id = $equipment->getId();

        if (isset($this->memory[$id])) {
            unset($this->memory[$id]);
        }
    }
}
