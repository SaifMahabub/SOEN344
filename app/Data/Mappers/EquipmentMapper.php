<?php

namespace App\Data\Mappers;

use App\Data\IdentityMaps\EquipmentIdentityMap;
use App\Data\TDGs\EquipmentTDG;
use App\Data\Equipment;
use App\Singleton;

/**
 * @method static EquipmentMapper getInstance()
 */
class EquipmentMapper extends Singleton
{

    /**
     * @var EquipmentTDG
     */
    private $tdg;

    /**
     * @var EquipmentIdentityMap
     */
    private $identityMap;

    /**
     * UserMapper constructor.
     */
    protected function __construct()
    {
        parent::__construct();

        $this->tdg = EquipmentTDG::getInstance();
        $this->identityMap = EquipmentIdentityMap::getInstance();
    }

    /**
     * Fetch message for retrieving an Equipment with the given ID
     *
     * @param int $id
     * @return Equipment
     */
    public function find(int $id): Equipment
    {
        $equipment = $this->identityMap->get($id);
        $result = null;

        // If Identity Map doesn't have it then use TDG.
        if ($equipment === null) {
            $result = $this->tdg->find($id);
        }

        // If TDG doesn't have it then it doens't exist.
        if ($result !== null) {
            //We got the client from the TDG who got it from the DB and now the mapper must add it to the ClientIdentityMap
            $equipment = new Equipment($result->id, (string)$result->name, $result->amount);
            $this->identityMap->add($equipment);
        }

        return $equipment;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $results = $this->tdg->findAll();
        $equipments = [];

        foreach ($results as $result) {
            if ($equipment = $this->identityMap->get($result->id)) {
                $equipments[] = $equipment;
            } else {
                $equipment = new Equipment($result->id, (string)$result->name, $result->amount);
                $this->identityMap->add($equipment);
                $equipments[] = $equipment;
            }
        }

        return $equipments;
    }
}
