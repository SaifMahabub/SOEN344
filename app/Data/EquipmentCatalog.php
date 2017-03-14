<?php

namespace App\Data;

use App\Data\Mappers\EquipmentMapper;


class EquipmentCatalog
{

    /**
     * @var equipmentMapper
     */
	private $equipmentMapper;

	 protected function __construct()
    {
        parent::__construct();

        $this->equipmentMapper = EquipmentMapper::getInstance();
    }

	public function find(int $id): Equipment
    {
        $equipment = $this->equipmentMapper->find($id)
        
       return $equipment;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $equipment = $this->equipmentMapper->findAll();

        return $equipment;
    }
}