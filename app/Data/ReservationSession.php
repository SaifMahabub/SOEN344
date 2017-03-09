<?php

namespace App\Data;

/**
 * @method static ReservationSession getInstance()
 */
class ReservationSession
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $roomName;

    /**
     * @var \DateTime
     */
    protected $timeslot;

    /**
     * Lock constructor.
     * @param string $roomName
     * @param \DateTime $timeslot
     */
    public function __construct($userId, string $roomName, \DateTime $timeslot)
    {
        $this->userId = $userId;
        $this->roomName = $roomName;
        $this->timeslot = $timeslot;
    }

    public function getUserId (){
        return $this->userId;
    }

    public function getRoomName (){
        return $this->roomName;
    }

    public function getTimeslot (){
        return $this->timeslot;
    }


}