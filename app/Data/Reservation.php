<?php

namespace App\Data;

class Reservation
{
    /**
     * @var int
     */
    protected $id;

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
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $recurId;

    /**
     * @var boolean
     */
    protected $waitlisted;

    /**
     * Room constructor.
     * @param int $userId
     * @param string $roomName
     * @param \DateTime $timeslot
     * @param string $description
     * @param null $recurId
     * @param int $id
     * @param bool $waitlisted
     */
    public function __construct(int $userId, string $roomName, \DateTime $timeslot, string $description = null, $recurId = null, bool $waitlisted = true, $id = null)
    {
        $this->userId = $userId;
        $this->roomName = $roomName;
        $this->description = $description;
        $this->timeslot = $timeslot;
        $this->recurId = $recurId;
        // key
        $this->id = $id;
        $this->waitlisted = $waitlisted;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getRoomName(): string
    {
        return $this->roomName;
    }

    /**
     * @param string $roomName
     */
    public function setRoomName(string $roomName)
    {
        $this->roomName = $roomName;
    }

    /**
     * @return \DateTime
     */
    public function getTimeslot(): \DateTime
    {
        return $this->timeslot;
    }

    /**
     * @param \DateTime $timeslot
     */
    public function setTimeslot(\DateTime $timeslot)
    {
        $this->timeslot = $timeslot;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getRecurId(): string
    {
        return $this->recurId;
    }

    /**
     * @param string $recurId
     */
    public function setRecurId(string $recurId)
    {
        $this->recurId = $recurId;
    }

    /**
     * @return bool
     */
    public function getWaitlisted() : string
    {
        return $this->waitlisted;
    }

    /**
     * @param bool $w
     */
    public function setWaitlisted(boolean $w)
    {
        $this->waitlisted = $w;
    }
}
