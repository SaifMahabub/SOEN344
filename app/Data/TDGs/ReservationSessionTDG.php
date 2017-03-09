<?php

namespace App\Data\TDGs;
use App\Singleton;
use DB;
use App\Data\ReservationSession;

class ReservationSessionTDG extends Singleton
{

    // Generate a Reservation session to prevent other users from accessing this resource/room
    // Each session expires after a minute, for more information see database/.../
    // migrations/2017_03_05_225059_create_session_event_scheduler.php
    public function makeNewSession(ReservationSession $session){
        DB::insert('INSERT INTO session (userId, roomName, timeslot, timestamp) VALUES (:userId, :roomName, :timeslot, 
        :timestamp)', [
            'userId' => $session->getUserId(),
            'roomName' => $session->getRoomName(),
            'timeslot' => $session->getTimeslot(),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    // Remove the Reservation session to allow other users from accessing this resource/room
    public function endSession(ReservationSession $session){
        if($session->getRoomName()==null AND $session->getTimeslot()==null){
            DB::delete('DELETE FROM session WHERE userId = :userId', [
                'userId' => $session->getUserId()
            ]);
        }
        DB::delete('DELETE FROM session WHERE userId = :userId AND roomName = :roomName AND timeslot = :timeslot', [
            'userId' => $session->getUserId(),
            'roomName' => $session->getRoomName(),
            'timeslot' => $session->getTimeslot()
        ]);
    }

    public function checkSessionInProgress (ReservationSession $session){
        $sessionInProgress = DB::select('SELECT * FROM session WHERE userId = :userId', [
            'userId' => $session->getUserId()
        ]);

        if (empty($sessionInProgress)) {
            return false;
        }
        return true;
    }

    public function checkLock (ReservationSession $session){
        $lock = DB::select('SELECT * FROM session WHERE roomName = :roomName AND timeslot = :timeslot', [
            'roomName' => $session->getRoomName(),
            'timeslot' => $session->getTimeslot()
        ]);

        if (empty($lock)) {
            return false;
        }

        return true;
    }
}