<?php

namespace App\Data;

use App\Data\Mappers\RoomMapper;


class RoomCatalog
{

    /**
     * @var roomMapper
     */
	private $roomMapper;

	 protected function __construct()
    {
        parent::__construct();

        $this->roomMapper = RoomMapper::getInstance();
    }

	public function find(string $name): Room
    {
        $room = $this->roomMapper->find($name)
        
       return $room;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $rooms = $this->roomMapper->findAll();

        return $rooms;
    }
}