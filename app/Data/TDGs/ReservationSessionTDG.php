<?php

namespace App\Data\TDGs;
use App\Singleton;
use DB;
use App\Data\ReservationSession;

class ReservationSessionTDG extends Singleton
{

    public function makeNewSession(ReservationSession $session){
        DB::insert('INSERT INTO session (userId, roomName, timeslot) VALUES (:userId, :roomName, :timeslot)', [
            'userId' => $session->getUserId(),
            'roomName' => $session->getRoomName(),
            'timeslot' => $session->getTimeslot()
        ]);
    }

    public function endSession(ReservationSession $session){
        DB::delete('DELETE FROM session WHERE userId = :userId AND roomName = :roomName AND timeslot = :timeslot', [
            'userId' => $session->getUserId(),
            'roomName' => $session->getRoomName(),
            'timeslot' => $session->getTimeslot()
        ]);
    }
}