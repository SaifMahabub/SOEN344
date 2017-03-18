<?php

namespace App\Data\UoWs;

use App\Data\Mappers\ReservationMapper;
use App\Data\Reservation;
use App\Singleton;

/**
 * @method static ReservationUoW getInstance()
 */
class ReservationUoW extends Singleton
{
    private $newList = array();
    private $changedList = array();
    private $deletedList = array();

    /**
     * @var ReservationMapper
     */
    private $reservationMapper;

    protected function __construct()
    {
        parent::__construct();

        $this->reservationMapper = ReservationMapper::getInstance();
    }

    public function registerNew(Reservation $reservation)
    {
        array_push($this->newList, $reservation);
    }

    public function registerDirty(Reservation $reservation)
    {
        array_push($this->changedList, $reservation);
    }

    public function registerDeleted(Reservation $reservation)
    {
        array_push($this->deletedList, $reservation);
    }

    public function commit()
    {
        $this->reservationMapper->addMany($this->newList);
        $this->reservationMapper->updateMany($this->changedList);
        $this->reservationMapper->deleteMany($this->deletedList);

        // empty the lists after the commit
        unset($this->newList);
        $this->newList = array();

        unset($this->changedList);
        $this->changedList = array();

        unset($this->deletedList);
        $this->deletedList = array();
    }
}
