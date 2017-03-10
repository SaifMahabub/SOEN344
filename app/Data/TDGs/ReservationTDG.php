<?php

namespace App\Data\TDGs;

use App\Data\Reservation;
use App\Singleton;
use DB;
use Illuminate\Database\QueryException;

/**
 * @method static ReservationTDG getInstance()
 */
class ReservationTDG extends Singleton
{
    /**
     * Adds a list of Reservations to the database
     *
     * @param array $newList
     */
    public function addMany(array $newList)
    {
        foreach ($newList as $reservation) {
            if (($id = $this->create($reservation)) !== null) {
                $reservation->setId($id);
            }
        }
    }
    
    /**
     * Updates a list of Reservations in the database
     *
     * @param array $updateList
     */
    public function updateMany(array $updateList)
    {
        foreach ($updateList as $user) {
            $this->update($user);
        }
    }

    /**
     * Removes a list of Reservations in the database
     *
     * @param array $deleteList
     */
    public function deleteMany(array $deleteList)
    {
        foreach ($deleteList as $reservation) {
            $this->remove($reservation);
        }
    }

    /**
     * SQL statement to create a new Reservation row
     *
     * @param Reservation $reservation
     * @return int
     */
    public function create(Reservation $reservation)
    {
        $id = null;

        try {
            $id = DB::table('reservations')->insertGetId([
                'user_id' => $reservation->getUserId(),
                'room_name' => $reservation->getRoomName(),
                'timeslot' => $reservation->getTimeslot(),
                'description' => $reservation->getDescription(),
                'equipment_id' => $reservation->getEquipmentId(),
                'recur_id' => $reservation->getRecurId(),
                'waitlisted' => (int)$reservation->getWaitlisted()
            ]);
        } catch (QueryException $e) {
            // error inserting, duplicate row
        }

        return $id;
    }

    /**
     * SQL statement to update a new Reservation row
     *
     * @param Reservation $reservation
     */
    public function update(Reservation $reservation)
    {
        DB::update('UPDATE reservations SET description = :description, waitlisted = :waitlisted WHERE id = :id', [
            'id' => $reservation->getId(),
            'description' => $reservation->getDescription(),
            'waitlisted' => (int)$reservation->getWaitlisted()
        ]);
    }

    /**
     * SQL statement to delete Reservation rows based on the Reservation id, or the recurrence id
     *
     * @param Reservation $reservation
     */
    public function remove(Reservation $reservation)
    {
        DB::delete('DELETE FROM reservations WHERE id = :id OR (recur_id = :recur_id AND timeslot >= CURDATE())', [
            'id' => $reservation->getId(),
            'recur_id' => $reservation->getRecurId()
        ]);
    }

    /**
     * SQL statement to find a Reservation by its id
     *
     * @param int $id
     * @return \stdClass|null
     */
    public function find(int $id)
    {
        $reservations = DB::select('SELECT * FROM reservations WHERE id = ?', [$id]);

        if (empty($reservations)) {
            return null;
        }

        return $reservations[0];
    }

    /**
 * Returns a list of all Reservations for a given room-timeslot, ordered by waitlisted = false, then by isCapstone for waiting list
 *
 * @param string $roomName
 * @param \DateTime $timeslot
 * @return array
 */
    public function findForTimeslot(string $roomName, \DateTime $timeslot)
    {
        return DB::select('SELECT r.*, u.isCapstone
            FROM reservations r
            LEFT JOIN users u
            ON r.user_id = u.id
            WHERE r.timeslot = :timeslot AND r.room_name = :room_name
            ORDER BY r.waitlisted, u.isCapstone DESC', ['timeslot' => $timeslot, 'room_name' => $roomName]);
    }

    /**
     * Returns a list of all ACTIVE Reservations for a given equipment-timeslot
     *
     * @param int $equipmentId
     * @param \DateTime $timeslot
     * @return array
     */
    public function findActiveForTimeWithEquipment(int $equipId, \DateTime $timeslot)
    {
        return DB::select('SELECT *
            FROM reservations
            WHERE timeslot = :timeslot AND equipment_id = :equipment_id AND waitlisted = 0',
            ['timeslot' => $timeslot, 'equipment_id' => $equipId]
        );
    }

    /** Returns a list of reservations that are pending equipment availability,
     * but are ready to become active because no active reservations exists for their time/room */
    public function findReadyToBeActiveForTimeWithEquipment(\DateTime $timeslot, int $equipmentId)
    {
        return DB::select('SELECT *
            FROM reservations
            WHERE timeslot = :timeslot AND equipment_id = :equipment_id AND waitlisted = 1
            AND room_name NOT IN
            (SELECT room_name
            FROM reservations
            WHERE waitlisted = 0
            GROUP BY room_name)',
            ['timeslot' => $timeslot, 'equipment_id' => $equipmentId]
        );
    }

    /**
     * Returns a list of all active (eg. not waitlisted) reservations for a date
     *
     * @param \DateTime $date
     * @return array
     */
    public function findAllActive(\DateTime $date)
    {
        return DB::select('SELECT r1.*
            FROM reservations r1
            JOIN (SELECT min(id) AS id
	            FROM reservations
	            WHERE waitlisted = FALSE 
	            GROUP BY room_name, timeslot) r2 ON r1.id = r2.id
	        WHERE DATE(timeslot) = DATE(?)
            ORDER BY timeslot;', [$date]);
    }

    /**
     * Returns a list of all Reservations and their waiting list positions for a user
     *
     * @param int $user_id
     * @return array
     */
    public function findPositionsForUser(int $user_id)
    {
        return DB::select('SELECT t.* FROM (
                SELECT x.*,
                    @rank_count := CASE 
                      WHEN waitlisted = 1 AND (@prev_room_name <> room_name OR @prev_timeslot <> timeslot) THEN 1
                      WHEN @prev_room_name <> room_name OR @prev_timeslot <> timeslot THEN 0
                      ELSE @rank_count + 1 END AS position,
                    @prev_timeslot := timeslot AS _prev_timeslot,
                    @prev_room_name := room_name AS _prev_room_name
                FROM 
                    (SELECT @prev_room_name := -1, @prev_timeslot := -1, @rank_count := -1) v,
                    (SELECT r.*, u.isCapstone
                    from reservations r
                    LEFT JOIN users u
                    on r.user_id = u.id) x
                ORDER BY room_name, timeslot, waitlisted, isCapstone DESC, id) t
                WHERE user_id = ? AND timeslot >= CURDATE()
            ORDER BY timeslot;', [$user_id]);
    }

    /**
     * SQL statement to count the reservations for a certain user within a date range
     *
     * @param int $user_id
     * @param \DateTime $start Start date, inclusive
     * @param \DateTime $end End date, exclusive
     * @return int
     */
    public function countInRange(int $user_id, \DateTime $start, \DateTime $end): int
    {
        return DB::table('reservations')
            ->where('user_id', $user_id)
            ->where('timeslot', '>=', $start)
            ->where('timeslot', '<', $end)
            ->count();
    }
}
