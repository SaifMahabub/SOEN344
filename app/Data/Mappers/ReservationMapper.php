<?php

namespace App\Data\Mappers;

use App\Data\IdentityMaps\ReservationIdentityMap;
use App\Data\TDGs\ReservationTDG;
use App\Data\UoWs\ReservationUoW;
use App\Data\Reservation;
use App\Singleton;
use Carbon\Carbon;

/**
 * @method static ReservationMapper getInstance()
 */
class ReservationMapper extends Singleton
{

    /**
     * @var ReservationTDG
     */
    private $tdg;

    /**
     * @var ReservationIdentityMap
     */
    private $identityMap;

    /**
     * ReservationMapper constructor.
     */
    protected function __construct()
    {
        parent::__construct();

        $this->tdg = ReservationTDG::getInstance();
        $this->identityMap = ReservationIdentityMap::getInstance();
    }

    /**
     * Handles the creation of a new object of type Reservation
     *
     * @param int $userId
     * @param string $roomName
     * @param \DateTime $timeslot
     * @param string $description
     * @param string $uuid
     * @return Reservation
     */
    public function create(int $userId, string $roomName, \DateTime $timeslot, string $description, string $uuid): Reservation
    {
        $reservation = new Reservation($userId, $roomName, $timeslot, $description, $uuid);

        // add the new Reservation to the list of existing objects in live memory
        $this->identityMap->add($reservation);

        // add to UoW registry so that we create it in the DB once the reservation is ready to commit everything
        ReservationUoW::getInstance()->registerNew($reservation);

        return $reservation;
    }

    /**
     * Fetch message for retrieving a Reservation with the given ID
     *
     * @param int $id
     * @return Reservation|null
     */
    public function find(int $id)
    {
        $reservation = $this->identityMap->get($id);
        $result = null;

        // if Identity Map doesn't have it, use TDG
        if ($reservation === null) {
            $result = $this->tdg->find($id);
        }

        // if TDG doesn't have it, it doesn't exist
        if ($result !== null) {
            // we got the Reservation from the TDG who got it from the DB and now the mapper must add it to the ReservationIdentityMap
            $reservation = new Reservation(intval($result->user_id), $result->room_name, new Carbon($result->timeslot), $result->description, $result->recur_id, intval($result->id));
            $this->identityMap->add($reservation);
        }

        return $reservation;
    }

    /**
     * @param string $roomName
     * @param \DateTime $timeslot
     * @return Reservation[]
     */
    public function findForTimeslot(string $roomName, \DateTime $timeslot): array
    {
        $results = $this->tdg->findForTimeslot($roomName, $timeslot);
        $reservations = [];

        foreach ($results as $result) {
            if ($reservation = $this->identityMap->get($result->id)) {
                $reservations[] = $reservation;
            } else {
                $reservation = new Reservation(intval($result->user_id), $result->room_name, new Carbon($result->timeslot), $result->description, $result->recur_id, intval($result->id));
                $this->identityMap->add($reservation);
                $reservations[] = $reservation;
            }
        }

        return $reservations;
    }

    /**
     * @param Reservation $reservation
     * @return int
     */
    public function findPosition(Reservation $reservation): int
    {
        // get a list of all the other reservations for the same room-timeslot
        $reservations = $this->findForTimeslot($reservation->getRoomName(), $reservation->getTimeslot());

        // find which position we're in the waitlist
        $position = 0;
        foreach ($reservations as $r) {
            if ($r->getId() === $reservation->getId()) {
                break;
            }

            ++$position;
        }

        return $position;
    }

    /**
     * @param \DateTime $date
     * @return Reservation[]|array
     */
    public function findAllActive(\DateTime $date): array
    {
        $results = $this->tdg->findAllActive($date);
        $reservations = [];

        foreach ($results as $result) {
            if ($reservation = $this->identityMap->get($result->id)) {
                $reservations[] = $reservation;
            } else {
                $reservation = new Reservation(intval($result->user_id), $result->room_name, new Carbon($result->timeslot), $result->description, $result->recur_id, intval($result->id));
                $this->identityMap->add($reservation);
                $reservations[] = $reservation;
            }
        }

        return $reservations;
    }

    /**
     * @param int $user_id
     * @return array[]
     */
    public function findPositionsForUser(int $user_id): array
    {
        $results = $this->tdg->findPositionsForUser($user_id);
        $reservations = [];

        foreach ($results as $result) {
            if ($reservation = $this->identityMap->get($result->id)) {
                $reservations[] = [$reservation, $result->position];
            } else {
                $reservation = new Reservation(intval($result->user_id), $result->room_name, new Carbon($result->timeslot), $result->description, $result->recur_id, intval($result->id));
                $this->identityMap->add($reservation);
                $reservations[] = [$reservation, intval($result->position)];
            }
        }

        return $reservations;
    }

    /**
     * Returns the number of reservations for a certain user within a date range
     *
     * @param int $userId
     * @param \DateTime $start Start date, inclusive
     * @param \DateTime $end End date, exclusive
     * @return int
     */
    public function countInRange(int $userId, \DateTime $start, \DateTime $end): int
    {
        return $this->tdg->countInRange($userId, $start, $end);
    }

    /**
     * @param int $id
     * @param string $description
     */
    public function set(int $id, string $description)
    {
        $reservation = $this->find($id);

        $reservation->setDescription($description);

        // we've modified something in the object so we register the instance as dirty in the UoW
        ReservationUoW::getInstance()->registerDirty($reservation);
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        // first we fetch the client by checking the identity map
        $reservation = $this->find($id);

        // if the identity map returned the object, then remove it from the IdentityMap
        if ($reservation !== null) {
            $this->identityMap->delete($reservation);

            // we want to delete this object from out DB, so we simply register it as deleted in the UoW
            ReservationUoW::getInstance()->registerDeleted($reservation);
        }
    }

    /**
     * Finalize changes
     */
    public function done()
    {
        ReservationUoW::getInstance()->commit();
    }

    /**
     * Pass the list of Reservations to add to DB to the TDG
     *
     * @param array $newList
     */
    public function addMany(array $newList)
    {
        $this->tdg->addMany($newList);
    }

    /**
     * Pass the list of Reservations to update in the DB to the TDG
     *
     * @param array $updateList
     */
    public function updateMany(array $updateList)
    {
        $this->tdg->updateMany($updateList);
    }

    /**
     * Pass the list of Reservations to remove from DB to the TDG
     *
     * @param array $deleteList
     */
    public function deleteMany(array $deleteList)
    {
        $this->tdg->deleteMany($deleteList);
    }
}
